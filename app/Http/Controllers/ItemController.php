<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return JsonResponse::create(['items' => (new ItemsSerializer($items))->getData()]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|string|max:255',
          'price' => 'required|numeric',
           'url' => 'required|url',
          'description' => 'required|string',
        ]);

        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);

        $item = Item::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'url' => $request['url'],
            'description' => $converter->convert($request['description'])->getContent(),
        ]);

        $serializer = new ItemSerializer($item);

        return new JsonResponse(['item' => $serializer->getData()]);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);

        $serializer = new ItemSerializer($item);

        return new JsonResponse(['item' => $serializer->getData()]);
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'url' => 'required|url',
            'description' => 'required|string',
        ]);

        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);

        $item = Item::findOrFail($id);
        $item->name = $request['name'];
        $item->url = $request['url'];
        $item->price = $request['price'];
        $item->description = $converter->convert($request['description'])->getContent();
        $item->save();

        return new JsonResponse(
            [
                'item' => (new ItemSerializer($item))->getData()
            ]
        );
    }
}
