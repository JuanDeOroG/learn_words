<?php

use App\Http\Controllers\EvaluateConjugations\EvaluateConjugationController;
use App\Http\Controllers\Study\StudyController;
use App\Models\Collection;
use App\Models\Conjugation;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('evaluate')->group(function () {

    Route::get('/', function () {
        return view('evaluate.index', ['conjugations' => Conjugation::all()]);
    })->name('evaluate.index');

    Route::prefix('/conjugations')->group(function () {

        Route::get('/fill', [EvaluateConjugationController::class, 'fill'])->name('evaluate.fillConjugations');
        Route::post('/fill/check', [EvaluateConjugationController::class, 'checkFill'])->name('evaluate.fillConjugations.check');
        // Route::get('order', [App\Http\Controllers\EvaluationController::class, 'order'])->name('evaluate.order');
        // Route::get('choice', [App\Http\Controllers\EvaluationController::class, 'choice'])->name('evaluate.choice');
    });
});

Route::prefix('study')->group(function () {
    
    Route::get('/', function () {return view('study.index', ['collections'=>Collection::all()]);})->name('study.index');
    Route::get('/session', [StudyController::class, 'study'])->name('study.session');




});
