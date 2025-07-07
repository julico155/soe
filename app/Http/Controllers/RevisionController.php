<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use App\Models\Modulo; // Asegúrate de importar el modelo Modulo
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RevisionImage;

class RevisionController extends Controller
{
    /**
     * Display a listing of the revisions.
     */
    public function index()
    {
        // Cargar todas las revisiones, incluyendo el módulo, el programa del módulo y las imágenes de la revisión.
        // Ordenar por la fecha de creación de la revisión más reciente primero.
        $revisions = Revision::with(['modulo.programa', 'images'])
                             ->latest() // Ordenar por la revisión más reciente
                             ->get();

        return Inertia::render('Revision/Index', [
            'revisions' => $revisions,
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }

    /**
     * Show the form for creating a new revision for a specific modulo.
     */
    public function create(Modulo $modulo) // Recibe el módulo como parámetro
    {
        return Inertia::render('Revision/Create', [ // La vista está en Modulos/Revision
            'modulo' => $modulo,
            // Si necesitas pasar otros datos, como estudiantes, hazlo aquí
        ]);
    }

    /**
     * Store a newly created revision in storage for a specific modulo.
     */
    public function store(Request $request, Modulo $modulo)
    {
        $validatedData = $request->validate([
            'criterio1_1_cumplido' => 'boolean',
            'criterio1_2_cumplido' => 'boolean',
            'criterio1_3_cumplido' => 'boolean',
            'criterio1_4_cumplido' => 'boolean',
            'criterio2_1_cumplido' => 'boolean',
            'criterio2_2_cumplido' => 'boolean',
            'criterio2_3_cumplido' => 'boolean',
            'criterio2_4_cumplido' => 'boolean',
            'criterio3_1_cumplido' => 'boolean',
            'criterio3_2_cumplido' => 'boolean',
            'observacion' => 'nullable|string|max:1000',
            'justificacion' => 'nullable|string|max:1000',
            // Validación para las imágenes: un array de cadenas Base64, cada una max 5MB
            'images.*' => 'nullable|string|max:5242880', // 5MB en caracteres (aprox)
            'images' => 'array', // Asegura que 'images' es un array
        ]);

        $validatedData['criterio1_1_cumplido'] = $request->boolean('criterio1_1_cumplido');
        $validatedData['criterio1_2_cumplido'] = $request->boolean('criterio1_2_cumplido');
        $validatedData['criterio1_3_cumplido'] = $request->boolean('criterio1_3_cumplido');
        $validatedData['criterio1_4_cumplido'] = $request->boolean('criterio1_4_cumplido');
        $validatedData['criterio2_1_cumplido'] = $request->boolean('criterio2_1_cumplido');
        $validatedData['criterio2_2_cumplido'] = $request->boolean('criterio2_2_cumplido');
        $validatedData['criterio2_3_cumplido'] = $request->boolean('criterio2_3_cumplido');
        $validatedData['criterio2_4_cumplido'] = $request->boolean('criterio2_4_cumplido');
        $validatedData['criterio3_1_cumplido'] = $request->boolean('criterio3_1_cumplido');
        $validatedData['criterio3_2_cumplido'] = $request->boolean('criterio3_2_cumplido');

        $validatedData['modulo_id'] = $modulo->id;

        // Crear la revisión
        $revision = Revision::create($validatedData);

        // Guardar las imágenes si existen
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $imageData) {
                // Extraer el tipo MIME y los datos Base64
                // El formato esperado es "data:image/png;base64,..."
                if (preg_match('/^data:(.*?);base64,(.*)$/', $imageData, $matches)) {
                    $mimeType = $matches[1];
                    $base64Data = $matches[2];

                    RevisionImage::create([
                        'revision_id' => $revision->id,
                        'image_base64' => $base64Data,
                        'mime_type' => $mimeType,
                        // 'filename' => 'image_' . uniqid() . '.' . explode('/', $mimeType)[1], // Puedes generar un nombre de archivo único
                    ]);
                }
            }
        }

        $this->updateModuloPorcentajeAvance($modulo);

        return redirect()->route('modulos.show', $modulo->id)
                         ->with('success', 'Revisión añadida exitosamente.');
    }

    /**
     * Display the specified revision.
     */
    public function show(Revision $revision)
    {
        $revision->load(['modulo', 'images']); // Cargar también las imágenes

        $totalScore = 0;
        foreach (range(1, 3) as $cat) {
            $maxCriterios = ($cat == 1 || $cat == 2) ? 4 : 2;
            for ($i = 1; $i <= $maxCriterios; $i++) {
                $criterioName = "criterio{$cat}_{$i}_cumplido";
                if ($revision->$criterioName) {
                    $totalScore += 1;
                }
            }
        }

        return Inertia::render('Revision/Show', [
            'revision' => $revision,
            'totalScore' => $totalScore,
        ]);
    }


