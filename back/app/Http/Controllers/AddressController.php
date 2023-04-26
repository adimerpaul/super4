<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Cog;
use Illuminate\Http\Request;

class AddressController extends Controller{
    public function index(Request $request){
        $address=Address::where('user_id', $request->user()->id)->get();
        $lat= Cog::where('name', 'lat')->first()->value;
        $lng= Cog::where('name', 'lng')->first()->value;
        $de0a1= Cog::where('name', 'de0a1')->first()->value;
        $de1a2= Cog::where('name', 'de1a2')->first()->value;
        $de2a3= Cog::where('name', 'de2a3')->first()->value;
        $de3a4= Cog::where('name', 'de3a4')->first()->value;
        $de4a5= Cog::where('name', 'de4a5')->first()->value;
        $de5a6= Cog::where('name', 'de5a6')->first()->value;
        $de6a7= Cog::where('name', 'de6a7')->first()->value;
        $de7a8= Cog::where('name', 'de7a8')->first()->value;
        $de9a10= Cog::where('name', 'de9a10')->first()->value;
        $de10a100= Cog::where('name', 'de10a100')->first()->value;
        $address->each(function ($address) use ($de10a100, $de9a10, $de7a8, $de6a7, $de5a6, $de4a5, $de3a4, $de2a3, $de1a2, $de0a1, $lng, $lat) {
            $distance= $this->distanceLatLng($lat, $lng, $address->lat, $address->lng);
            $address->distance = $distance;
            if($distance <= 1){
                $address->deliveryAmount = $de0a1;
            }elseif($distance <= 2){
                $address->deliveryAmount = $de1a2;
            }elseif($distance <= 3){
                $address->deliveryAmount = $de2a3;
            }elseif($distance <= 4){
                $address->deliveryAmount = $de3a4;
            }elseif($distance <= 5){
                $address->deliveryAmount = $de4a5;
            }elseif($distance <= 6){
                $address->deliveryAmount = $de5a6;
            }elseif($distance <= 7){
                $address->deliveryAmount = $de6a7;
            }elseif($distance <= 8){
                $address->deliveryAmount = $de7a8;
            }elseif($distance <= 9){
                $address->deliveryAmount = $de9a10;
            }elseif($distance <= 100){
                $address->deliveryAmount = $de10a100;
            }else{
                $address->deliveryAmount = 1000;
            }
            $address->save();
        });
        return $address;
    }
    public function store(StoreAddressRequest $request){
        $addresses = Address::where('user_id', $request->user()->id)->get();
        $addresses->each(function ($address) {
            $address->active = false;
            $address->save();
        });
        $request->merge(['user_id' => $request->user()->id]);
        $request->merge(['active' => true]);

        return Address::create($request->all());
    }
    public function distanceLatLng($lat1, $lon1, $lat2, $lon2){
        $earth_radius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;
        return $d;
    }
    public function show(Address $address){ return $address; }
    public function update(UpdateAddressRequest $request, Address $address){ $address->update($request->all()); return $address; }
    public function destroy(Address $address){ $address->delete(); return response()->noContent(); }
}
