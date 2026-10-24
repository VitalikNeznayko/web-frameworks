<?php

namespace App\Pagination;

/**
 * @template T
 */
final class PaginatedResult
{
    /**
     * @param list<T> $items
     */
    public function __construct(
        private readonly array $items,
        private readonly int $page,
        private readonly int $itemsPerPage,
        private readonly int $totalItems,
    ) {
    }

    /**
     * @return list<T>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getTotalPages(): int
    {
        return max(1, (int) ceil($this->totalItems / $this->itemsPerPage));
    }

    public function getFirstItem(): int
    {
        return 0 === $this->totalItems ? 0 : ($this->page - 1) * $this->itemsPerPage + 1;
    }

    public function getLastItem(): int
    {
        return 0 === $this->totalItems ? 0 : $this->getFirstItem() + \count($this->items) - 1;
    }

    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->page < $this->getTotalPages();
    }

    /**
     * @return list<int|null>
     */
    public function getPageRange(int $around = 2): array
    {
        $last = $this->getTotalPages();
        $start = max(1, $this->page - $around);
        $end = min($last, $this->page + $around);
        $pages = [];

        if ($start > 1) {
            $pages[] = 1;
            if ($start > 2) {
                $pages[] = null;
            }
        }

        for ($i = $start; $i <= $end; ++$i) {
            $pages[] = $i;
        }

        if ($end < $last) {
            if ($end < $last - 1) {
                $pages[] = null;
            }
            $pages[] = $last;
        }

        return $pages;
    }

    /**
     * @return list<int>
     */
    public function getItemsPerPageChoices(): array
    {
        return Paginator::ITEMS_PER_PAGE_CHOICES;
    }
}
