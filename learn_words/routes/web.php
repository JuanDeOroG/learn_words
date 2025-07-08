<?php

use App\Http\Controllers\EvaluateConjugations\EvaluateConjugationController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('evaluateConjugations')->group(function () {
    Route::get('/', function () {return view('evaluateConjugations.index');})->name('evaluateConjugations.index');

    Route::get('/fill', [EvaluateConjugationController::class, 'fill'])->name('evaluateConjugations.fill');
    Route::post('/fill/check', [EvaluateConjugationController::class, 'checkFill'])->name('evaluateConjugations.fill.check');
    // Route::get('order', [App\Http\Controllers\EvaluationController::class, 'order'])->name('evaluate.order');
    // Route::get('choice', [App\Http\Controllers\EvaluationController::class, 'choice'])->name('evaluate.choice');
});

Route::prefix('study')->group(function () {
    Route::get('/', function () {return view('study.index');})->name('study.index');

});
