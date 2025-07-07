<?php
namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Programa; // Para los selects en el formulario
use App\Models\User;     // Para los selects de docentes
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Visit; // ¡Importa el modelo Visit!

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource and record the visit.
     */
    public function index()
    {
        // Carga los módulos con sus relaciones (programa y docente)
        // Selecciona solo id y name del docente para optimizar
        $modulos = Modulo::with(['programa:id,sigla,titulo', 'docente:id,name'])->orderBy('sigla')->get();

        // Lógica para el contador de visitas de la página de índice de Módulos
        $visitableId = 1; // ID arbitrario para la página de índice de Módulos
        $visitableType = 'App\Models\Modulo_IndexPage'; // Tipo único para esta página
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Modulos/Index', [
            'modulos' => $modulos,
            'success' => session('success'),
            'error' => session('error'),
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Show the form for creating a new resource and record the visit.
     */
    public function create()
    {
        // Necesitamos pasar los programas y docentes disponibles para los desplegables del formulario
        $programas = Programa::orderBy('titulo')->get(['id', 'titulo', 'sigla']);
        $docentes = User::where('tipo', 'docente')->orderBy('name')->get(['id', 'name']);

        // Lógica para el contador de visitas de la página de creación de Módulos
        $visitableId = 2; // ID arbitrario para la página de creación de Módulos
        $visitableType = 'App\Models\Modulo_CreatePage'; // Tipo único para esta página
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Modulos/Create', [
            'programas' => $programas,
            'docentes' => $docentes,
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sigla' => 'required|string|max:20|unique:modulos,sigla',
            'nombre' => 'required|string|max:255',
            'porcentaje_avance' => 'nullable|numeric|min:0|max:100',
            'programa_id' => 'required|exists:programas,id',
            'docente_id' => 'nullable|exists:users,id', // Puede ser null si ON DELETE SET NULL
        ]);

        Modulo::create($request->all());

        return redirect()->route('modulos.index')
                         ->with('success', 'Módulo creado exitosamente.');
    }

    /**
     * Display the specified resource and record the visit.
     */
    public function show(Modulo $modulo)
    {
        // Cargar las revisiones del módulo, ordenadas por la más reciente
        // Y cargar las imágenes de cada revisión
        $modulo->load(['revisiones' => function ($query) {
            $query->latest()->with('images'); // Cargar la última revisión y sus imágenes
        }, 'programa', 'docente']); // Cargar también programa y docente

        $ultimaRevision = $modulo->revisiones->first(); // La revisión más reciente

        // Lógica para el contador de visitas de la página de detalle del Módulo
        $visitableId = $modulo->id;
        $visitableType = 'App\Models\Modulo';
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Modulos/Show', [
            'modulo' => $modulo,
            'ultimaRevision' => $ultimaRevision,
            'pageVisits' => $pageVisits,
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }

    /**
     * Show the form for editing the specified resource and record the visit.
     */
    public function edit(Modulo $modulo)
    {
        // Necesitamos pasar el módulo, así como los programas y docentes para los desplegables
        $programas = Programa::orderBy('titulo')->get(['id', 'titulo', 'sigla']);
        $docentes = User::where('tipo', 'docente')->orderBy('name')->get(['id', 'name']);

        // Lógica para el contador de visitas de la página de edición de un Módulo específico
        // Usamos el ID del módulo como visitable_id
        $visitableId = $modulo->id;
        $visitableType = 'App\Models\Modulo_EditPage'; // Tipo único para la página de edición de un módulo
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Modulos/Edit', [
            'modulo' => $modulo,
            'programas' => $programas,
            'docentes' => $docentes,
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modulo $modulo)
    {
        $request->validate([
            'sigla' => 'required|string|max:20|unique:modulos,sigla,' . $modulo->id,
            'nombre' => 'required|string|max:255',
            'porcentaje_avance' => 'nullable|numeric|min:0|max:100',
            'programa_id' => 'required|exists:programas,id',
            'docente_id' => 'nullable|exists:users,id',
        ]);

        $modulo->update($request->all());

        return redirect()->route('modulos.index')
                         ->with('success', 'Módulo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modulo $modulo)
    {
        try {
            // Antes de eliminar el módulo, verifica si tiene revisiones asociadas
            if ($modulo->revisiones()->count() > 0) {
                return redirect()->back()->with('error', 'No se puede eliminar el módulo porque tiene revisiones asociadas.');
            }

            $modulo->delete();
            return redirect()->route('modulos.index')
                             ->with('success', 'Módulo eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('modulos.index')
                             ->with('error', 'No se pudo eliminar el módulo. Ocurrió un error en la base de datos.');
        }
    }
}
