<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use Generator;
use Illuminate\Support\Collection;

interface CompanyDataService
{
    public function getRecords(int $limit, int $offset = 0): Generator;

    public function getLastId(): int;

    public function count(): int;

    public function search(string $searchTerm): Collection;

    public function getByRegcode(int $regcode): Company;
}
