<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        User::factory()->create([
            'name' => 'Usuario',
            'email' => 'user@test.com',
            'password' => bcrypt('password123'),
        ]);

        $cat1 = Categoria::create(['nombre' => 'Electrónicos', 'descripcion' => 'Dispositivos electrónicos y accesorios']);
        $cat2 = Categoria::create(['nombre' => 'Ropa', 'descripcion' => 'Prendas de vestir y moda']);
        $cat3 = Categoria::create(['nombre' => 'Hogar', 'descripcion' => 'Artículos para el hogar']);

        Producto::create(['nombre' => 'Laptop Pro', 'descripcion' => 'Laptop de alto rendimiento', 'precio' => 1500.00, 'stock' => 10, 'categoria_id' => $cat1->id]);
        Producto::create(['nombre' => 'Mouse Inalámbrico', 'descripcion' => 'Mouse ergonómico', 'precio' => 25.00, 'stock' => 50, 'categoria_id' => $cat1->id]);
        Producto::create(['nombre' => 'Teclado Mecánico', 'descripcion' => 'Teclado RGB', 'precio' => 80.00, 'stock' => 3, 'categoria_id' => $cat1->id]);
        Producto::create(['nombre' => 'Camiseta Algodón', 'descripcion' => 'Camiseta 100% algodón', 'precio' => 20.00, 'stock' => 100, 'categoria_id' => $cat2->id]);
        Producto::create(['nombre' => 'Lámpara LED', 'descripcion' => 'Lámpara de escritorio', 'precio' => 45.00, 'stock' => 25, 'categoria_id' => $cat3->id]);
    }
}
