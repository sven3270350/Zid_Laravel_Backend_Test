<?php

declare(strict_types=1);

namespace App\Serializers;

class ItemsSerializer extends AbstractSerializer
{
    private $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    protected function serialize(): void
    {
        foreach ($this->items as $item) {
            $serializer = new ItemSerializer($item);
            $this->data[] = $serializer->getData();
        }
    }
}
