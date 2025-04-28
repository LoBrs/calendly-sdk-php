<?php

namespace LoBrs\Calendly\Models;

/**
 * The rules for an availability schedule.
 *
 * @see UserAvailabilitySchedule
 * @property string $type The type of this Availability Rule; can be "wday" or a specific "date".
 * @property array $intervals The intervals to be applied to this Rule. Each interval represents when booking a meeting is allowed. If the interval array is empty, then there is no booking availability for that day. Time is in 24h format (i.e. "17:30") and local to the timezone in the Availability Schedule.
 * @property string $wday The day of the week for which this Rule should be applied to.
 * @property string $date A specific date in the future that this should be applied to (i.e. "2030-12-31").
 */
class AvailabilityRule extends BaseModel
{
}