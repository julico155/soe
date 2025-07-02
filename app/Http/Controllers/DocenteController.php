<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Models\Visit; // ¡Importa el modelo Visit!

class DocenteController extends Controller
{
    /**
     * Display a listing of the Docentes and record the visit.
     */
    public function index()
    {
        // Obtener solo los usuarios con tipo 'docente'
        $docentes = User::where('tipo', 'docente')->get();

        // Lógica para el contador de visitas de la página de índice de Docentes
        $visitableId = 1; // ID arbitrario para la página de índice de Docentes
        $visitableType = 'App\Models\Docente_IndexPage'; // Tipo único para esta página
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Docentes/Index', [
            'docentes' => $docentes,
            'success' => session('success'),
            'error' => session('error'),
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Show the form for creating a new Docente and record the visit.
     */
    public function create()
    {
        // Lógica para el contador de visitas de la página de creación de Docentes
        $visitableId = 2; // ID arbitrario para la página de creación de Docentes
        $visitableType = 'App\Models\Docente_CreatePage'; // Tipo único para esta página
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Docentes/Create', [
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Store a newly created Docente in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'tipo' => 'required|string|in:administrativo,docente',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('docentes.index')
                         ->with('success', 'Docente creado exitosamente.');
    }

    /**
     * Display the specified resource and record the visit.
     */
    public function show(User $docente)
    {
        // Asegúrate de que el usuario es un docente antes de mostrar
        if ($docente->tipo !== 'docente') {
            abort(404, 'El usuario no es un docente.');
        }

        $docente->load('modulos'); // Carga los módulos si tienes la relación definida en el modelo User

        // Lógica para el contador de visitas de la página de detalle de un Docente específico
        // Aquí usamos el ID del docente como visitable_id para contar visitas por docente individual
        $visitableId = $docente->id;
        $visitableType = 'App\Models\Docente'; // Usa el nombre del modelo directamente
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Docentes/Show', [
            'docente' => $docente,
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Show the form for editing the specified resource and record the visit.
     */
    public function edit(User $docente)
    {
        if ($docente->tipo !== 'docente') {
            abort(404, 'El usuario no es un docente.');
        }

        // Lógica para el contador de visitas de la página de edición de un Docente específico
        // Usamos el ID del docente como visitable_id
        $visitableId = $docente->id;
        $visitableType = 'App\Models\Docente_EditPage'; // Tipo único para la página de edición de un docente
        $visit = Visit::firstOrCreate(['visitable_id' => $visitableId, 'visitable_type' => $visitableType], ['count' => 0]);
        $visit->increment('count');
        $pageVisits = $visit->count;

        return Inertia::render('Docentes/Edit', [
            'docente' => $docente,
            'pageVisits' => $pageVisits, // Pasa el contador a la vista
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $docente)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $docente->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'tipo' => 'required|string|in:administrativo,docente',
        ]);

        // Si la contraseña se envía, validarla y actualizarla
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $docente->password = Hash::make($request->password);
        }

        $docente->update($request->only(['name', 'email', 'telefono', 'direccion', 'tipo'])); // Actualiza solo los campos que vienen del formulario

        return redirect()->route('docentes.index')
                         ->with('success', 'Docente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $docente)
    {
        if ($docente->tipo !== 'docente') {
            return redirect()->route('docentes.index')->with('error', 'Solo se pueden eliminar docentes.');
        }

        try {
            // Verifica si el docente tiene módulos asignados antes de eliminarlo
            if ($docente->modulos()->count() > 0) {
                return redirect()->back()->with('error', 'No se puede eliminar el docente porque tiene módulos asignados.');
            }

            $docente->delete();
            return redirect()->route('docentes.index')
                             ->with('success', 'Docente eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('docentes.index')
                             ->with('error', 'No se puede eliminar el docente debido a dependencias existentes (ej. módulos asignados).');
        }
    }
}
