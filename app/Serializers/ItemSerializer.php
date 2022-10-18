<?php

namespace App\Serializers;

use App\Models\Item;

class ItemSerializer extends AbstractSerializer
{
    private Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    protected function serialize(): void
    {
        $this->data['id'] = $this->item->id;
        $this->data['name'] = $this->item->name;
        $this->data['price'] = number_format($this->item->price, 2, '.', ' ');
        $this->data['url'] = $this->item->url;
        $this->data['description'] = trim($this->item->description);
    }
}
