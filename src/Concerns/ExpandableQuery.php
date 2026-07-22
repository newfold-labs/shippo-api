<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Concerns;

/**
 * Trait for adding expand query parameters to API requests.
 */
trait ExpandableQuery
{
    /**
     * Adds an expand query parameter when fields are provided.
     *
     * @param array<string, mixed> $query  Existing query parameters.
     * @param array<int, string>   $fields Related object fields to expand.
     *
     * @return array<string, mixed> Query parameters with expand applied.
     */
    protected function withExpand(array $query, array $fields): array
    {
        if ($fields !== []) {
            $query['expand'] = implode(',', $fields);
        }

        return $query;
    }
}
