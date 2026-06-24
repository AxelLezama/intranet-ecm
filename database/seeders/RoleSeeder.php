<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'description' => 'Control Total'
        ]);
        Role::create([
            'name' => 'comittee',
            'description' => 'Gestión documental de dirección'
        ]);
        Role::create([
            'name' => 'supervisor',
            'description' => 'Gestión documental de empleados'
        ]);
        Role::create([
            'name' => 'employee',
            'description' => 'Visualizar y descargar documentos creados por el supervisor'
        ]);
    }
}
