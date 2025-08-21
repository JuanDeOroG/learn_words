<?php

namespace App\Http\Controllers\Study;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Study\Services\StudyConfigurationService;
use App\Http\Requests\Study\StudyConfigRequest;
use App\Models\Word;

class StudyController extends Controller
{
    /**
     * Recibe la configuración del usuario y prepara la sesión de estudio.
     */
    public function study(StudyConfigRequest $request)
    {
        // obtener todas las configuraciones enviadas por el usuario
        $config = $request->validated();

        // llamar al servicio encargado de procesar la configuración y preparar las palabras
        $studyData = (new StudyConfigurationService())->prepare($config);

        // retornar la vista de estudio con los datos generados
        return view('study.session', [
            'words' => $studyData['words'],
            'mode' => $studyData['mode'],
            'goal' => $studyData['goal'],
            'order' => $studyData['order'],
            'collections' => $studyData['collections'],
            // puedes agregar más datos según lo que devuelva el servicio
        ]);
    }

    /**
     * Marca una palabra como estudiada.
     */
    public function incrementStudyCount(Request $request)
    {
        $request->validate([
            'word_id' => 'required|exists:words,id',
        ]);

        $word = Word::find($request->word_id);
        $word->incrementStudyCount();


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Word marked as studied.']);
        }

        return redirect()->back()->with('success', 'Word marked as studied.');
    }
}
