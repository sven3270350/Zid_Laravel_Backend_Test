<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use App\Models\Item;

class ItemStatistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic {parameter?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show statistics for Item model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $parameter = $this->argument('parameter');
        $total_price = Item::sum('price');
        $count = Item::count();
        $this_month_price = Item::ofThisMonth()->sum('price');
        $highest_site = Item::groupBy('url')
                        ->selectRaw('sum(price) as sum, url')
                        ->orderBy('sum','desc')
                        ->first()->url;
        $avg_price = $total_price / $count;
        $data['avg_price'] = $avg_price;
        $data['count'] = $count;
        $data['this_month_price'] = $this_month_price;
        $data['highest_site'] = $highest_site;
        if ($parameter == '') {
            $this->info(new JsonResponse($data));
        } else {
            $this->info(new JsonResponse($data[$parameter]));
        }
        return 0;
    }
}
