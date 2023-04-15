<?php

namespace App\Http\Controllers;

use App\Models\Rubro;
use App\Http\Requests\StoreRubroRequest;
use App\Http\Requests\UpdateRubroRequest;

class RubroController extends Controller{
    public function index(){ return Rubro::all(); }
    public function store(StoreRubroRequest $request){ return Rubro::create($request->validated()); }
    public function show(Rubro $rubro){ return $rubro; }
    public function update(UpdateRubroRequest $request, Rubro $rubro){ return $rubro->update($request->validated()); }
    public function destroy(Rubro $rubro){ return $rubro->delete(); }
}
