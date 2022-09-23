<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\Listable;
use LoBrs\Calendly\Traits\Timeable;

/**
 * Routing form submission from users. Includes questions and answers.
 *
 * @property string $uri
 * @property string $routing_form
 * @property array $questions_and_answers
 * @property $tracking
 * @property $result
 * @property string $submitter
 * @property string $submitter_type
 */
class RoutingFormSubmission extends BaseModel
{
    static string $resource = "routing_form_submissions";

    use Timeable;
    use Listable;
}