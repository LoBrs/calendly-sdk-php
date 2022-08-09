<?php

namespace LoBrs\Calendly\Webhooks;

use LoBrs\Calendly\Exceptions\WebhookExpiredResponseException;
use LoBrs\Calendly\Exceptions\WebhookSignatureException;

class WebhookSignature
{
    const DEFAULT_EXPIRATION_TIME = 120;

    private string $signature;
    private string $signature_version;
    private int $signature_timestamp;

    public function __construct(string $header) {
        $header = explode(',', $header);
        $this->signature_timestamp = intval(explode('=', $header[0])[1] ?? null);
        [$this->signature_version, $this->signature] = explode('=', $header[1]);
    }

    /**
     * @param string $payload
     * @param string $header
     * @param string $secret
     * @param int $tolerance
     * @return WebhookSignature
     * @throws WebhookExpiredResponseException|WebhookSignatureException
     */
    public static function verifySignature(string $payload, string $header, string $secret, int $tolerance): WebhookSignature {

        $sig = new WebhookSignature($header);

        if (empty($sig->signature)) {
            throw new WebhookSignatureException("No valid signature found");
        }
        if (-1 === $sig->signature_timestamp || empty($sig->signature_timestamp)) {
            throw new WebhookExpiredResponseException('Signature header timestamp not found');
        }

        $expected_signature = $sig->computeSignature($payload, $secret);

        if ($sig->signature !== $expected_signature) {
            throw new WebhookSignatureException('Invalid signature');
        }

        if ($sig->signature_timestamp < time() - $tolerance) {
            throw new WebhookExpiredResponseException('Invalid Signature. The signature\'s timestamp is outside of the tolerance zone.');
        }

        return $sig;
    }

    /**
     * @param string $payload
     * @param string $secret
     * @return string
     */
    private function computeSignature(string $payload, string $secret): string {
        $data = $this->getTimestamp() . '.' . $payload;
        return \hash_hmac('sha256', $data, $secret);
    }

    public function getVersion(): string {
        return $this->signature_version;
    }

    public function getSignature(): string {
        return $this->signature;
    }

    public function getTimestamp(): int {
        return $this->signature_timestamp;
    }

    public static function getSignatureHeaders() {
        return $_SERVER['HTTP_CALENDLY_WEBHOOK_SIGNATURE'] ?? null;
    }
}