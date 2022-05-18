<?php

namespace LoBrs\Calendly\Traits;

/**
 * @property $created_at
 * @property $updated_at
 */
trait Timeable
{

    public function getCreationDate() {
        return new \DateTime($this->created_at);
    }

    public function getUpdateDate() {
        return new \DateTime($this->updated_at);
    }
}