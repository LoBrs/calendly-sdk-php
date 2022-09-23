<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Traits\Listable;
use LoBrs\Calendly\Traits\Timeable;

/**
 * Information about a scheduled meeting
 *
 * @property $uri
 * @property $name
 * @property $status
 * @property $start_time
 * @property $end_time
 * @property $event_type
 * @property $location
 * @property $invitees_counter
 * @property $event_memberships
 * @property $event_guests
 * @property $cancellation
 */
class Event extends BaseModel
{
    public static string $resource = "scheduled_events";

    use Listable;
    use Timeable;

    public function isActive(): bool {
        return $this->status == "active";
    }

    public function isCanceled(): bool {
        return $this->status == "canceled";
    }

    public function getStartDate() {
        return new \DateTime($this->start_time);
    }

    public function getEndDate() {
        return new \DateTime($this->end_time);
    }

    public function getGuests() {
        return Guest::collection((array)$this->event_guests);
    }

    public function getEventTypeId(): string {
        return self::getIdFromUri($this->event_type);
    }

    public static function cancel(string $uuid, string $reason = "") {
        $response = Calendly::getClient()->request(static::$resource . "/$uuid/cancellation", "POST", [
            "reason" => $reason,
        ]);
        if (isset($response["resource"])) {
            return $response["resource"];
        }
        return false;
    }
}