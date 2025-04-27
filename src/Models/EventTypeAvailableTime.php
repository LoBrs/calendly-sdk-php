<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\Listable;

/**
 * An available meeting time slot for the given event type
 *
 * @see EventType
 * @property string $status Indicates that the open time slot is "available"
 * @property int $invitees_remaining Total remaining invitees for this available time. For Group Event Type, more than one invitee can book in this available time. For all other Event Types, only one invitee can book in this available time.
 * @property string $start_time The moment the event was scheduled to start in UTC time
 * @property string $scheduling_url The URL of the user’s scheduling site where invitees book this event type
 */
class EventTypeAvailableTime extends BaseModel
{
    public static string $resource = "event_type_available_times";

    use Listable;
}