<?php

declare(strict_types=1);

use App\Http\Controllers\Game\GameController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('game', [GameController::class, 'index'])
        ->name('game.index');
    Route::post('game/{game}/move', [GameController::class, 'move'])
        ->name('game.move');
    Route::post('game/{game}/shuffle', [GameController::class, 'shuffle'])
        ->name('game.shuffle');
    Route::post('game/{game}/undo', [GameController::class, 'undo'])
        ->name('game.undo');
});
