<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\Deletable;
use LoBrs\Calendly\Traits\NestedRelationListable;
use LoBrs\Calendly\Traits\Timeable;

/**
 *
 */
class OrganizationMembership extends BaseModel
{
    use NestedRelationListable;
    use Timeable;
    use Deletable;

    public static string $resource = "organization_memberships";

    static function getParentResource() {
        return Organization::$resource;
    }
}