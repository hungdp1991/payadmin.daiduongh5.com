<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gold extends Model
{
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'gold';
    protected $primaryKey = 'id';

    /**
     * Create Game - Gold relation
     * @created 2020-02-24 LongTHK
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Games::class, 'product_id', 'agent');
    }

    /**
     * Get gold list depend on page
     * @param $gameAgentsList
     * @param $currentPage
     * @return mixed
     * @created 2020-02-24 LongTHK
     */
    public function getGoldList($gameAgentsList, $currentPage = 1)
    {
        return $this->when(!empty($gameAgentsList), function ($query) use ($gameAgentsList) {
            return $query->whereIn('product_id', $gameAgentsList);
        })
            ->orderByDesc('id')
            ->skip(($currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    /**
     * Get total page after paginate
     * @param $gameAgentsList
     * @return float
     * @created 2020-02-19 LongTHK
     */
    public function getTotalPage($gameAgentsList)
    {
        return ceil($this->when(!empty($gameAgentsList), function($query) use ($gameAgentsList) {
            return $query->whereIn('product_id', $gameAgentsList);
        })->count() / $this->perPage);
    }

    /**
     * Create new gold
     * @param $goldName
     * @param $goldDescription
     * @param $goldImage
     * @param $goldAmount
     * @param $goldValue
     * @param $productGoldId
     * @param $goldType
     * @param $gameId
     * @created 2020-02-24 LongTHK
     */
    public function createGold($goldName, $goldDescription, $goldImage, $goldAmount, $goldValue, $productGoldId, $goldType, $gameId)
    {
        // new instance
        $newGold = new Gold();

        // set card info
        $newGold->name = $goldName;
        $newGold->description = $goldDescription;
        $newGold->image = $goldImage;
        $newGold->amount = $goldAmount;
        $newGold->gold = $goldValue;
        $newGold->product_gold_id = $productGoldId;
        $newGold->card_month = $goldType;
        $newGold->product_id = $gameId;

        // save
        $newGold->save();
    }

    /**
     * Delete gold by given card id
     * @param $goldId
     * @created 2020-02-24 LongTHK
     */
    public function deleteGold($goldId)
    {
        $this->find($goldId)->delete();
    }

    /**
     * Get gold info
     * @param $goldId
     * @return mixed
     * @created 2020-02-24 LongTHK
     */
    public function getGoldInfo($goldId)
    {
        return $this->find($goldId);
    }

    /**
     * Update gold
     * @param $goldId
     * @param $goldName
     * @param $goldDescription
     * @param $goldImage
     * @param $goldAmount
     * @param $goldValue
     * @param $productGoldId
     * @param $goldType
     * @param $gameId
     * @created 2020-02-24 LongTHK
     */
    public function updateGold($goldId, $goldName, $goldDescription, $goldImage, $goldAmount, $goldValue, $productGoldId, $goldType, $gameId)
    {
        // get gold info
        $goldInfo = $this->find($goldId);

        // set gold info
        $goldInfo->name = $goldName;
        $goldInfo->description = $goldDescription;
        $goldInfo->image = $goldImage;
        $goldInfo->amount = $goldAmount;
        $goldInfo->gold = $goldValue;
        $goldInfo->product_gold_id = $productGoldId;
        $goldInfo->card_month = $goldType;
        $goldInfo->product_id = $gameId;

        // save
        $goldInfo->save();
    }

    /**
     * Search gold list
     * @param $gameAgentsList
     * @param $gameId
     * @param $goldType
     * @return mixed
     * @created 2020-04-14 LongTHK
     */
    public function searchGoldList($gameAgentsList, $gameId, $goldType)
    {
        return $this->when(!empty($gameAgentsList), function ($query) use ($gameAgentsList) {
            return $query->whereIn('product_id', $gameAgentsList);
        })->when(!empty($gameId), function ($query) use ($gameId) {
            return $query->where('product_id', '=', $gameId);
        })->when(!empty($goldType), function ($query) use ($goldType) {
            return $query->where('card_month', '=', $goldType);
        })->get();
    }

    /**
     * Get gold list by given game id
     * @param $gameAgent
     * @return mixed
     * @created 2020-04-22 LongTHk
     */
    public function getGoldListByGameAgent($gameAgent)
    {
        return $this->where('product_id', '=', $gameAgent)
            ->get();
    }
}
