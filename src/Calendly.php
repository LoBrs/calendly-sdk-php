<?php

namespace LoBrs\Calendly;

/**
 * @method static Models\User me()
 * @method static Models\User getUser(string $uuid)
 * @method static Models\EventType[] getEventTypes(array $options)
 */
class Calendly
{
    static private ?CalendlyApi $sdk = null;
    static private string $token = "";

    public static function __callStatic($name, $arguments) {
        return call_user_func_array([self::getClient(), $name], $arguments);
    }

    public static function setToken($token): ?CalendlyApi {
        self::$token = $token;
        return self::getClient();
    }

    public static function getClient(): ?CalendlyApi {
        if (!self::$sdk) {
            if (empty(self::$token)) {
                throw new \InvalidArgumentException("Calendly token must be set");
            }
            self::$sdk = new CalendlyApi(self::$token);
        }

        return self::$sdk;
    }
}