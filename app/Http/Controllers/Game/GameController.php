<?php

namespace App\Http\Controllers\Game;

use App\Helpers\LayoutTransformer;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\UserMove;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \JsonException
     */
    public function index(Request $request): Response
    {
        $game = Game::query()
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get()
            ->first();

        $userMove = UserMove::create([
            'user_id' => $request->user()->id,
            'game_id' => $game->getKey(),
            'layout' => $game->layout,
        ]);

        return Inertia::render('game/Index', [$userMove->toArray(), 'gameId' => $game->getKey()]);
    }

    public function move(Game $game, Request $request): RedirectResponse|Redirector|JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'layout' => 'required'
        ]);

        $layout = LayoutTransformer::fromRequest($request->post('layout'));
        $userMove = UserMove::create(
            [
                'game_id' => $game->getKey(),
                'user_id' => $user->getKey(),
                'layout' => $layout,
            ]
        );
        $userMove->save();

        if ($userMove->layout === []) {
            return to_route('game.leaderboard');
        }
        return response()->json($userMove);
    }

    public function shuffle(Game $game, Request $request): JsonResponse
    {
        $user = $request->user();
        $previousMove = UserMove::query()
            ->where('game_id', $game->getKey())
            ->where('user_id', $user->getKey())
            ->where('is_undone', false)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        $validator = Validator::make(
            ['move' => $previousMove],
            ['move' => 'required']
        );
        $validator->validate();

        $coords = $previousMove->layout;
        $types = [];
        foreach ($coords as $coord) {
            $types[] = array_pop($coord);
        }

        shuffle($coords);
        foreach ($coords as $coord) {
            $coord[] = array_pop($types);
        }

        $shuffled = UserMove::create([
            'game_id' => $game->getKey(),
            'user_id' => $user->getKey(),
            'layout' => $coords,
        ]);
        return response()->json($shuffled);
    }

    public function undo(Game $game, Request $request): JsonResponse
    {
        $previousMoves = UserMove::query()
            ->where('game_id', $game->getKey())
            ->where('user_id', $request->user()->id)
            ->where('is_undone', false)
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();
        $validator = Validator::make(
            ['moves' => $previousMoves],
            ['moves' => 'required|array|size:2']
        );
        $validator->validate();

        $moveQuery = UserMove::query()
            ->where('game_id', $game->getKey())
            ->where('user_id', $request->user()->id)
            ->where('is_undone', false)
            ->orderBy('created_at', 'desc')
            ->limit(1);

        $undoneMove = $moveQuery->first();
        $undoneMove->setAttribute('is_undone', true);
        $undoneMove->save();

        $move = $moveQuery->first();
        return response()->json($move);
    }
}
