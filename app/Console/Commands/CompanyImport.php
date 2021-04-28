<?php

namespace App\Console\Commands;

use App\Console\Progress;
use App\Models\Company;
use App\Services\CompanyDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CompanyImport extends Command
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

            DB::connection()->unsetEventDispatcher();

            foreach ($progressBar->iterate($companyDataService->getRecords($limit, $offset), $max) as $batch) {
                $progressBar->setMessage(last($batch)['_id']);
                Company::upsert(
                    $batch,
                    ['_id'],
                    $fillable
                );
            }
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->newLine();

        return 0;
    }
}
