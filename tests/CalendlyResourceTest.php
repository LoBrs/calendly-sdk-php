<?php

namespace LoBrs\Calendly\Test;

use LoBrs\Calendly\Models\Event;
use LoBrs\Calendly\Models\User;
use LoBrs\Calendly\Utils\PaginatedList;
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

    public function testEventList() {
        $response = [
            "collection" => [
                [
                    "uri"               => "https://api.calendly.com/scheduled_events/GBGBDCAADAEDCRZ2",
                    "name"              => "15 Minute Meeting",
                    "status"            => "active",
                    "start_time"        => "2019-08-24T14:15:22.123456Z",
                    "end_time"          => "2019-08-24T14:15:22.123456Z",
                    "event_type"        => "https://api.calendly.com/event_types/GBGBDCAADAEDCRZ2",
                    "location"          => [
                        "type"     => "physical",
                        "location" => "Calendly Office",
                    ],
                    "invitees_counter"  => [
                        "total"  => 0,
                        "active" => 0,
                        "limit"  => 0,
                    ],
                    "created_at"        => "2019-01-02T03:04:05.092125Z",
                    "updated_at"        => "2019-01-02T03:04:05.092125Z",
                    "event_memberships" => [
                        [
                            "user" => "https://api.calendly.com/users/GBGBDCAADAEDCRZ2",
                        ],
                    ],
                    "event_guests"      => [
                        [
                            "email"      => "user@example.com",
                            "created_at" => "2022-04-21T17:10:48.484945Z",
                            "updated_at" => "2022-04-21T17:11:01.758636Z",
                        ],
                    ],
                ],
            ],
            "pagination" => [
                "count"               => 1,
                "next_page"           => "https://api.calendly.com/scheduled_events?count=1&page_token=sNjq4TvMDfUHEl7zHRR0k0E1PCEJWvdi",
                "previous_page"       => "https://api.calendly.com/scheduled_events?count=1&page_token=VJs2rfDYeY8ahZpq0QI1O114LJkNjd7H",
                "next_page_token"     => "sNjq4TvMDfUHEl7zHRR0k0E1PCEJWvdi",
                "previous_page_token" => "VJs2rfDYeY8ahZpq0QI1O114LJkNjd7H",
            ],
        ];

        $data = Event::pagination($response);
        $this->assertNotNull($data);
        $this->assertInstanceOf(PaginatedList::class, $data);
        $this->assertIsArray($data->getCollection());
        $this->assertNotEmpty($data->getCollection());
        $this->assertInstanceOf(Event::class, $data->getCollection()[0]);
        $this->assertEquals(1, $data->countResults());
        $this->assertEquals("https://api.calendly.com/scheduled_events?count=1&page_token=sNjq4TvMDfUHEl7zHRR0k0E1PCEJWvdi", $data->getNextPage());
    }
}