<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ItemCRUDTest extends TestCase
{
    use RefreshDatabase;

    public function test_getting_items(): void
    {
        Item::factory()->amazon()->count(3)->create();
        Item::factory()->zid()->count(4)->create();
        Item::factory()->steam()->count(1)->create();

        $response = $this->getJson('/items');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('items')->etc();
            $json->has('items.0', function (AssertableJson $json) {
                $json
                    ->whereType('id', 'integer')
                    ->whereType('name', 'string')
                    ->whereType('url', 'string')
                    ->whereType('price', 'string')
                    ->whereType('description', 'string')
                ;
            });
        });
    }

    public function test_getting_single_item(): void
    {
        $attributes = [
            'name' => 'Test item',
            'price' => 12300.45,
            'url' => 'https://example.zid.store/a3fc9978-51b9-334e-bc79-c4607c4e988e',
            'description' => 'Test description',
        ];

        $item = Item::factory()->create($attributes);

        $response = $this->getJson('/items/' . $item->id);

        $response->assertStatus(200);

        $responseItem = $response->json()['item'];

        $this->assertSame($item->id, $responseItem['id']);
        $this->assertSame($attributes['name'], $responseItem['name']);
        $this->assertSame('12 300.45', $responseItem['price']);
        $this->assertSame($attributes['url'], $responseItem['url']);
        $this->assertSame($attributes['description'], $responseItem['description']);
    }

    public function test_creating_new_item_with_valid_data(): void
    {
        $response = $this->postJson('/items', [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => 'Test **item** description',
        ]);

        $this->assertSame('New item', $response->json()['item']['name']);

        $this->assertDatabaseHas(Item::class, [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => "<p>Test <strong>item</strong> description</p>\n",
        ]);
    }

    public function test_creating_new_item_with_invalid_data(): void
    {
        $response = $this->postJson('/items', [
            'name' => 'New item',
            'price' => 'string',
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }

    public function test_updating_item_with_valid_data(): void
    {
        $item = Item::factory()->create();

        $response = $this->putJson('/items/ ' . $item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => 'Test _item_ description',
        ]);

        $this->assertSame('Updated title', $response->json()['item']['name']);
        $this->assertSame(
            '<p>Test <em>item</em> description</p>',
            $response->json()['item']['description']
        );

        $this->assertDatabaseHas(Item::class, [
            'id' => $item->id,
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => "<p>Test <em>item</em> description</p>\n",
        ]);
    }

    public function test_updating_item_with_invalid_data(): void
    {
        $item = Item::factory()->create();

        $response = $this->putJson('/items/ ' . $item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }
}
