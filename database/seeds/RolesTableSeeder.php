<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
        	'name' => 'provider',
        	'slug' => 'provider',
        	'description' => 'Vende al por mayor',
        ]);
        
        Role::create([
        	'name' => 'commerce',
        	'slug' => 'commerce',
        	'description' => 'Vende al por menor',
        ]);
    }
}
