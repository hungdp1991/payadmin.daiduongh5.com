<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $casts = [
        'status' => 'boolean',
        'limit_local' => 'array',
        'accepted_charge_type' => 'array'
    ];
    protected $perPage = 9;

    /**
     * Define boot function
     */
    protected static function boot()
    {
        parent::boot();

        // delete all servers belongs to game
        static::deleting(function($game) {
            $game->servers()->delete();
        });
    }

    /**
     * Create Games - Servers relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servers()
    {
        return $this->hasMany(Servers::class, 'product_id', 'id');
    }

    /**
     * Get all games list
     * @param $gameIdsList
     * @return mixed
     * @created 2020-02-20 LongTHK
     */
    public function getAllGamesList($gameIdsList)
    {
        return $this->when(!empty($gameIdsList), function($query) use($gameIdsList) {
            return $query->whereIn('id', $gameIdsList);
        })
            ->orderBy('order')
            ->get();
    }

    /**
     * Get games list depend on page
     * @param $currentPage
     * @return mixed
     * @created 2020-02-19 LongTHK
     */
    public function getGamesList($currentPage = 1)
    {
        return $this->orderBy('order')
            ->skip(($currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    /**
     * Get total page after paginate
     * @return float
     * @created 2020-02-19 LongTHK
     */
    public function getTotalPage()
    {
        return ceil($this->count() / $this->perPage);
    }

    /**
     * Create new post
     * @param $gameAgent
     * @param $gameName
     * @param $gameSlug
     * @param $gameImage
     * @param $paymentType
     * @param $acceptedChargeType
     * @param $gameRedirect
     * @param $limitIPList
     * @created 2020-02-19 LongTHK
     */
    public function createGame($gameAgent, $gameName, $gameSlug, $gameImage, $paymentType, $acceptedChargeType, $gameRedirect, $limitIPList)
    {
        // new instance
        $newGame = new Games();

        // set game info
        $newGame->agent = $gameAgent;
        $newGame->name = $gameName;
        $newGame->slug = $gameSlug;
        $newGame->avatar = $gameImage;
        $newGame->payment_type = $paymentType;
        $newGame->accepted_charge_type = $acceptedChargeType;
        $newGame->url_redirect = $gameRedirect;
        $newGame->limit_local = $limitIPList;

        // save
        $newGame->save();
    }

    /**
     * Delete game by given game id
     * @param $gameId
     * @created 2020-02-19 LongTHK
     */
    public function deleteGame($gameId)
    {
        $this->find($gameId)->delete();
    }

    /**
     * Get game info
     * @param $gameId
     * @return mixed
     * @created 2020-02-19 LongTHK
     */
    public function getGameInfo($gameId)
    {
        return $this->find($gameId);
    }

    /**
     * Update post
     * @param $gameId
     * @param $gameAgent
     * @param $gameName
     * @param $gameSlug
     * @param $gameImage
     * @param $paymentType
     * @param $acceptedChargeType
     * @param $gameRedirect
     * @param $limitIPList
     * @created 2020-02-17 LongTHK
     */
    public function updateGame($gameId, $gameAgent, $gameName, $gameSlug, $gameImage, $paymentType, $acceptedChargeType, $gameRedirect, $limitIPList)
    {
        // get game info
        $gameInfo = $this->find($gameId);

        // set new post info
        $gameInfo->agent = $gameAgent;
        $gameInfo->name = $gameName;
        $gameInfo->slug = $gameSlug;
        $gameInfo->avatar = $gameImage;
        $gameInfo->payment_type = $paymentType;
        $gameInfo->accepted_charge_type = $acceptedChargeType;
        $gameInfo->url_redirect = $gameRedirect;
        $gameInfo->limit_local = $limitIPList;

        // save
        $gameInfo->save();
    }

    /**
     * Get game info by given agent
     * @param $gameAgent
     * @return mixed
     * @created 2020-02-27 LongTHk
     */
    public function getGameInfoByAgent($gameAgent)
    {
        return $this->where('agent', '=', $gameAgent)
            ->first();
    }

    /**
     * Update game order
     * @param $gameId
     * @param $gameOrder
     */
    public function updateGameOrder($gameId, $gameOrder)
    {
        // get current game
        $currentGame = $this->find($gameId);
        // set order
        $currentGame->order = $gameOrder;

        // save
        $currentGame->save();
    }

    /**
     * Change game status
     * @param $gameId
     * @param $gameStatus
     */
    public function changeStatus($gameId, $gameStatus)
    {
        // get game info
        $game = $this->findOrFail($gameId);

        // update status
        $game->status = $gameStatus;

        // save
        $game->save();
    }

    /**
     * Get game agents list by given ids list
     * @param $idsList
     * @return mixed
     */
    public function getAgentsByIds($idsList)
    {
        return $this->whereIn('id', $idsList)
            ->pluck('agent')
            ->toArray();
    }

    /**
     * Get game ids list
     * @return mixed
     */
    public function getIdsList()
    {
        return $this
            ->pluck('id')
            ->toArray();
    }
}
