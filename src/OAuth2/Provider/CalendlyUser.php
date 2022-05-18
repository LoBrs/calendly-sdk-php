<?php

namespace Calendly\OAuth2\Provider;

use Calendly\Models\User;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class CalendlyUser extends User implements ResourceOwnerInterface
{
    //
}