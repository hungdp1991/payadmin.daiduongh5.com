<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Requests\Card\CardsCreatingRequest;
use App\Http\Requests\Card\CardsUpdatingRequest;
use App\Http\Requests\Gold\GoldCreatingRequest;
use App\Http\Requests\Gold\GoldUpdatingRequest;
use App\Http\Resources\CardsResource;
use App\Http\Resources\GoldResource;
use App\Models\Cards;
use App\Models\Games;
use App\Models\Gold;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GoldController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $goldModel;
    private $gamesModel;

    /**
     * CardsController constructor.
     * @param $goldModel
     * @param $gamesModel
     * @created 2020-02-24 LongTHK
     */
    public function __construct(Gold $goldModel, Games $gamesModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->goldModel = $goldModel;
        $this->gamesModel = $gamesModel;
    }

    /**
     * Get gold list
     * @return AnonymousResourceCollection
     * @created 2020-02-24 LongTHK
     */
    public function list()
    {
        // get current page
        $currentPage = request()->currentPage;

        // return collection
        return GoldResource::collection($this->goldModel->getGoldList($this->generateGameAgentsList(), $currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->goldModel->getTotalPage($this->generateGameAgentsList())
            ]
        ]);
    }

    /**
     * Get game agents list by game ids list
     * @return array
     * @created 2020-04-14 LongTHK
     */
    private function generateGameAgentsList()
    {
        // get game agents list by ids list
        $gameIdsList = request()->user()->role_agent;

        if (empty($gameIdsList)) {
            return [];
        }

        return $this->gamesModel->getAllGamesList($gameIdsList)->pluck('agent')->toArray();;
    }

    /**
     * Create new game
     * @param GoldCreatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-24 LongTHK
     */
    public function create(GoldCreatingRequest $request)
    {
        // create new gold
        $this->goldModel->createGold($request->goldName, $request->goldDescription, $request->goldImage, $request->goldAmount, $request->goldValue, $request->productGoldId, $request->goldType, $this->getGameAgentByGameId($request->gameId));

        // return collection
        return GoldResource::collection($this->goldModel->getGoldList($this->generateGameAgentsList()))->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->goldModel->getTotalPage($this->generateGameAgentsList())
            ]
        ]);
    }

    /**
     * Get game agent by given game id
     * @param $gameId
     * @return mixed
     * @created 2020-04-14 LongTHk
     */
    private function getGameAgentByGameId($gameId)
    {
        return $this->gamesModel->find($gameId)->agent;
    }

    /**
     * Delete games
     * @return AnonymousResourceCollection
     * @created 2020-02-19 LongTHK
     */
    public function delete()
    {
        // delete gold
        $this->goldModel->deleteGold(request()->goldId);

        // return collection
        return GoldResource::collection($this->goldModel->getGoldList($this->generateGameAgentsList()))->additional([
            'pagination' => [
                'currentPage' => request()->currentPage,
                'totalPage' => $this->goldModel->getTotalPage($this->generateGameAgentsList())
            ]
        ]);
    }

    /**
     * Get game info
     * @return GoldResource
     * @created 2020-02-24 LongTHK
     */
    public function info()
    {
        return GoldResource::make($this->goldModel->getGoldInfo(request()->goldId));
    }

    /**
     * Update post
     * @param GoldUpdatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-24 LongTHK
     */
    public function update(GoldUpdatingRequest $request)
    {
        // update gold
        $this->goldModel->updateGold($request->goldId, $request->goldName, $request->goldDescription, $request->goldImage, $request->goldAmount, $request->goldValue, $request->productGoldId, $request->goldType, $this->getGameAgentByGameId($request->gameId));

        // return collection
        return GoldResource::collection($this->goldModel->getGoldList($this->generateGameAgentsList()))->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->goldModel->getTotalPage($this->generateGameAgentsList())
            ]
        ]);
    }

    /**
     * Search gold list
     * @return AnonymousResourceCollection
     * @created 2020-04-14 LongTHK
     */
    public function search()
    {
        return GoldResource::collection($this->goldModel->searchGoldList($this->generateGameAgentsList(), request()->gameAgent, request()->goldType));
    }

    /**
     * Get gold list by game
     * @return AnonymousResourceCollection
     * @created 2020-04-22 LongTHK
     */
    public function listByGame()
    {
        // get all servers belong to game
        return GoldResource::collection($this->goldModel->getGoldListByGameAgent(request()->gameAgent));
    }
}
