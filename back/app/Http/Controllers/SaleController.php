<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;

class SaleController extends Controller{
    public function index(){ return Sale::whereDate('created_at', today())->get(); }
    public function store(StoreSaleRequest $request){
        $addresses = Sale::find($request->sale_id);

        $request->merge(['user_id' => $request->user()->id]);
        $request->merge(['date' => today()]);
        $request->merge(['time' => now()]);
        $request->merge(['status' => 'Pendiente']);
        $sale = Sale::create($request->validated());
        return $sale;
    }
    public function show(Sale $sale){ return $sale; }
    public function update(UpdateSaleRequest $request, Sale $sale){ $sale->update($request->validated()); return $sale; }
    public function destroy(Sale $sale){ $sale->delete(); return $sale; }

}
