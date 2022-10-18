<?php

declare(strict_types=1);

namespace App\Serializers;

abstract class AbstractSerializer
{
    protected $data = [];

    abstract protected function serialize(): void;

    public function getData()
    {
        $this->serialize();

        return $this->data;
    }
}
