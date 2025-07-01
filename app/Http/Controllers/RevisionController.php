<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Revision;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RevisionController extends Controller
{
    /**
     * Show the form for creating a new Revision for a specific Modulo.
     *
     * @param  \App\Models\Modulo  $modulo
     * @return \Inertia\Response
     */
    public function create(Modulo $modulo)
    {
        // Opcional: Podrías querer pasar la última revisión para mostrar su puntuación
        // o información relevante si el usuario está creando una "segunda" revisión.
        // Pero para el formulario de creación, solo necesitamos el ID del módulo.
        return Inertia::render('Revisiones/Create', [
            'modulo' => [
                'id' => $modulo->id,
                'nombre' => $modulo->nombre,
                'sigla' => $modulo->sigla,
            ],
        ]);
    }

    /**
     * Store a newly created Revision in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Modulo $modulo)
    {
        $validatedData = $request->validate([
            'p_score' => 'required|integer|min:0|max:4',
            'o_score' => 'required|integer|min:0|max:4',
            's1_score' => 'required|integer|min:0|max:2',
            'observacion' => 'nullable|string|max:1000',
        ]);

        $totalScore = $validatedData['p_score'] + $validatedData['o_score'] + $validatedData['s1_score'];

        $modulo->revisiones()->create([
            'p_score' => $validatedData['p_score'],
            'o_score' => $validatedData['o_score'],
            's1_score' => $validatedData['s1_score'],
            'total_score' => $totalScore,
            'observacion' => $validatedData['observacion'],
        ]);

        // --- ¡NUEVA LÓGICA AQUÍ! ---
        // Calcula el nuevo porcentaje de avance del módulo basándose en la última revisión
        $newPercentage = ($totalScore / 10) * 100;

        // Actualiza el campo 'porcentaje_avance' del módulo
        $modulo->update(['porcentaje_avance' => $newPercentage]);
        // --- FIN DE NUEVA LÓGICA ---

        $message = 'Revisión añadida.';
        if ($newPercentage >= 100) { // Usamos newPercentage en lugar de totalScore directamente
            $message .= ' Módulo completado al 100%.';
        } else {
            $message .= ' El módulo aún no alcanza el 100% de avance.';
        }

        return redirect()->route('modulos.show', $modulo->id)
                         ->with('success', $message);
    }


        public function justify(Request $request, Revision $revision)
    {
        // 1. Autorización: Asegúrate de que solo el docente asignado al módulo de esta revisión pueda justificarla
        // y que el usuario logueado sea un docente.
        if ($request->user()->id !== $revision->modulo->docente_id || $request->user()->tipo !== 'docente') {
            abort(403, 'No estás autorizado para justificar esta revisión.');
        }

        // 2. Validación del mensaje de justificación
        $request->validate([
            'message' => ['required', 'string', 'max:1000'], // Mensaje requerido, string, máximo 1000 caracteres
        ]);

        // 3. Verificar si ya existe una justificación (opcional, si solo permites una)
        if ($revision->justificacion) {
            return redirect()->back()->with('error', 'Esta revisión ya tiene una justificación.');
        }

        // 4. Guardar la justificación en la revisión
        $revision->justificacion = $request->input('message');
        $revision->save();

        // 5. Redirigir de vuelta a la página del módulo con un mensaje de éxito
        return redirect()->route('modulos.show', $revision->modulo_id)->with('success', 'Justificación enviada correctamente.');
    }

}