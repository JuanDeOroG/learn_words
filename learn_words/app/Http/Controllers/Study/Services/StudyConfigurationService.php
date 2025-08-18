<?php

namespace App\Http\Controllers\Study\Services;

use App\Models\Word;
use App\Models\Collection;

class StudyConfigurationService
{
    /**
     * recibe la configuración del usuario y prepara los datos para la sesión de estudio.
     */
    public function prepare(array $config)
    {
        // obtener las colecciones seleccionadas
        $collectionIds = $config['collections'] ?? [];
        $collections = Collection::whereIn('id', $collectionIds)->get();

        // obtener palabras de las colecciones seleccionadas
        $wordsQuery = Word::filterByCollections($collectionIds);

        // aplicar orden de estudio
        switch ($config['order'] ?? 'random') {
            case 'least_studied':
                // aquí deberías tener una columna o relación para saber cuántas veces se ha estudiado cada palabra
                $wordsQuery->orderBy('study_count', 'asc');
                break;
            case 'recent':
                $wordsQuery->orderBy('created_at', 'desc');
                break;
            case 'older':
                $wordsQuery->orderBy('created_at', 'asc');
                break;
            case 'random':
            default:
                $wordsQuery->inRandomOrder();
                break;
        }

        // aplicar cantidad de palabras solo si el objetivo es por cantidad
        if (($config['goal_type'] ?? '') === 'quantity' && !empty($config['words_count'])) {
            $wordsQuery->limit((int)$config['words_count']);
        }
        $words = $wordsQuery->get();

        // definir el objetivo de la sesión de estudio
        $goalType = $config['goal_type'] ?? null;
        $goalValue = null;

        if ($goalType === 'time' && !empty($config['words_time'])) {
            // si el objetivo es por tiempo, calcular la hora de finalización
            $goalValue = now()->addMinutes((int)$config['words_time']);
        } elseif ($goalType === 'quantity' && !empty($config['words_count'])) {
            // si el objetivo es por cantidad, usar el número de palabras
            $goalValue = (int)$config['words_count'];
        }

        $goal = [
            'type' => $goalType,
            'value' => $goalValue,
        ];

        // modo de presentación
        $mode = $config['presentation_mode'] ?? 'flashcard';

        // retornar los datos preparados
        return [
            'words' => $words,
            'mode' => $mode,
            'goal' => $goal,
            'order' => ($config['order'] ?? 'random') === 'least_studied' ? 'least studied' : ($config['order'] ?? 'random'),
            'collections' => $collections,
        ];
    }
}