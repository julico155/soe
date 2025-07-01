<?php
namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Programa; // Para los selects en el formulario
use App\Models\User;     // Para los selects de docentes
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carga los módulos con sus relaciones (programa y docente)
        // Selecciona solo id y name del docente para optimizar
        $modulos = Modulo::with(['programa:id,sigla,titulo', 'docente:id,name'])->orderBy('sigla')->get();
        return Inertia::render('Modulos/Index', [
            'modulos' => $modulos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Necesitamos pasar los programas y docentes disponibles para los desplegables del formulario
        $programas = Programa::orderBy('titulo')->get(['id', 'titulo', 'sigla']);
        $docentes = User::where('tipo', 'docente')->orderBy('name')->get(['id', 'name']);

        return Inertia::render('Modulos/Create', [
            'programas' => $programas,
            'docentes' => $docentes,
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
     * Display the specified resource.
     */
    public function show(Modulo $modulo)
    {
        // Cargar el programa, docente y todas las revisiones ordenadas por la más reciente
        $modulo->load(['programa', 'docente', 'revisiones' => function($query) {
            $query->latest(); // Ordena las revisiones por 'created_at' en orden descendente
        }]);

        // Opcional: Obtener la última revisión para pasarla directamente a la vista
        $ultimaRevision = $modulo->revisiones->first();

        return Inertia::render('Modulos/Show', [
            'modulo' => $modulo,
            'ultimaRevision' => $ultimaRevision, // Pasa la última revisión a la vista
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modulo $modulo)
    {
        // Necesitamos pasar el módulo, así como los programas y docentes para los desplegables
        $programas = Programa::orderBy('titulo')->get(['id', 'titulo', 'sigla']);
        $docentes = User::where('tipo', 'docente')->orderBy('name')->get(['id', 'name']);

        return Inertia::render('Modulos/Edit', [
            'modulo' => $modulo,
            'programas' => $programas,
            'docentes' => $docentes,
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
            $modulo->delete();
            return redirect()->route('modulos.index')
                             ->with('success', 'Módulo eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('modulos.index')
                             ->with('error', 'No se pudo eliminar el módulo. Puede que tenga revisiones asociadas.');
        }
    }
}