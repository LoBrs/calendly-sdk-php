<?php

namespace Calendly\Models;

use Calendly\Traits\Timeable;

/**
 * @property $email
 */
class Guest extends BaseModel
{
    use Timeable;
}