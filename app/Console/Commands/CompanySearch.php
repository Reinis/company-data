<?php

namespace App\Console\Commands;

use App\Services\CompanyDataService;
use Illuminate\Console\Command;

class CompanySearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:search {term}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display matching data';

    /**
     * Execute the console command.
     */
    public function handle(CompanyDataService $companyDataService): int
    {
        $headers = [
            'regcode',
            'name',
            'type',
            'address',
        ];

        $result = $companyDataService->search($this->argument('term') ?? '')->toArray();

        $this->table($headers, $result);

        return 0;
    }
}
