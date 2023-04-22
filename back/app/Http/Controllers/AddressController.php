<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use Illuminate\Http\Request;

class AddressController extends Controller{
    public function index(Request $request){ return Address::where('user_id', $request->user()->id)->get(); }
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
    public function show(Address $address){ return $address; }
    public function update(UpdateAddressRequest $request, Address $address){ $address->update($request->all()); return $address; }
    public function destroy(Address $address){ $address->delete(); return response()->noContent(); }
}
