<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Cache::remember('categorias.todas', 3600, function () {
            return Categoria::with('productos')->get()->toArray();
        });

        return response()->json(['data' => $categorias]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = Categoria::create($validated);
        Cache::forget('categorias.todas');

        return response()->json($categoria, 201);
    }

    public function show(Categoria $categoria)
    {
        return response()->json($categoria->load('productos'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $categoria->update($validated);
        Cache::forget('categorias.todas');

        return response()->json($categoria);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        Cache::forget('categorias.todas');

        return response()->json(null, 204);
    }
}
