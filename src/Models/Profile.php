<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Exceptions\MissingCalendlyTokenException;

/**
 * The publicly visible profile of a User or a Team that's associated with the Event Type.
 *
 * @see EventType
 * @property string $type Indicates if the profile belongs to a "user" (individual) or "team"
 * @property string $name Human-readable name for the profile of the user that's associated with the event type
 * @property string $owner The unique reference to the user associated with the profile
 */
class Profile extends BaseModel
{
    /**
     * @return User|null
     * @throws ApiErrorException|InvalidArgumentException|MissingCalendlyTokenException
     */
    public function getOwner(): ?User {
        if (empty($this->owner)) {
            return null;
        }
        return Calendly::getClient()->getUser($this->owner);
    }
}