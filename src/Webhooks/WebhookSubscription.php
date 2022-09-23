<?php

namespace LoBrs\Calendly\Webhooks;

use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InternalServerErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Models\BaseModel;
use LoBrs\Calendly\Models\User;
use LoBrs\Calendly\Traits\Deletable;
use LoBrs\Calendly\Traits\Listable;
use LoBrs\Calendly\Traits\Postable;
use LoBrs\Calendly\Traits\Timeable;

/**
 * Webhook Subscription Object
 *
 * @property string $uri
 * @property string $callback_url The callback URL to use when the event is triggered
 * @property string $retry_started_at The date and time the webhook subscription is retried
 * @property string $state "active" or "disabled"
 * @property array $events A list of events to which the webhook is subscribed
 * @property string $scope user or organization
 * @property string $organization
 * @property string $user
 * @property string $creator The URI of the user who created the webhook subscription
 */
class WebhookSubscription extends BaseModel
{
    public static string $resource = "webhook_subscriptions";

    const EVENT_INVITEE_CREATED = "invitee.created";
    const EVENT_INVITEE_CANCELED = "invitee.canceled";
    const EVENT_ROUTING_FORM_SUBMISSION_CREATED = "routing_form_submission.created";

    use Listable;
    use Postable;
    use Timeable;
    use Deletable;

    /**
     * @return \DateTime|null
     */
    public function getRetryStartedDate(): ?\DateTime {
        if (!empty($this->retry_started_at)) {
            return new \DateTime($this->retry_started_at);
        }

        return null;
    }

    /**
     * @return User|null
     * @throws ApiErrorException|InternalServerErrorException|InvalidArgumentException
     */
    public function getCreator(): ?User {
        if (empty($this->creator)) {
            return null;
        }
        return User::get($this->creator);
    }

}