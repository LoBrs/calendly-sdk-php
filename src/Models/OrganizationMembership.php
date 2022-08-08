<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Traits\ChildListable;
use LoBrs\Calendly\Traits\Deletable;
use LoBrs\Calendly\Traits\Timeable;

/**
 *
 */
class OrganizationMembership extends BaseModel
{
    use ChildListable;
    use Timeable;
    use Deletable;

    public static string $resource = "organization_memberships";

    static function getParentResource() {
        return Organization::$resource;
    }
}