<?php

namespace LoBrs\Calendly\Utils;

class PaginatedList
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * @var array
     */
    protected $pagination;

    public function __construct(array $response) {
        $this->collection = $response["collection"] ?? [];
        $this->pagination = $response["pagination"] ?? [];
    }

    public function getCollection() {
        return $this->collection;
    }

    public function countResults(): int {
        return $this->pagination["count"] ?? 0;
    }

    public function getNextPage(): ?string {
        return $this->pagination["next_page"] ?? null;
    }

    public function getPreviousPage(): ?string {
        return $this->pagination["previous_page"] ?? null;
    }

    public function getNextPageToken(): ?string {
        return $this->pagination["next_page_token"] ?? null;
    }

    public function getPreviousPageToken(): ?string {
        return $this->pagination["previous_page_token"] ?? null;
    }

}