<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'charge_type';
    protected $primaryKey = 'id';
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Get all cards list
     * @return mixed
     * @created 2020-02-26 LongTHK
     */
    public function getAllCardsList()
    {
        return $this->orderBy('name')
            ->get();
    }

    /**
     * Get card list depend on page
     * @param $currentPage
     * @return mixed
     * @created 2020-02-24 LongTHK
     */
    public function getCardsList($currentPage = 1)
    {
        return $this->orderBy('type')
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
     * Create new card
     * @created 2020-02-24 LongTHK
     */
    public function createCard($cardType, $careName)
    {
        // new instance
        $newCard = new Cards();

        // set card info
        $newCard->type = $cardType;
        $newCard->name = $careName;

        // save
        $newCard->save();
    }

    /**
     * Delete card by given card id
     * @param $cardId
     * @created 2020-02-24 LongTHK
     */
    public function deleteCard($cardId)
    {
        $this->find($cardId)->delete();
    }

    /**
     * Get card info
     * @param $cardId
     * @return mixed
     * @created 2020-02-24 LongTHK
     */
    public function getCardInfo($cardId)
    {
        return $this->find($cardId);
    }

    /**
     * Update card
     * @param $cardId
     * @param $cardType
     * @param $cardName
     * @created 2020-02-24 LongTHK
     */
    public function updateCard($cardId, $cardType, $cardName)
    {
        // get card info
        $cardInfo = $this->find($cardId);

        // set card info
        $cardInfo->type = $cardType;
        $cardInfo->name = $cardName;

        // save
        $cardInfo->save();
    }

    /**
     * Update card status
     * @param $cardId
     * @param $cardStatus
     * @created 2020-05-08 LongTHK
     */
    public function updateCardStatus($cardId, $cardStatus)
    {
        // get card
        $card = $this->find($cardId);

        // set info
        $card->status = $cardStatus;

        // save
        $card->save();
    }
}
