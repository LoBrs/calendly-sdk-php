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

    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $current_options;

    public function __construct(array $response, $current_options = [], $model = null) {
        $this->collection = $response["collection"] ?? [];
        $this->pagination = $response["pagination"] ?? [];
        $this->current_options = $current_options;
        $this->model = $model;
    }

    public function getCollection() {
        return $this->collection;
    }

    public function countResults(): int {
        return $this->pagination["count"] ?? 0;
    }

    public function getNextPageURL(): ?string {
        return $this->pagination["next_page"] ?? null;
    }

    public function getPreviousPageURL(): ?string {
        return $this->pagination["previous_page"] ?? null;
    }

    public function getNextPageToken(): ?string {
        return $this->pagination["next_page_token"] ?? null;
    }

    public function getPreviousPageToken(): ?string {
        return $this->pagination["previous_page_token"] ?? null;
    }

    public function next(array $options = []): ?PaginatedList {
        if (!empty($this->getNextPageToken()) && method_exists($this->model, "paginate")) {
            $options["page_token"] = $this->getNextPageToken();
            return $this->model::paginate(array_merge($this->current_options, $options));
        }

        return null;
    }

    public function previous(array $options = []): ?PaginatedList {
        if (!empty($this->getPreviousPageToken()) && method_exists($this->model, "paginate")) {
            $options["page_token"] = $this->getPreviousPageToken();
            return $this->model::paginate(array_merge($this->current_options, $options));
        }

        return null;
    }

}