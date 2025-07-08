<?php
namespace App\Http\Controllers\EvaluateConjugations\Services;

use App\Models\Word;

class CheckFillAnswersService
{
    public function check($groupKey, $answers)
    {
        // Obtener las formas correctas de la palabra
        $forms = Word::where('group_key', $groupKey)
            ->with('conjugation')
            ->get()
            ->pluck('word', 'conjugation.name')
            ->toArray();

        $feedback = [];
        $allCorrect = true;
        foreach ($answers as $conjugation => $userAnswer) {
            $correct = isset($forms[$conjugation]) && trim(strtolower($userAnswer)) === trim(strtolower($forms[$conjugation]));
            $feedback[$conjugation] = [
                'correct' => $correct,
                'correct_answer' => $forms[$conjugation] ?? '',
            ];
            if (!$correct) $allCorrect = false;
        }

        return [
            'feedback' => $feedback,
            'allCorrect' => $allCorrect,
        ];
    }
}