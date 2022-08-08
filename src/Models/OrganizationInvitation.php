<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvitationAlreadyAcceptedException;
use LoBrs\Calendly\Traits\ChildListable;
use LoBrs\Calendly\Traits\Timeable;

/**
 * @property $uri
 * @property $organization Canonical reference (unique identifier) for the organization
 * @property $email
 * @property $status
 * @property $last_sent_at The moment the invitation was last sent (e.g. "2020-01-02T03:04:05.678123Z")
 * @property $user When the invitation is accepted, a reference to the user who accepted the invitation
 */
class OrganizationInvitation extends BaseModel
{
    use ChildListable;
    use Timeable;

    public static string $resource = "invitations";

    static function getParentResource() {
        return Organization::$resource;
    }

    /**
     * Revoke user's invitation to join organization
     *
     * @return bool
     * @throws InvitationAlreadyAcceptedException|ApiErrorException
     */
    public function revoke(): bool {
        Calendly::getClient()->request(static::$resource . "/" . $this->organization . "/invitations/" . $this->getId(), "DELETE");
        return true;
    }
}