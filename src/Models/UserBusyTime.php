<?php

namespace LoBrs\Calendly\Models;

use DateTime;
use DateTimeZone;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Traits\Listable;

/**
 * @property string $type Indicates whether the scheduled event is internal ('calendly' or 'reserved') or external
 * @property string $start_time The start time of the scheduled event in UTC time
 * @property string $end_time The end time of the scheduled event in UTC time
 * @property string $buffered_start_time The start time of the calendly event, as calculated by any "before" buffer set by the user
 * @property string $buffered_end_time The end time of the calendly event, as calculated by any "after" buffer set by the user
 * @property object $event The uri property associated with the calendly scheduled event
 */
class UserBusyTime extends BaseModel
{
    public static string $resource = "user_busy_times";

    use Listable;

    /**
     * @return DateTime The start time of the scheduled event in UTC time
     */
    public function getStartTime(): DateTime {
        return new DateTime($this->start_time, new DateTimeZone('UTC'));
    }

    /**
     * @return DateTime The end time of the scheduled event in UTC time
     */
    public function getEndTime(): DateTime {
        return new DateTime($this->end_time, new DateTimeZone('UTC'));
    }

    /**
     * @return DateTime The start time of the calendly event, as calculated by any "before" buffer set by the user in UTC time
     */
    public function getBufferedStartTime(): DateTime {
        return new DateTime($this->buffered_start_time, new DateTimeZone('UTC'));
    }

    /**
     * @return DateTime The end time of the calendly event, as calculated by any "after" buffer set by the user in UTC time
     */
    public function getBufferedEndTime(): DateTime {
        return new DateTime($this->buffered_end_time, new DateTimeZone('UTC'));
    }

    /**
     * @return Event|null
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function getEvent(): ?Event {
        $event = $this->getField('event');
        if (empty($event) || empty($event['uri'])) {
            return null;
        }
        return Event::get($event['uri']);
    }

}