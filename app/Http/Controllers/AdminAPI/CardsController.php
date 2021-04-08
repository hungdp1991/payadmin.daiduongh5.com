<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Requests\Card\CardsCreatingRequest;
use App\Http\Requests\Card\CardsUpdatingRequest;
use App\Http\Resources\CardsResource;
use App\Models\Cards;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CardsController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $cardsModel;

    /**
     * CardsController constructor.
     * @param $cardsModel
     * @created 2020-02-24 LongTHK
     */
    public function __construct(Cards $cardsModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->cardsModel = $cardsModel;
    }

    /**
     * Get all cards list
     * @return AnonymousResourceCollection
     * @created 2020-02-26 LongTHK
     */
    public function all()
    {
        // return collection
        return CardsResource::collection($this->cardsModel->getAllCardsList());
    }

    /**
     * Get cards list
     * @return AnonymousResourceCollection
     * @created 2020-02-24 LongTHK
     */
    public function list()
    {
        // get current page
        $currentPage = request()->currentPage;

        // return collection
        return CardsResource::collection($this->cardsModel->getCardsList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->cardsModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Create new game
     * @param CardsCreatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-17 LongTHK
     */
    public function create(CardsCreatingRequest $request)
    {
        // create new game
        $this->cardsModel->createCard($request->cardType, $request->cardName);

        // return collection
        return CardsResource::collection($this->cardsModel->getCardsList())->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->cardsModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Delete games
     * @return AnonymousResourceCollection
     * @created 2020-02-19 LongTHK
     */
    public function delete()
    {
        // delete game
        $this->cardsModel->deleteCard(request()->cardId);

        // return collection
        return CardsResource::collection($this->cardsModel->getCardsList())->additional([
            'pagination' => [
                'currentPage' => request()->currentPage,
                'totalPage' => $this->cardsModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Get game info
     * @return CardsResource
     * @created 2020-02-24 LongTHK
     */
    public function info()
    {
        return CardsResource::make($this->cardsModel->getCardInfo(request()->cardId));
    }

    /**
     * Update post
     * @param CardsUpdatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-24 LongTHK
     */
    public function update(CardsUpdatingRequest $request)
    {
        // update card
        $this->cardsModel->updateCard($request->cardId, $request->cardType, $request->cardName);

        // return collection
        return CardsResource::collection($this->cardsModel->getCardsList())->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->cardsModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Update card status
     * @return AnonymousResourceCollection
     * @created 2020-05-08 LongTHK
     */
    public function updateCardStatus()
    {
        // update server
        $this->cardsModel->updateCardStatus(request()->cardId, request()->cardStatus);
        // get current page
        $currentPage = request()->currentPage;

        // return new servers list
        return CardsResource::collection($this->cardsModel->getCardsList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->cardsModel->getTotalPage()
            ]
        ]);
    }
}
