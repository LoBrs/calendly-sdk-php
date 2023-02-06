<?php

namespace LoBrs\Calendly;

use GuzzleHttp\Client;
use LoBrs\Calendly\Exceptions\MissingCalendlyTokenException;

/**
 * @method static Models\User me()
 * @method static Models\User getUser(string $uuid)
 * @method static Models\Organization getOrganization()
 * @method static Models\EventType[] getEventTypes(array $options)
 * @method static array request(string $uri, string $method = "GET", array $params = [])
 */
class Calendly
{
    static private ?CalendlyApi $sdk = null;
    static private ?Client $httpClient = null;
    static private string $token = "";

    /**
     * @throws MissingCalendlyTokenException
     */
    public static function __callStatic($name, $arguments) {
        return call_user_func_array([self::getClient(), $name], $arguments);
    }

    public static function setToken(string $token) {
        self::$token = $token;
    }

    public static function setHttpClient(Client $httpClient) {
        static::$httpClient = $httpClient;
    }

    /**
     * @throws MissingCalendlyTokenException
     */
    public static function getClient(): ?CalendlyApi {
        if (!self::$sdk) {
            if (empty(self::$token)) {
                throw new MissingCalendlyTokenException("Calendly token must be set");
            }
            self::$sdk = new CalendlyApi(self::$token, self::$httpClient);
        }

        return self::$sdk;
    }
}