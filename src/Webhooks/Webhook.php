<?php

namespace LoBrs\Calendly\Webhooks;

use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Exceptions\WebhookExpiredResponseException;
use LoBrs\Calendly\Exceptions\WebhookSignatureException;
use LoBrs\Calendly\Models\BaseModel;
use UnexpectedValueException;

class Webhook
{
    const SCOPE_USER = "user";
    const SCOPE_ORGANIZATION = "organization";

    /**
     * Create a Webhook Subscription for an Organization or User.
     *
     * @param string $url The URL where you want to receive POST requests for events you are subscribed to.
     * @param array $events List of user events to subscribe to (invitee.created, invitee.canceled).
     * @param string|array $params UUID of the user/organization or a complete array of parameters for the webhook subscription.
     * @param ?string $webhook_signing_key Optional secret key shared between your application and Calendly
     * @return BaseModel|null
     * @throws ApiErrorException
     * @throws InvalidArgumentException
     */
    public static function subscribe(string $url, array $events, $params, ?string $webhook_signing_key = null): ?BaseModel {
        $options = [
            "url"    => $url,
            "events" => $events,
        ];
        if (is_string($params)) {
            $scope = strpos($params, "organization") !== false ? self::SCOPE_ORGANIZATION : self::SCOPE_USER;
            $params = [
                "scope"  => $scope,
                $scope   => $params,
            ];
        }

        $options = array_merge($options, $params);

        if (!empty($webhook_signing_key)) {
            $options['signing_key'] = $webhook_signing_key;
        }

        return WebhookSubscription::create($options);
    }

    /**
     * Returns the WebhookEvent corresponding to the provided JSON payload.
     *
     * @param string $payload Payload sent by Calendly
     * @param string $signature_header
     * @param string $secret_key Secret used to generate the signature
     * @param int $expire Maximum difference (seconds) allowed between the header's timestamp and the current time
     * @return WebhookEvent
     * @throws WebhookExpiredResponseException|WebhookSignatureException|UnexpectedValueException
     */
    public static function readEvent(
        string $payload,
        string $signature_header,
        string $secret_key,
        int $expire = WebhookSignature::DEFAULT_EXPIRATION_TIME
    ): WebhookEvent {

        WebhookSignature::verifySignature($payload, $signature_header, $secret_key, $expire);

        $data = json_decode($payload, true);

        if ($data === null || !isset($data['payload'])) {
            throw new UnexpectedValueException('Invalid payload.');
        }

        return new WebhookEvent($data);
    }

}