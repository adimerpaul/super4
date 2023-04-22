<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Http\Request;

class InvoiceController extends Controller{
    public function index(Request $request){ return Invoice::where('user_id', $request->user()->id)->get(); }
    public function store(StoreInvoiceRequest $request){
        $invoices = Invoice::where('user_id', $request->user()->id)->get();
        $invoices->each(function ($invoice) {
            $invoice->active = false;
            $invoice->save();
        });
        $request->merge(['user_id' => $request->user()->id]);
        $request->merge(['active' => true]);
        return Invoice::create($request->all());
    }
    public function show(Invoice $invoice){ return $invoice; }
    public function update(UpdateInvoiceRequest $request, Invoice $invoice){ return $invoice->update($request->all()); }
    public function destroy(Invoice $invoice){ return $invoice->delete(); }
}
