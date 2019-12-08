<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	// Ventas
        Permission::create([
        	'name' => 'Vender',
        	'slug' => 'sale.create',
        	'description' => 'Podra vender cualquier artículo registrado en el sistema'
        ]);
        // Permission::create([
        // 	'name' => 'Eliminar ventas',
        // 	'slug' => 'sale.destroy',
        // 	'description' => 'Podra eliminar cualquier venta que este regitrada en el sistema'
        // ]);
        Permission::create([
        	'name' => 'Ver las ventas',
        	'slug' => 'sale.index',
        	'description' => 'Podra ver todas las ventas registradas en el sistema'
        ]);

        // Articles
        Permission::create([
        	'name' => 'Ingresar artículos',
        	'slug' => 'article.create',
        	'description' => 'Podra registrar cualquier cantidad de artículos'
        ]);
        // Permission::create([
        // 	'name' => 'Editar artículos',
        // 	'slug' => 'article.edit',
        // 	'description' => 'Podra editar cualquier artículo'
        // ]);
        // Permission::create([
        // 	'name' => 'Eliminar artículos',
        // 	'slug' => 'article.destroy',
        // 	'description' => 'Podra eliminar cualquier cantidad de artículos'
        // ]);
        Permission::create([
        	'name' => 'Ver los artículos',
        	'slug' => 'article.index',
        	'description' => 'Podra ver todos los artículos que hayan sido ingresados'
        ]);

        // Codigos de barra
        Permission::create([
        	'name' => 'Crear codigos de barra',
        	'slug' => 'bar_code.create',
        	'description' => 'Podra crear cualquier cantidad de codigos de barra'
        ]);
        
    }
}
