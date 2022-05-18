# Calendly PHP SDK

### Installation

```
composer require lobrs/calendly-sdk-php
```

### Usage

```
\Calendly\Facades\Calendly::setToken($token);
$user = \Calendly\Facades\Calendly::me();
```

### Examples

```
$user = User::get($uuid);
$scheduled_events = \Calendly\Models\Event::getList([
    "user"   => $user->uri
]);
```