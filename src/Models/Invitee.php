<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\NestedRelationListable;
use LoBrs\Calendly\Traits\Timeable;

/**
 * @property $uri
 * @property $email
 * @property $first_name
 * @property $last_name
 * @property $name
 * @property $status
 * @property $timezone
 * @property $event
 * @property $text_reminder_number
 * @property $rescheduled
 * @property $old_invitee
 * @property $cancel_url
 * @property $reschedule_url
 * @property $cancellation
 * @property $payment
 * @property $no_show
 * @property $reconfirmation
 */
class Invitee extends BaseModel
{
    public static string $resource = "invitees";

    use NestedRelationListable;
    use Timeable;

    static function getParentResource() {
        return Event::$resource;
    }

    public function getEventId(): string {
        return self::getIdFromUri($this->event);
    }

    public function getEvent(): ?Event {
        return Event::get($this->event);
    }
}