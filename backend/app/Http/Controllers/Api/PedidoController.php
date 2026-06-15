<?php

namespace App\Http\Controllers\Api;

use App\Events\NuevoPedidoRecibido;
use App\Http\Controllers\Controller;
use App\Jobs\EnviarConfirmacionPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['user', 'items.producto'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json(['data' => $pedidos]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        $pedido = DB::transaction(function () use ($request) {
            $p = Pedido::create([
                'user_id' => auth()->id(),
                'total' => collect($request->items)->sum(fn($i) => $i['precio'] * $i['cantidad']),
            ]);

            foreach ($request->items as $item) {
                $p->items()->create([
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                ]);

                Producto::find($item['producto_id'])->decrement('stock', $item['cantidad']);
            }

            return $p;
        });

        EnviarConfirmacionPedido::dispatch($pedido)->delay(now()->addSeconds(5));

        broadcast(new NuevoPedidoRecibido($pedido))->toOthers();

        return response()->json(['pedido_id' => $pedido->id], 201);
    }

    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id() && !auth()->user()->esAdmin()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($pedido->load(['user', 'items.producto']));
    }
}
