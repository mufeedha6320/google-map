<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Delivery;


class LocationController extends Controller
{
    public function location(){
        $delivery = Delivery::query()->select([
            'id',
            'status',
            DB::raw("ST_X(current_location) AS lng"),
            DB::raw("ST_Y(current_location) AS lat"),
        ])->where('id',1)->first();
        return view('location', compact('delivery'));
    }
}
