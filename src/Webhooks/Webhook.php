<?php

namespace Calendly\Webhooks;

use Calendly\Models\BaseModel;
use Calendly\Traits\Listable;
use Calendly\Traits\Postable;
use Calendly\Traits\Timeable;

/**
 * @property string $uri
 * @property string $callback_url
 * @property string $retry_started_at
 * @property string $state
 * @property array $events
 * @property string $scope
 * @property string $organization
 * @property string $user
 * @property string $creator
 */
class Webhook extends BaseModel
{
    public static string $resource = "webhook_subscriptions";

    const EVENT_INVITEE_CREATED = "invitee.created";
    const EVENT_INVITEE_CANCELED = "invitee.canceled";

    use Listable;
    use Postable;
    use Timeable;

    public function getRetryStartedDate() {
        if (!empty($this->retry_started_at)) {
            return new \DateTime($this->retry_started_at);
        }

        return null;
    }
}