<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\ProductoController as V1ProductoController;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends V1ProductoController
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->q) {
            $query->whereFullText(['nombre', 'descripcion'], $request->q);
        }

        if ($request->categoria_id) {
            $query->where('categoria_id', $request->categoria_id);
        }

        return response()->json([
            'data' => $query->paginate(20)
        ]);
    }
}