    /**
     * Show the form for editing the specified revision.
     */
    public function edit(Revision $revision)
    {
        // Asegúrate de cargar la relación 'modulo' y 'images' aquí
        $revision->load(['modulo', 'images']);

        return Inertia::render('Revision/Edit', [
            'revision' => $revision,
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }

    /**
     * Update the specified revision in storage.
     */
    public function update(Request $request, Revision $revision)
    {
        $validatedData = $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'criterio1_1_cumplido' => 'boolean',
            'criterio1_2_cumplido' => 'boolean',
            'criterio1_3_cumplido' => 'boolean',
            'criterio1_4_cumplido' => 'boolean',
            'criterio2_1_cumplido' => 'boolean',
            'criterio2_2_cumplido' => 'boolean',
            'criterio2_3_cumplido' => 'boolean',
            'criterio2_4_cumplido' => 'boolean',
            'criterio3_1_cumplido' => 'boolean',
            'criterio3_2_cumplido' => 'boolean',
            'observacion' => 'nullable|string|max:1000',
            'justificacion' => 'nullable|string|max:1000',
            // Validación para nuevas imágenes
            'new_images.*' => 'nullable|string|max:5242880',
            'new_images' => 'array',
            // Para manejar la eliminación de imágenes existentes (IDs de imágenes a eliminar)
            'removed_image_ids' => 'array',
            'removed_image_ids.*' => 'integer|exists:revision_images,id',
        ]);

        $validatedData['criterio1_1_cumplido'] = $request->boolean('criterio1_1_cumplido');
        $validatedData['criterio1_2_cumplido'] = $request->boolean('criterio1_2_cumplido');
        $validatedData['criterio1_3_cumplido'] = $request->boolean('criterio1_3_cumplido');
        $validatedData['criterio1_4_cumplido'] = $request->boolean('criterio1_4_cumplido');
        $validatedData['criterio2_1_cumplido'] = $request->boolean('criterio2_1_cumplido');
        $validatedData['criterio2_2_cumplido'] = $request->boolean('criterio2_2_cumplido');
        $validatedData['criterio2_3_cumplido'] = $request->boolean('criterio2_3_cumplido');
        $validatedData['criterio2_4_cumplido'] = $request->boolean('criterio2_4_cumplido');
        $validatedData['criterio3_1_cumplido'] = $request->boolean('criterio3_1_cumplido');
        $validatedData['criterio3_2_cumplido'] = $request->boolean('criterio3_2_cumplido');

        $revision->update($validatedData);

        // Eliminar imágenes existentes
        if ($request->has('removed_image_ids') && is_array($request->removed_image_ids)) {
            RevisionImage::whereIn('id', $request->removed_image_ids)
                         ->where('revision_id', $revision->id)
                         ->delete();
        }

        // Guardar nuevas imágenes
        if ($request->has('new_images') && is_array($request->new_images)) {
            foreach ($request->new_images as $imageData) {
                if (preg_match('/^data:(.*?);base64,(.*)$/', $imageData, $matches)) {
                    $mimeType = $matches[1];
                    $base64Data = $matches[2];

                    RevisionImage::create([
                        'revision_id' => $revision->id,
                        'image_base64' => $base64Data,
                        'mime_type' => $mimeType,
                    ]);
                }
            }
        }

        $this->updateModuloPorcentajeAvance($revision->modulo);

        return redirect()->route('modulos.show', $revision->modulo_id)
                         ->with('success', 'Revisión actualizada exitosamente.');
    }

    /**
     * Handle justification submission for a revision.
     */
    public function justify(Request $request, Revision $revision)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $revision->update(['justificacion' => $request->message]);

        return redirect()->back()->with('success', 'Justificación enviada exitosamente.');
    }

    /**
     * Remove the specified revision from storage.
     */
    public function destroy(Revision $revision)
    {
        $moduloId = $revision->modulo_id; // Guarda el ID del módulo antes de eliminar la revisión
        $revision->delete();

        return redirect()->route('modulos.show', $moduloId) // Redirige de vuelta al módulo
                         ->with('success', 'Revisión eliminada exitosamente.');
    }



     protected function updateModuloPorcentajeAvance(Modulo $modulo)
    {
        $ultimaRevision = $modulo->revisiones()->latest()->first();

        $porcentajeAvance = 0;
        if ($ultimaRevision) {
            $totalScoreUltimaRevision = 0;
            if ($ultimaRevision->criterio1_1_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio1_2_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio1_3_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio1_4_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio2_1_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio2_2_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio2_3_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio2_4_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio3_1_cumplido) $totalScoreUltimaRevision += 1;
            if ($ultimaRevision->criterio3_2_cumplido) $totalScoreUltimaRevision += 1;

            $porcentajeAvance = ($totalScoreUltimaRevision / 10) * 100;
        }

        // Actualizar el campo porcentaje_avance en el módulo
        $modulo->update(['porcentaje_avance' => round($porcentajeAvance, 2)]);
    }
}
