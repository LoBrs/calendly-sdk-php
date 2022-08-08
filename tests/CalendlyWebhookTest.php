<?php

namespace LoBrs\Calendly\Test;

use LoBrs\Calendly\Webhooks\WebhookSignature;
use PHPUnit\Framework\TestCase;

class CalendlyWebhookTest extends TestCase
{
    public function testSignatureHeader() {
        $t = time();
        $header = "Calendly-Webhook-Signature: t=$t,v1=5257a869e7ecebeda32affa62cdca3fa51cad7e77a0e56ff536d0ce8e108d8bd";
        $webhookSignature = new WebhookSignature($header);
        $this->assertInstanceOf(WebhookSignature::class, $webhookSignature);
        $this->assertEquals($t, $webhookSignature->getTimestamp());
        $this->assertEquals("v1", $webhookSignature->getVersion());
        $this->assertEquals("5257a869e7ecebeda32affa62cdca3fa51cad7e77a0e56ff536d0ce8e108d8bd", $webhookSignature->getSignature());
    }
}