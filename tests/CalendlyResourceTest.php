<?php

namespace LoBrs\Calendly\Test;

use LoBrs\Calendly\Models\User;
use PHPUnit\Framework\TestCase;

class CalendlyResourceTest extends TestCase
{
    public function testUserModel() {
        $data = [
            "uri"                  => "https://api.calendly.com/users/AAAAAAAAAAAAAAAA",
            "name"                 => "John Doe",
            "slug"                 => "acmesales",
            "email"                => "test@example.com",
            "scheduling_url"       => "https://calendly.com/acmesales",
            "timezone"             => "America/New York",
            "avatar_url"           => "https://01234567890.cloudfront.net/uploads/user/avatar/0123456/a1b2c3d4.png",
            "created_at"           => "2019-01-02T03:04:05.678123Z",
            "updated_at"           => "2019-08-07T06:05:04.321123Z",
            "current_organization" => "https://api.calendly.com/organizations/AAAAAAAAAAAAAAAA",
        ];
        $user = new User($data);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("John Doe", $user->name);
        $this->assertEquals("AAAAAAAAAAAAAAAA", $user->getId());
        $this->assertEquals(new \DateTime("2019-01-02T03:04:05.678123Z"), $user->getCreationDate());
    }
}