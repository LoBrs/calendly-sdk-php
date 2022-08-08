<?php

namespace LoBrs\Calendly\Exceptions;

use Throwable;

class InvalidArgumentException extends \InvalidArgumentException
{
    private array $details;

    public function __construct($message = "", $details = [], Throwable $previous = null) {
        parent::__construct($message, 0, $previous);
        $this->details = $details;
    }

    public function getDetails(): array {
        return $this->details;
    }
}