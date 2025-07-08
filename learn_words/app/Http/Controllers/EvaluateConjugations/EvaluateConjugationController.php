<?php
namespace App\Http\Controllers\EvaluateConjugations;

use App\Http\Controllers\EvaluateConjugations\Services\GenerateFillExerciseService;
use App\Http\Controllers\EvaluateConjugations\Services\CheckFillAnswersService;
use App\Http\Requests\EvaluateConjugations\EvaluateFillRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class EvaluateConjugationController extends Controller
{
    public function fill(EvaluateFillRequest $request)
    {
        // Obtener preferencias del usuario desde el request (o usar valores por defecto)
        $columns = $request->input('columns', 5);
        $toFill = $request->input('to_fill', 2);
        $wordsCount = $request->input('words_count', 1);

        // Lógica principal delegada al servicio
        $exercise = (new GenerateFillExerciseService)->generateFill($columns, $toFill, $wordsCount);

        // Renderizar la vista con los datos generados
        return view('evaluateConjugations.fill', [
            'columns' => $exercise['columns'],
            'wordForms' => $exercise['wordForms'],
            'toFill' => $exercise['toFill'],
            'groupKey' => $exercise['groupKey'],
        ]);
    }

    public function checkFill(Request $request)
    {
        $groupKey = $request->input('group_key');
        $answers = $request->input('answers', []);

        $result = (new CheckFillAnswersService)->check($groupKey, $answers);

        return response()->json($result);
    }
}