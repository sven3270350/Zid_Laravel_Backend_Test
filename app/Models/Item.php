<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeOfThisMonth($query)
    {
        $dt = Carbon::now();
        $first = Carbon::create($dt->year,$dt->month,1,0);
        $last = Carbon::create($dt->year,$dt->month,1,0)->addMonth();
        return $query->where('created_at', '>=' , $first)->where('created_at', '<' , $last);
    }
}
