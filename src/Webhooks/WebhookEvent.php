<?php

namespace LoBrs\Calendly\Webhooks;

use LoBrs\Calendly\Models\BaseModel;
use LoBrs\Calendly\Traits\Timeable;

/**
 * @property $event
 * @property $payload
 */
class WebhookEvent extends BaseModel
{
    use Timeable;
}