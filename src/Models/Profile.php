<?php

namespace Calendly\Models;

use Calendly\Facades\Calendly;

/**
 * @property $type
 * @property $name
 * @property $owner
 */
class Profile extends BaseModel
{
    public function getOwner(): ?User {
        if (empty($this->owner)) {
            return null;
        }
        return Calendly::getClient()->getUser($this->owner);
    }
}