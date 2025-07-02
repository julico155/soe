<?php

namespace App\Http\Controllers;

use App\Models\Modulo; // Importa el modelo Modulo
use App\Models\Programa; // Importa el modelo Programa
use App\Models\User; // Importa el modelo User
use App\Enums\UserType; // Asegúrate de importar tu Enum UserType
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Visit; // ¡Importa el modelo Visit!

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Obtiene el usuario autenticado

        // Lógica para el contador de visitas de la página del Dashboard
        // Usamos un ID arbitrario (ej. 100) y un tipo específico para la página del Dashboard.
        $visitableId = 100;
        $visitableType = 'App\Models\DashboardPage'; // Tipo único para la página del Dashboard
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        // Si el usuario es de tipo 'docente'
        if ($user && $user->tipo === UserType::Docente->value) {
            // Cargar los módulos a los que el docente está asignado
            // Asegúrate de que el modelo Modulo tenga una relación con User (docente)
            $modulosDocente = Modulo::where('docente_id', $user->id)
                                    ->with('programa') // Carga la relación con Programa para mostrar el nombre del programa
                                    ->get();

            return Inertia::render('DashboardDocente', [
                'modulos' => $modulosDocente, // Pasa los módulos al componente Vue
                'pageVisits' => $pageVisits, // Pasa el contador a la vista del docente también
            ]);
        }

        // --- Lógica para el dashboard administrativo/general ---

        // Estadísticas Generales
        $totalProgramas = Programa::count();
        $totalModulos = Modulo::count();
        $totalDocentes = User::where('tipo', UserType::Docente->value)->count();

        // Estadísticas de Progreso de Módulos (para el gráfico de torta)
        $modulosCompletados = Modulo::where('porcentaje_avance', 100)->count();
        $modulosPendientes = $totalModulos - $modulosCompletados;

        // Calcular el porcentaje de avance promedio de todos los módulos
        $averageModuleCompletion = $totalModulos > 0
            ? round(Modulo::avg('porcentaje_avance'), 2)
            : 0;

        // --- Datos para la Tabla de Docentes ---
        $docentes = User::where('tipo', UserType::Docente->value)
                        ->with('modulos') // Carga ansiosamente los módulos asignados a cada docente
                        ->get();

        $docenteStats = $docentes->map(function ($docente) {
            $averageDocenteCompletion = 0;
            if ($docente->modulos->count() > 0) {
                // Calcula el promedio de porcentaje_avance para los módulos de este docente
                $averageDocenteCompletion = round($docente->modulos->avg('porcentaje_avance'), 2);
            }
            return [
                'id' => $docente->id,
                'name' => $docente->name,
                'email' => $docente->email,
                'average_completion' => $averageDocenteCompletion,
                'modules_count' => $docente->modulos->count(),
            ];
        })->sortByDesc('average_completion')->values()->all(); // Ordena por promedio de avance descendente y reindexa

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalProgramas' => $totalProgramas,
                'totalModulos' => $totalModulos,
                'totalDocentes' => $totalDocentes,
                'modulosCompletados' => $modulosCompletados,
                'modulosPendientes' => $modulosPendientes,
                'porcentajeAvancePromedio' => $averageModuleCompletion,
            ],
            'docenteStats' => $docenteStats, // Pasa los datos de los docentes
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }
}
