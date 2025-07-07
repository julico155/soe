<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de importar tu modelo User
use App\Enums\UserType; // Asegúrate de importar tu Enum UserType
use Illuminate\Support\Facades\Hash; // Para hashear las contraseñas

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario Administrativo (Supervisor)
        User::firstOrCreate(
            ['email' => 'supervisor@example.com'], // Busca por email para evitar duplicados
            [
                'name' => 'Supervisor',
                'password' => Hash::make('password'), // Puedes cambiar la contraseña
                'telefono' => '123456789',
                'direccion' => 'Calle Falsa 123, Oficina Central',
                'tipo' => UserType::Administrativo->value, // Usar el valor del Enum
            ]
        );

        $this->command->info('Usuario Supervisor creado/actualizado.');

        // Crear usuario Docente de Ejemplo
        User::firstOrCreate(
            ['email' => 'docente@example.com'], // Busca por email para evitar duplicados
            [
                'name' => 'Docente Ejemplo',
                'password' => Hash::make('password'), // Puedes cambiar la contraseña
                'telefono' => '987654321',
                'direccion' => 'Av. Siempre Viva 742, Aula 5',
                'tipo' => UserType::Docente->value, // Usar el valor del Enum
            ]
        );

        $this->command->info('Usuario Docente de Ejemplo creado/actualizado.');
    }
}

