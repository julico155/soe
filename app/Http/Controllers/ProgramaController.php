<?php
namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Visit; // Importa el modelo Visit

class ProgramaController extends Controller
{
    /**
     * Display a listing of the resource and record the visit.
     */
    public function index()
    {
        $programas = Programa::orderBy('sigla')->get(); // Ordenar por sigla para mejor visualización

        // Lógica para el contador de visitas de la página de índice de Programas
        $visitableId = 1; // ID arbitrario para la página de índice de Programas
        $visitableType = 'App\Models\Programa_IndexPage'; // Tipo único para esta página
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Programas/Index', [
            'programas' => $programas,
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
        // Lógica para el contador de visitas de la página de creación de Programas
        $visitableId = 2; // ID arbitrario para la página de creación de Programas
        $visitableType = 'App\Models\Programa_CreatePage'; // Tipo único para esta página
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Programas/Create', [
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sigla' => 'required|string|max:20|unique:programas,sigla',
            'tipo' => 'required|string|max:20|in:maestria,diplomado,doctorado',
            'titulo' => 'required|string|max:255',
        ]);

        Programa::create($request->all());

        return redirect()->route('programas.index')
                         ->with('success', 'Programa creado exitosamente.');
    }

    /**
     * Display the specified resource and record the visit.
     */
    public function show(Programa $programa)
    {
        // Lógica para el contador de visitas de la página de detalle de un Programa específico
        // Aquí usamos el ID del programa como visitable_id para contar visitas por programa individual
        $visitableId = $programa->id;
        $visitableType = 'App\Models\Programa'; // Usa el nombre del modelo directamente
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Programas/Show', [
            'programa' => $programa->load('modulos'), // Carga también sus módulos si los quieres mostrar
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Show the form for editing the specified resource and record the visit.
     */
    public function edit(Programa $programa)
    {
        // Lógica para el contador de visitas de la página de edición de un Programa específico
        // Usamos el ID del programa como visitable_id
        $visitableId = $programa->id;
        $visitableType = 'App\Models\Programa_EditPage'; // Tipo único para la página de edición de un programa
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Programas/Edit', [
            'programa' => $programa,
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Programa $programa)
    {
        $request->validate([
            'sigla' => 'required|string|max:20|unique:programas,sigla,' . $programa->id,
            'tipo' => 'required|string|max:20|in:maestria,diplomado,doctorado',
            'titulo' => 'required|string|max:255',
        ]);

        $programa->update($request->all());

        return redirect()->route('programas.index')
                         ->with('success', 'Programa actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programa $programa)
    {
        try {
            // Antes de eliminar el programa, verifica si tiene módulos asociados
            if ($programa->modulos()->count() > 0) {
                return redirect()->back()->with('error', 'No se puede eliminar el programa porque tiene módulos asociados.');
            }

            $programa->delete();
            return redirect()->route('programas.index')
                             ->with('success', 'Programa eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('programas.index')
                             ->with('error', 'No se pudo eliminar el programa. Ocurrió un error en la base de datos.');
        }
    }
}
