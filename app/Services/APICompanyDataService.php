<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use Generator;
use Illuminate\Support\Collection;
use RuntimeException;

class APICompanyDataService implements CompanyDataService
{
    private string $baseName = 'https://data.gov.lv/dati/lv';
    private string $resourceId = "25e80bf3-f107-4ab4-89ef-251b5b9374e9";

    public function getLastId(): int
    {
        return Company::max('_id') ?? 0;
    }

    public function getRecords(int $limit, int $offset = 0): Generator
    {
        $result = $this->fetchPage(null, $offset, $limit);

        while (count($result['result']['records']) > 0) {
            if (!$result['success']) {
                throw new RuntimeException("Failed to retrieve records");
            }

            yield $result['result']['records'];

            $result = $this->fetchPage($result['result']['_links']['next']);
        }
    }

    private function fetchPage(?string $queryString = null, int $offset = 0, int $limit = 1): array
    {
        if (null === $queryString) {
            $queryString = "/api/3/action/datastore_search";
            $queryString .= "?resource_id={$this->resourceId}";
            $queryString .= "&sort=_id";
            $queryString .= "&offset={$offset}";
            $queryString .= "&limit={$limit}";
        }

        $contents = file_get_contents($this->baseName . $queryString);

        return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    }

    public function count(): int
    {
        $result = $this->fetchPage();

        return $result['result']['total'];
    }

    public function search(string $searchTerm): Collection
    {
        return Company::select(
            [
                'regcode',
                'name_in_quotes',
                'type',
                'address',
            ]
        )
            ->where(
                'name',
                'like',
                '%' . $this->escape_like($searchTerm) . '%'
            )
            ->get();
    }

    /**
     * Escape special characters for a LIKE query.
     */
    private function escape_like(string $value, string $char = '\\'): string
    {
        return str_replace(
            [$char, '%', '_'],
            [$char . $char, $char . '%', $char . '_'],
            $value
        );
    }
}
