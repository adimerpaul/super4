<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

class ProductoController extends Controller{
    public function index(){ return Producto::with('rubro')->get(); }
    public function show(Producto $producto){ return $producto; }
    public function store(StoreProductoRequest $request){ return Producto::create($request->all()); }
    public function update(UpdateProductoRequest $request, Producto $producto){ $producto->update($request->all()); return $producto; }
    public function destroy(Producto $producto){ $producto->delete(); return response()->noContent(); }
}
