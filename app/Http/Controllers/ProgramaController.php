<?php
namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programas = Programa::orderBy('sigla')->get(); // Ordenar por sigla para mejor visualización
        return Inertia::render('Programas/Index', [
            'programas' => $programas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Programas/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sigla' => 'required|string|max:20|unique:programas,sigla', // max:20 de tu esquema
            'tipo' => 'required|string|max:20|in:maestria,diplomado,doctorado', // max:20 y restricción CHECK
            'titulo' => 'required|string|max:255',
        ]);

        Programa::create($request->all());

        return redirect()->route('programas.index')
                         ->with('success', 'Programa creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Programa $programa)
    {
        // Inertia no tiene un componente 'show' por defecto,
        // pero podrías crear uno para ver detalles de un programa específico
        return Inertia::render('Programas/Show', [
            'programa' => $programa->load('modulos') // Carga también sus módulos si los quieres mostrar
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Programa $programa)
    {
        return Inertia::render('Programas/Edit', [
            'programa' => $programa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Programa $programa)
    {
        $request->validate([
            'sigla' => 'required|string|max:20|unique:programas,sigla,' . $programa->id, // Excluye el propio ID para updates
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
            $programa->delete();
            return redirect()->route('programas.index')
                             ->with('success', 'Programa eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Manejo de errores si hay claves foráneas (aunque onDelete('cascade') lo evita normalmente)
            return redirect()->route('programas.index')
                             ->with('error', 'No se pudo eliminar el programa. Puede que esté asociado a módulos.');
        }
    }
}