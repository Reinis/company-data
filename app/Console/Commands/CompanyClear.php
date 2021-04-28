<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class CompanyClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the local database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Company::truncate();

        return 0;
    }
}
