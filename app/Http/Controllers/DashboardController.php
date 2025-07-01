<?php

namespace App\Http\Controllers;

use App\Models\Modulo; // Importa el modelo Modulo
use App\Models\Programa; // Importa el modelo Programa
use App\Models\User; // Importa el modelo User
use App\Enums\UserType; // Asegúrate de importar tu Enum UserType
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Obtiene el usuario autenticado

        // Si el usuario es de tipo 'docente'
        if ($user && $user->tipo === UserType::Docente->value) {
            // Cargar los módulos a los que el docente está asignado
            // Asegúrate de que el modelo Modulo tenga una relación con User (docente)
            $modulosDocente = Modulo::where('docente_id', $user->id)
                                    ->with('programa') // Carga la relación con Programa para mostrar el nombre del programa
                                    ->get();

            return Inertia::render('DashboardDocente', [
                'modulos' => $modulosDocente, // Pasa los módulos al componente Vue
            ]);
        }

        // Si el usuario es de tipo 'administrativo' o cualquier otro,
        // o si no hay un usuario logueado (aunque esta ruta debería estar protegida por middleware 'auth')
        // se mantiene la lógica del dashboard general.

        // Estadísticas Generales (para el dashboard administrativo/general)
        $totalProgramas = Programa::count();
        $totalModulos = Modulo::count();
        $totalDocentes = User::where('tipo', UserType::Docente->value)->count();

        // Estadísticas de Progreso de Módulos
        $modulosCompletados = Modulo::where('porcentaje_avance', 100)->count();
        $modulosPendientes = $totalModulos - $modulosCompletados;

        // Calcular el porcentaje de avance promedio de todos los módulos
        $averageModuleCompletion = $totalModulos > 0
            ? round(Modulo::avg('porcentaje_avance'), 2)
            : 0;

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalProgramas' => $totalProgramas,
                'totalModulos' => $totalModulos,
                'totalDocentes' => $totalDocentes,
                'modulosCompletados' => $modulosCompletados,
                'modulosPendientes' => $modulosPendientes,
                'porcentajeAvancePromedio' => $averageModuleCompletion,
            ],
        ]);
    }
}
