<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Traits\Postable;

/**
 * Single-use scheduling link.
 *
 * @property int max_event_count The max number of events that can be scheduled using this scheduling link.
 * @property string owner A link to the resource that owns this Scheduling Link
 * @property string owner_type Resource type
 */
class SchedulingLink extends BaseModel
{
    use Postable;

    static string $resource = "scheduling_links";
}