<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cog;
use App\Models\Detail;
use App\Models\Invoice;
use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\User;

class SaleController extends Controller{
    public function index(){ return Sale::whereDate('created_at', today())->get(); }
    public function store(StoreSaleRequest $request){
        $address = Address::find($request->address_id);
        if ($address) {
            $addresses = Address::where('user_id', $request->user()->id)->get();
            $addresses->each(function ($address) {
                $address->active = false;
                $address->save();
            });
            $address->active = true;
            $address->save();
            $limitDistance = Cog::where('name', 'limitDistance')->first();
            if ($address->distance > $limitDistance->value) {
                return response()->json(['message' => 'No se puede realizar la venta, la distancia es mayor a '.($limitDistance->value).' km'], 400);
            }
            if ($request->type=='store') {
                ///1////
                $request->merge(['distance' => 0]);
                $request->merge(['deliveryAmount' => 0]);
            }else{
                $request->merge(['distance' => $address->distance]);
                $request->merge(['deliveryAmount' => $address->deliveryAmount]);
            }
        }else{
            //2///
            $request->merge(['distance' => 0]);
            $request->merge(['deliveryAmount' => 0]);
        }
        $request->merge(['user_id' => $request->user()->id]);
        $request->merge(['date' => today()]);
        $request->merge(['time' => now()]);
        $request->merge(['status' => 'Pendiente']);
        $sale = Sale::create($request->all());

        $user = User::find($request->user()->id);
        $user->phone = $request->phone;
        $user->save();

        $invoices = Invoice::where('user_id', $request->user()->id)->get();
        $invoices->each(function ($invoice) {
            $invoice->active = false;
            $invoice->save();
        });
        $invoice = Invoice::find($request->invoice_id);
        $invoice->active = true;
        $invoice->save();

        $total = 0;
        foreach ($request->details as $detail) {
            error_log("detail: ".json_encode($detail));
            $total += $detail['total'];
            Detail::create([
                'quantity' => $detail['cantidad'],
                'product' => $detail['nombre'],
                'price' => $detail['precio'],
                'subtotal' => $detail['total'],
                'notes' => isset($detail['notas']) ? $detail['notas'] : null,
                'user_id' => $request->user()->id,
                'producto_id' => $detail['id'],
                'sale_id' => $sale->id,
            ]);
        }
        $sale->total = $total;
        $sale->save();
        return $sale;
    }
    public function show(Sale $sale){ return $sale; }
    public function update(UpdateSaleRequest $request, Sale $sale){ $sale->update($request->validated()); return $sale; }
    public function destroy(Sale $sale){ $sale->delete(); return $sale; }

}
