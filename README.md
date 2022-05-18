# Calendly PHP SDK

### Installation

```
composer require lobrs/calendly-sdk-php
```

### Usage

```
\LoBrs\Calendly\Calendly::setToken($token);
$user = \LoBrs\Calendly\Calendly::me();
```

### Examples

```
$user = User::get($uuid);
$scheduled_events = \Calendly\Models\Event::getList([
    "user"   => $user->uri
]);
```