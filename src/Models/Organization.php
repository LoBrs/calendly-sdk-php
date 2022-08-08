<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Traits\Timeable;

/**
 * @property $uri
 * @property $plan Active subscription plan or trial plan
 * @property $stage Current stage of organization
 */
class Organization extends BaseModel
{
    public static string $resource = "organizations";

    use Timeable;

    /**
     * @return OrganizationInvitation[]
     * @throws \Exception
     */
    public function getInvitations() {
        return OrganizationInvitation::getList($this->getId());
    }

    /**
     * @return OrganizationMembership[]
     * @throws \Exception
     */
    public function getMemberShips() {
        return OrganizationMembership::getList($this->getId());
    }

    /**
     * Invite a user to join an organization.
     *
     * @param string $email
     * @return ?OrganizationInvitation
     * @throws \Exception
     */
    public function invite(string $email) {
        $response = Calendly::getClient()->request(static::$resource . "/" . $this->getId() . "/invitations", "POST", [
            "email" => $email,
        ]);
        if (isset($response["resource"])) {
            return new OrganizationInvitation($response["resource"]);
        }

        return null;
    }

}