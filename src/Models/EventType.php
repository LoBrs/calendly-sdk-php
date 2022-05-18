<?php

namespace Calendly\Models;

use Calendly\Traits\Listable;
use Calendly\Traits\Timeable;

/**
 * @property $uri
 * @property $name
 * @property $active
 * @property $slug
 * @property $scheduling_url
 * @property $duration
 * @property $kind
 * @property $pooling_type
 * @property $type
 * @property $color
 * @property $internal_note
 * @property $description_plain
 * @property $description_html
 * @property $secret
 * @property $booking_method
 * @property $deleted_at
 */
class EventType extends BaseModel
{
    public static string $resource = "event_types";

    use Listable;
    use Timeable;

    /**
     * The event type's description
     *
     * @param bool $html Formatted with HTML or non formatted
     * @return string
     */
    public function getDescription(bool $html = true): string {
        return $this->getField($html ? "description_html" : "description_plain", "");
    }

    public function getProfile(): Profile {
        return new Profile((array)$this->getField("profile"));
    }
}