<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function index(){
        $delivery = Delivery::query()->select([
            'id',
            'status',
            DB::raw("ST_X(current_location) AS lng"),
            DB::raw("ST_Y(current_location) AS lat"),
        ])->where('id',1)->get();
        return $delivery;
    }
    public function update(Request $request){
        Log::info('Update request received:', $request->all());
        $request->validate([
            'lng' =>['required','numeric'],
            'lat' => ['required','numeric']
        ]);
        $delivery = Delivery::find(1);
        $delivery->update([
            'current_location' => DB::raw(
                "POINT({$request->lng},{$request->lat})"
            ),
        ]);
        return response()->json($delivery); // Return a JSON response
    }

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
