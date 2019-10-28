<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lucas = User::create([
        	'name' => 'Lucas',
        	'company_name' => 'Lucas Distribuidora',
        	'password' => bcrypt('1234'),
        ]);
        $lucas->roles()->sync([1]);

        $marcos = User::create([
        	'name' => 'Marcos',
        	'company_name' => 'Lo de Marcos',
        	'password' => bcrypt('1234'),
        ]);
        $marcos->roles()->sync([2]);
    }
}
