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
    Route::post('/incrementStudyCount', [StudyController::class, 'incrementStudyCount'])->name('study.incrementStudyCount');



});

Route::prefix('collections')->group(function () {
    Route::get('/', function () {
        return view('collections.index');
    })->name('collections.index');

    Route::get('/create', function () {
        return view('collections.create');
    })->name('collections.create');

    Route::get('/import', function () {
        return view('collections.import');
    })->name('collections.import');

    Route::get('/manage', function () {
        return view('collections.manage', [
            'collections' => \App\Models\Collection::all()
        ]);
    })->name('collections.manage');

    Route::get('/stats', function () {
        return view('collections.stats', [
            'collections' => \App\Models\Collection::all()
        ]);
    })->name('collections.stats');
});
