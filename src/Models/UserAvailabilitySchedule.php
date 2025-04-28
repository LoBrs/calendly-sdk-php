<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Exceptions\MissingCalendlyTokenException;
use LoBrs\Calendly\Traits\Listable;

/**
 * The availability schedule set by the user.
 *
 * @property string $uri A URI reference to this Availability Schedule.
 * @property bool $default This is the default Availability Schedule in use.
 * @property string $name The name of this Availability Schedule.
 * @property string $user A URI reference to a User.
 * @property string $timezone The timezone for which this Availability Schedule is originated in.
 * @property array $rules The rules of this Availability Schedule.
 */
class UserAvailabilitySchedule extends BaseModel
{
    public static string $resource = "user_availability_schedules";

    use Listable;

    /**
     * @return User|null
     * @throws ApiErrorException|InvalidArgumentException|MissingCalendlyTokenException
     */
    public function getUser(): ?User {
        $user = $this->getField("user");
        if (empty($user)) {
            return null;
        }

        return Calendly::getClient()->getUser($user);
    }

    /**
     * @return AvailabilityRule[]
     */
    public function getRules(): array {
        return AvailabilityRule::collection((array)$this->rules);
    }

}