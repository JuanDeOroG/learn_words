<?php

use App\Models\Word;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('word', 100);
            $table->integer('conjugation_id')->index('fk_conjugation_id');
            $table->string('group_key', 50)->index('idx_group_key');
            $table->integer('study_count')->default(0); // contador de veces estudiada
            $table->timestamps();


            $table->index(['word', 'conjugation_id'], 'idx_word_conjugation');
        });


        $words = [
            ['awake', 'awoke', 'awoken'],
            ['be', 'was, were', 'been'],
            ['beat', 'beat', 'beaten'],
            ['become', 'became', 'become'],
            ['begin', 'began', 'begun'],
            ['bend', 'bent', 'bent'],
            ['bet', 'bet', 'bet'],
            ['bid', 'bid', 'bid'],
            ['bite', 'bit', 'bitten'],
            ['blow', 'blew', 'blown'],
            ['break', 'broke', 'broken'],
            ['bring', 'brought', 'brought'],
            ['broadcast', 'broadcast', 'broadcast'],
            ['build', 'built', 'built'],
            ['burn', 'burned', 'burned'],
            ['buy', 'bought', 'bought'],
            ['catch', 'caught', 'caught'],
            ['choose', 'chose', 'chosen'],
            ['come', 'came', 'come'],
            ['cost', 'cost', 'cost'],
            ['cut', 'cut', 'cut'],
            ['dig', 'dug', 'dug'],
            ['do', 'did', 'done'],
            ['draw', 'drew', 'drawn'],
            ['dream', 'dreamed', 'dreamed'],
            ['drive', 'drove', 'driven'],
            ['drink', 'drank', 'drunk'],
            ['eat', 'ate', 'eaten'],
            ['fall', 'fell', 'fallen'],
            ['feel', 'felt', 'felt'],
            ['fight', 'fought', 'fought'],
            ['find', 'found', 'found'],
            ['fly', 'flew', 'flown'],
            ['forget', 'forgot', 'forgotten'],
            ['forgive', 'forgave', 'forgiven'],
            ['freeze', 'froze', 'frozen'],
            ['get', 'got', 'got'],
            ['give', 'gave', 'given'],
            ['go', 'went', 'gone'],
            ['grow', 'grew', 'grown'],
            ['hang', 'hung', 'hung'],
            ['have', 'had', 'had'],
            ['hear', 'heard', 'heard'],
            ['hide', 'hid', 'hidden'],
            ['hit', 'hit', 'hit'],
            ['hold', 'held', 'held'],
            ['hurt', 'hurt', 'hurt'],
            ['keep', 'kept', 'kept'],
            ['know', 'knew', 'known'],
            ['lay', 'laid', 'laid'],
            ['lead', 'led', 'led'],
            ['learn', 'learned ', 'learned'],
            ['leave', 'left', 'left'],
            ['lend', 'lent', 'lent'],
            ['let', 'let', 'let'],
            ['lie', 'lay', 'lain'],
            ['lose', 'lost', 'lost'],
            ['make', 'made', 'made'],
            ['mean', 'meant', 'meant'],
            ['meet', 'met', 'met'],
            ['pay', 'paid', 'paid'],
            ['put', 'put', 'put'],
            ['read', 'read', 'read'],
            ['ride', 'rode', 'ridden'],
            ['ring', 'rang', 'rung'],
            ['rise', 'rose', 'risen'],
            ['run', 'ran', 'run'],
            ['say', 'said', 'said'],
            ['see', 'saw', 'seen'],
            ['sell', 'sold', 'sold'],
            ['send', 'sent', 'sent'],
            ['show', 'showed', 'showed'],
            ['shut', 'shut', 'shut'],
            ['sing', 'sang', 'sung'],
            ['sink', 'sank', 'sunk'],
            ['sit', 'sat', 'sat'],
            ['sleep', 'slept', 'slept'],
            ['speak', 'spoke', 'spoken'],
            ['spend', 'spent', 'spent'],
            ['stand', 'stood', 'stood'],
            ['stink', 'stank', 'stunk'],
            ['swim', 'swam', 'swum'],
            ['take', 'took', 'taken'],
            ['teach', 'taught', 'taught'],
            ['tear', 'tore', 'torn'],
            ['tell', 'told', 'told'],
            ['think', 'thought', 'thought'],
            ['throw', 'threw', 'thrown'],
            ['understand', 'understood', 'understood'],
            ['wake', 'woke', 'woken'],
            ['wear', 'wore', 'worn'],
            ['win', 'won', 'won'],
            ['write', 'wrote', 'written'],
        ];

        
        foreach ($words as $group) {
            $code = strtoupper(Str::random(7));

            $groupKey = $code; // Base form como clave del grupo

            Word::create([
                'word' => $group[0],
                'conjugation_id' => 1,
                'group_key' => $groupKey,
            ]);

            Word::create([
                'word' => $group[1],
                'conjugation_id' => 2,
                'group_key' => $groupKey,
            ]);

            Word::create([
                'word' => $group[2],
                'conjugation_id' => 3,
                'group_key' => $groupKey,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
