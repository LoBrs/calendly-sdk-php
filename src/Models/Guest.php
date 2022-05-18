<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\Timeable;

/**
 * @property $email
 */
class Guest extends BaseModel
{
    use Timeable;
}