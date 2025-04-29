<?php

namespace LoBrs\Calendly\Models;

/**
 * @property bool $active Boolean indicator of whether or not the presented token is currently active
 * @property string $scope The scope of the access token
 * @property string $client_id The ID provided by Calendly for the web application
 * @property string $token_type The token type (currently this is always "Bearer")
 * @property int $exp The UNIX timestamp in seconds when the access-token will expire
 * @property int $iat The UNIX timestamp in seconds when the access-token was originally issued
 * @property string $owner A link to the resource that owns the token (currently, this is always a user)
 * @property string $organization A link to the owner's current organization
 */
class IntrospectToken extends BaseModel
{
    /**
     * @return Organization|null
     */
    public function getOrganization(): ?Organization {
        $organization = $this->getField('organization');
        if (empty($organization)) {
            return null;
        }
        return Organization::get($organization);
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User {
        $owner = $this->getField('owner');
        if (empty($owner)) {
            return null;
        }
        return User::get($owner);
    }
}