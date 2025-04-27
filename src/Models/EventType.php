<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Traits\Listable;
use LoBrs\Calendly\Traits\Timeable;

/**
 * @property $uri
 * @property $name
 * @property $active
 * @property $slug
 * @property $scheduling_url
 * @property $duration
 * @property $duration_options
 * @property $kind
 * @property $pooling_type
 * @property $type
 * @property $color
 * @property $internal_note
 * @property $description_plain
 * @property $description_html
 * @property $secret
 * @property $booking_method
 * @property $custom_questions
 * @property $deleted_at
 * @property $admin_managed
 * @property $locations
 * @property $position
 * @property $locale
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

    /**
     * The publicly visible profile of a User or a Team that's associated with the Event Type.
     *
     * @return ?Profile
     */
    public function getProfile(): ?Profile {
        $profile = $this->getField("profile");
        if (empty($profile)) {
            // Some Event Types don't have profiles
            return null;
        }

        return new Profile((array)$profile);
    }

    /**
     * Creates a single-use scheduling link based on the Event Type.
     *
     * @param int $maxEventCount The max number of events that can be scheduled using this scheduling link.
     * @return BaseModel|null
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function createSchedulingLink(int $maxEventCount = 1): ?BaseModel {
        return SchedulingLink::create([
            "max_event_count" => $maxEventCount,
            "owner"           => $this->getId(),
            "owner_type"      => "EventType",
        ]);
    }
}