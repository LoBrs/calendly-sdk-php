<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Traits\Timeable;
use LoBrs\Calendly\Utils\PaginatedList;

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
     * @return PaginatedList
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function getScheduledEvents(array $options = []) {
        return Event::paginate(array_merge([
            "organization" => $this->current_organization,
            "user"         => $this->uri,
        ], $options));
    }

    /**
     * @return Organization|null
     */
    public function getCurrentOrganization(): ?Organization {
        if (!empty($this->getField("current_organization"))) {
            return Organization::get($this->current_organization);
        }

        return null;
    }
}