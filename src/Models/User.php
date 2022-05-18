<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\Timeable;

/**
 * @property $uri
 * @property $name
 * @property $slug
 * @property $email
 * @property $timezone
 * @property $avatar_url
 * @property $scheduling_url
 */
class User extends BaseModel
{
    public static string $resource = "users";

    use Timeable;

    /**
     * The URL of the user's avatar (image)
     */
    public function getAvatarUrl(): ?string {
        return $this->getField("avatar_url");
    }

    /**
     * The URL of the user's Calendly landing page (that lists all the user's event types)
     */
    public function getSchedulingUrl(): ?string {
        return $this->getField("scheduling_url");
    }
}