<?php

namespace App\Policies;

use App\Models\Games;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamesPolicy
{
    use HandlesAuthorization;

    /**
     * Model instance
     */
    private $gameModel;

    /**
     * GamesPolicy constructor.
     */
    public function __construct(Games $gameModel)
    {
        // create model instance
        $this->gameModel = $gameModel;
    }

    /**
     * View games permissions
     * @param User $user
     * @return bool
     */
    public function viewGames(User $user)
    {
        return $user->hasPermission('ViewGames');
    }

    /**
     * Create game permission
     * @param User $user
     * @return bool
     */
    public function createGame(User $user)
    {
        return $user->hasPermission('CreateGame');
    }

    /**
     * Update game permission
     * @param User $user
     * @return bool
     */
    public function updateGame(User $user)
    {
        return $user->hasPermission('UpdateGame');
    }

    /**
     * Delete game permission
     * @param User $user
     * @return bool
     */
    public function deleteGame(User $user)
    {
        // get current game
        $currentGame = $this->gameModel->getGameInfo(request()->gameId)->load('servers');

        return $user->hasPermission('DeleteGame');
    }
}
