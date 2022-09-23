<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\Postable;

/**
 * Single-use scheduling link.
 *
 * @property int max_event_count
 * @property string owner
 * @property string owner_type
 */
class SchedulingLink extends BaseModel
{
    use Postable;

    static string $resource = "scheduling_links";
}