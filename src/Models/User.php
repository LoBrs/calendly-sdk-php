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
 * @property $current_organization
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

    /**
     * @param array $options
     * @return Event[]
     * @throws \Exception
     */
    public function getScheduledEvents(array $options = []) {
        return Event::getList(array_merge([
            "organization" => $this->current_organization,
            "user"         => $this->uri,
        ], $options));
    }
}