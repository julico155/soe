<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class DocenteController extends Controller
{
    /**
     * Display a listing of the Docentes.
     */
    public function index()
    {
        // Obtener solo los usuarios con tipo 'docente'
        $docentes = User::where('tipo', 'docente')->get();

        return Inertia::render('Docentes/Index', [
            'docentes' => $docentes,
        ]);
    }

    /**
     * Show the form for creating a new Docente.
     */
    public function create()
    {
        return Inertia::render('Docentes/Create');
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
            'telefono' => 'nullable|string|max:20',    // <-- Validaciones para los nuevos campos
            'direccion' => 'nullable|string|max:255', // <-- Validaciones para los nuevos campos
            'tipo' => 'required|string|in:administrativo,docente', // <-- Validación para el campo 'tipo'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,   // <-- Guardar nuevos campos
            'direccion' => $request->direccion, // <-- Guardar nuevos campos
            'tipo' => $request->tipo,           // <-- Guardar el campo 'tipo'
        ]);

        return redirect()->route('docentes.index')
                         ->with('success', 'Docente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $docente)
    {
        // Asegúrate de que el usuario es un docente antes de mostrar
        if ($docente->tipo !== 'docente') {
            abort(404, 'El usuario no es un docente.');
        }

        $docente->load('modulos'); // Carga los módulos si tienes la relación definida en el modelo User

        return Inertia::render('Docentes/Show', [
            'docente' => $docente,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $docente)
    {
        if ($docente->tipo !== 'docente') {
            abort(404, 'El usuario no es un docente.');
        }

        return Inertia::render('Docentes/Edit', [
            'docente' => $docente,
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
            $docente->delete();
            return redirect()->route('docentes.index')
                             ->with('success', 'Docente eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('docentes.index')
                             ->with('error', 'No se puede eliminar el docente debido a dependencias existentes (ej. módulos asignados).');
        }
    }
}