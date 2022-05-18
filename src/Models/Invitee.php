<?php

namespace Calendly\Models;

use Calendly\Traits\ChildListable;

class Invitee extends BaseModel
{
    public static string $resource = "invitees";

    use ChildListable;

    static function getParentResource() {
        return Event::$resource;
    }
}