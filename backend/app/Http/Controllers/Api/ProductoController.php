<?php

namespace App\Http\Controllers\Api;

use App\Events\StockBajoAlerta;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductoController extends Controller
{
    #[OA\Get(
        path: '/api/v1/productos',
        tags: ['Productos'],
        summary: 'Listar productos',
        responses: [
            new OA\Response(response: 200, description: 'Lista de productos paginada'),
        ]
    )]
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->categoria_id) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->precio_min) {
            $query->where('precio', '>=', $request->precio_min);
        }

        if ($request->precio_max) {
            $query->where('precio', '<=', $request->precio_max);
        }

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->q}%")
                  ->orWhere('descripcion', 'like', "%{$request->q}%");
            });
        }

        $sortField = $request->sort ?? 'id';
        $sortDir = $request->order ?? 'asc';
        $query->orderBy($sortField, $sortDir);

        return response()->json([
            'data' => $query->paginate($request->per_page ?? 15)
        ]);
    }

    #[OA\Post(
        path: '/api/v1/productos',
        tags: ['Productos'],
        summary: 'Crear producto',
        responses: [
            new OA\Response(response: 201, description: 'Producto creado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $producto = Producto::create($validated);

        return response()->json($producto->load('categoria'), 201);
    }

    #[OA\Get(
        path: '/api/v1/productos/{id}',
        tags: ['Productos'],
        summary: 'Obtener producto',
        responses: [
            new OA\Response(response: 200, description: 'Producto encontrado'),
            new OA\Response(response: 404, description: 'No encontrado'),
        ]
    )]
    public function show(Producto $producto)
    {
        return response()->json($producto->load('categoria'));
    }

    #[OA\Put(
        path: '/api/v1/productos/{id}',
        tags: ['Productos'],
        summary: 'Actualizar producto',
        responses: [
            new OA\Response(response: 200, description: 'Producto actualizado'),
        ]
    )]
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'imagen' => 'nullable|string',
            'categoria_id' => 'sometimes|exists:categorias,id',
        ]);

        $producto->update($validated);

        if ($producto->stock <= 5) {
            broadcast(new StockBajoAlerta($producto, $producto->stock));
        }

        return response()->json($producto->load('categoria'));
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(null, 204);
    }
}
