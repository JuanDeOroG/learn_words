<?php

namespace App\Http\Controllers\EvaluateFill;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluateFill\SetFillConfigurationRequest;
use App\Http\Controllers\EvaluateFill\Services\SetFillConfiguration;
use App\Http\Requests\EvaluateFill\StoreEvaluationRequest;
use App\Http\Controllers\EvaluateFill\Services\StoreEvaluationService;

class EvaluateFillController extends Controller
{

public function session(SetFillConfigurationRequest $request)
{
    $validated = $request->validated();
    $service = new SetFillConfiguration();
    $words = $service->getWords($validated);

    // Puedes pasar también la configuración elegida a la vista
    return view('evaluate.fill.session', [
        'words' => $words,
        'config' => $validated,
    ]);
}

public function store(StoreEvaluationRequest $request)
{
    $service = new StoreEvaluationService();
    $evaluation = $service->store($request->validated());

    return response()->json([
        'success' => true,
        'evaluation' => $evaluation,
    ]);
}

}