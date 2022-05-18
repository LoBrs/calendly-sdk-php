<?php

use Calendly\Facades\Calendly;

require '../vendor/autoload.php';

$token = "eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2F1dGguY2FsZW5kbHkuY29tIiwiaWF0IjoxNjUyODc4MDA1LCJqdGkiOiI5MTRlZGM5ZC1hOThiLTRlZTgtYmVjMS1lZTQ3MTRjYmJjYmMiLCJ1c2VyX3V1aWQiOiJHRkRIVVNMVjVCRkpUQldWIn0.k7Yk8rGCXdI2GsZ9aTGQ0sa7B8EPaAKh-n6uhBfRhuI";
Calendly::setToken($token);

var_dump(Calendly::me()->uri);
var_dump(Calendly::me()->getId());

echo "\n";
var_dump(\Calendly\Models\EventType::getList([
    "active" => 1,
    "user"   => Calendly::me()->uri,
]));

echo "\n";

$scheduled_events = \Calendly\Models\Event::getList([
    "user"   => Calendly::me()->uri,
]);
var_dump($scheduled_events[0]->toArray());
var_dump(\Calendly\Models\Invitee::getList($scheduled_events[0]->getId()));