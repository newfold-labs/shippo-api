<?php

declare(strict_types=1);

namespace Bluehost\ShippoAPI\Pagination;

/**
 * Wrapper for a paginated Shippo API list response.
 *
 * @template T
 */
final class PaginatedResponse
{
    /**
     * Creates a new paginated response.
     *
     * @param array<int, T> $results  Page of result items.
     * @param string|null   $next     URL or cursor for the next page.
     * @param string|null   $previous URL or cursor for the previous page.
     * @param int|null      $count    Total number of items, if provided by the API.
     */
    public function __construct(
        private array $results,
        private ?string $next = null,
        private ?string $previous = null,
        private ?int $count = null
    ) {
    }

    /**
     * Returns the result items for the current page.
     *
     * @return array<int, T> Page results.
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Returns the URL or cursor for the next page, if available.
     *
     * @return string|null Next page identifier.
     */
    public function getNext(): ?string
    {
        return $this->next;
    }

    /**
     * Returns the URL or cursor for the previous page, if available.
     *
     * @return string|null Previous page identifier.
     */
    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    /**
     * Returns the total item count reported by the API, if available.
     *
     * @return int|null Total count.
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * Maps each result item through a callback.
     *
     * @template U
     *
     * @param callable(T): U $mapper Transformation callback.
     *
     * @return array<int, U> Mapped results.
     */
    public function map(callable $mapper): array
    {
        return array_map($mapper, $this->results);
    }
}
