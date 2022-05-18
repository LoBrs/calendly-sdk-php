# Calendly PHP SDK

### Installation

### Usage

```
Calendly::setToken($token);
```

### Examples

```
$user = Calendly::me();

$scheduled_events = \Calendly\Models\Event::getList([
    "user"   => $user->uri
]);
```