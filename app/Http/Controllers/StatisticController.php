<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Item;

class StatisticController extends Controller
{
    //
    public function index(Request $request)
    {
        $total_price = Item::sum('price');
        $count = Item::count();
        $this_month_price = Item::ofThisMonth()->sum('price');
        $highest_site = Item::groupBy('url')
                        ->selectRaw('sum(price) as sum, url')
                        ->orderBy('sum','desc')
                        ->first()->url;
        $avg_price = $total_price / $count;
        return new JsonResponse(
            [
                'avg_price' => $avg_price,
                'couont' => $count,
                'this_month_price' => $this_month_price,
                'highest_site' => $highest_site
            ]
        );
    }
}
