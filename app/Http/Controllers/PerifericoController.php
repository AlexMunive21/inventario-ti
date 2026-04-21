<?php

namespace App\Http\Controllers;

use App\Models\Periferico;
use App\Models\Area;
use Illuminate\Http\Request;

class PerifericoController extends Controller
{
    public function index()
    {
        $perifericos = Periferico::with('area')->get();

        return view('perifericos.index', compact('perifericos'));
    }

    public function create()
    {
        $areas = Area::where('activo', 1)->get();

        return view('perifericos.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'                => 'required|in:teclado,mouse',
            'marca'               => 'required',
            'modelo'              => 'required',
            'area_id'             => 'required|exists:areas,id',
            'cantidad_total'      => 'required|integer|min:1',
            'cantidad_disponible' => 'required|integer|min:0',
        ]);

        Periferico::create($request->all());

        return redirect()->route('perifericos.index')
            ->with('success', 'Periférico registrado correctamente.');
    }

    public function edit(Periferico $periferico)
    {
        $areas = Area::where('activo', 1)->get();

        return view('perifericos.edit', compact('periferico', 'areas'));
    }

    public function update(Request $request, Periferico $periferico)
    {
        $request->validate([
            'marca'               => 'required',
            'modelo'              => 'required',
            'area_id'             => 'required|exists:areas,id',
            'cantidad_total'      => 'required|integer|min:1',
            'cantidad_disponible' => 'required|integer|min:0',
        ]);

        $periferico->update($request->all());

        return redirect()->route('perifericos.index')
            ->with('success', 'Periférico actualizado correctamente.');
    }
}