<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'Cocina',
        ]);
        Department::create([
            'name' => 'Almacén',
        ]);
        Department::create([
            'name' => 'Recursos Humanos',
        ]);
        Department::create([
            'name' => 'Comité',
        ]);
        Department::create([
            'name' => 'Sistemas',
        ]);
        Department::create([
            'name' => 'Contabilidad',
        ]);
        Department::create([
            'name' => 'Tesorería',
        ]);
        Department::create([
            'name' => 'Compras',
        ]);
        Department::create([
            'name' => 'Banquetes',
        ]);
        Department::create([
            'name' => 'Reservaciones',
        ]);
        Department::create([
            'name' => 'Marketing',
        ]);
        Department::create([
            'name' => 'Seguridad',
        ]);
        Department::create([
            'name' => 'Laboratorio',
        ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
        // Department::create([
        //     'name' => 'Sistemas',
        // ]);
    }
}
