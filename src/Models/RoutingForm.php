<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Traits\Listable;
use LoBrs\Calendly\Traits\Timeable;
use LoBrs\Calendly\Utils\PaginatedList;

/**
 * @property string $uri
 * @property string $organization
 * @property string $name
 * @property array $questions
 */
class RoutingForm extends BaseModel
{
    static string $resource = "routing_forms";

    use Timeable;
    use Listable;

    /**
     * View routing form submissions associated with the routing form's URI.
     *
     * @param array $options
     * @return PaginatedList<RoutingFormSubmission>
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function getRoutingFormSubmissions(array $options = []): PaginatedList {
        return RoutingFormSubmission::paginate(array_merge([
            "form" => $this->uri,
        ], $options));
    }
}