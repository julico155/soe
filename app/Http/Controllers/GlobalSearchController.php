<?php

namespace App\Http\Controllers;

use App\Models\User;     // Importa el modelo User
use App\Models\Programa; // Importa el modelo Programa
use App\Models\Modulo;   // Importa el modelo Modulo
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Visit; // ¡Importa el modelo Visit!

class GlobalSearchController extends Controller
{
    /**
     * Maneja la solicitud de búsqueda global.
     * Busca en usuarios, programas y módulos y registra la visita.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Lógica para el contador de visitas de la página de Búsqueda Global
        // Usamos un ID arbitrario (ej. 200) y un tipo específico para esta página.
        $visitableId = 200;
        $visitableType = 'App\Models\GlobalSearchPage'; // Tipo único para la página de búsqueda global
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        // Obtiene la consulta de búsqueda del input 'query'
        $query = $request->input('query');
        $results = [
            'users' => [],    // Array para almacenar resultados de usuarios
            'programas' => [], // Array para almacenar resultados de programas
            'modulos' => [],   // Array para almacenar resultados de módulos
        ];

        // Solo realiza la búsqueda si hay una consulta
        if ($query) {
            // Búsqueda en Usuarios:
            // Busca por nombre o email que contengan la palabra clave.
            // Limita a 10 resultados para no sobrecargar la vista.
            $results['users'] = User::where('name', 'like', '%' . $query . '%')
                                    ->orWhere('email', 'like', '%' . $query . '%')
                                    ->limit(10)
                                    ->get();

            // Búsqueda en Programas:
            // Busca por título o sigla que contengan la palabra clave.
            $results['programas'] = Programa::where('titulo', 'like', '%' . $query . '%')
                                            ->orWhere('sigla', 'like', '%' . $query . '%')
                                            ->limit(10)
                                            ->get();

            // Búsqueda en Módulos:
            // Busca por nombre o sigla que contengan la palabra clave.
            // Asegúrate de que el modelo Modulo tenga la relación con Docente si necesitas mostrar su nombre.
            $results['modulos'] = Modulo::where('nombre', 'like', '%' . $query . '%')
                                        ->orWhere('sigla', 'like', '%' . $query . '%')
                                        ->limit(10)
                                        ->get();
        }

        // Renderiza el componente Vue 'SearchResults' y le pasa la consulta, los resultados y el contador de visitas
        return Inertia::render('SearchResults', [
            'query' => $query,
            'results' => $results,
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }
}
