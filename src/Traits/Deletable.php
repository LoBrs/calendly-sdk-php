<?php

namespace LoBrs\Calendly\Traits;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Exceptions\MissingCalendlyTokenException;

trait Deletable
{
    /**
     * @throws InvalidArgumentException
     * @throws ApiErrorException
     * @throws MissingCalendlyTokenException
     */
    public function delete(array $options = []): bool {
        Calendly::getClient()->request(static::$resource . "/" . $this->getId(), "DELETE", $options);
        return true;
    }
}