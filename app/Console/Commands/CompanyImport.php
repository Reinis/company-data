<?php

namespace App\Console\Commands;

use App\Console\Progress;
use App\Models\Company;
use App\Services\CompanyDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompanyImport extends Command implements SignalableCommandInterface
{
    use Progress;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import company data from data.gov.lv';

    private bool $beenSignaled = false;

    /**
     * Execute the console command.
     */
    public function handle(CompanyDataService $companyDataService): int
    {
        $limit = 1000;
        $fillable = (new Company())->getFillable();
        $offset = $companyDataService->getLastId();
        $progressBar = $this->getProgressBar();

        try {
            $total = $companyDataService->count();

            if ($total <= $offset) {
                return 0;
            }

            $max = ceil(($total - $offset) / $limit);

            if ($max > 100 && $this->getOutput()->getVerbosity() !== OutputInterface::VERBOSITY_DEBUG) {
                $this->info("Unsetting event dispatcher for bulk import...");
                DB::connection()->unsetEventDispatcher();
            }

            $companyData = $companyDataService->getRecords($limit, $offset);

            foreach ($progressBar->iterate($companyData, $max) as $batch) {
                $progressBar->setMessage(last($batch)['_id']);
                Company::upsert(
                    $batch,
                    ['_id'],
                    $fillable
                );

                if ($this->beenSignaled) {
                    $this->info("\nStopping...");
                    return 2;
                }
            }
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->newLine();

        return 0;
    }

    public function getSubscribedSignals(): array
    {
        return [SIGINT, SIGTERM];
    }

    public function handleSignal(int $signal): void
    {
        $this->beenSignaled = true;
    }
}
