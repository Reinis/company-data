<?php

declare(strict_types=1);

namespace App\Services;

use Generator;

interface CompanyDataService
{
    public function getRecords(int $limit, int $offset = 0): Generator;

    public function getLastId(): int;

    public function count(): int;
}
