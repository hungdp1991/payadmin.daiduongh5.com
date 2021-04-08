<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Requests\Games\GamesCreatingRequest;
use App\Http\Requests\Games\GamesUpdatingRequest;
use App\Http\Resources\GamesResource;
use App\Models\Games;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GamesController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $gamesModel;

    /**
     * GamesController constructor.
     * @param $gamesModel
     * @created 2020-02-19 LongTHK
     */
    public function __construct(Games $gamesModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->gamesModel = $gamesModel;
    }

    /**
     * Get all games list
     * @return AnonymousResourceCollection
     * @created 2020-02-20 LongTHK
     */
    public function all()
    {
        // return collection
        return GamesResource::collection($this->gamesModel->getAllGamesList(request()->user()->role_agent));
    }

    /**
     * Get games list
     * @return AnonymousResourceCollection
     * @created 2020-02-19 LongTHK
     */
    public function list()
    {
        // get current page
        $currentPage = request()->currentPage;

        // return collection
        return GamesResource::collection($this->gamesModel->getGamesList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->gamesModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Create new game
     * @param GamesCreatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-17 LongTHK
     */
    public function create(GamesCreatingRequest $request)
    {
        // get game info
        $gameAgent = $request->gameAgent;
        $gameName = $request->gameName;
        $gameSlug = $request->gameSlug;
        $gameImage = $request->gameImage;
        $paymentType = $request->paymentType;
        $acceptedChargeType = $request->acceptedChargeType;
        $gameRedirect = $request->gameRedirect;
        $limitIPList = $request->limitIPList;

        // create new game
        $this->gamesModel->createGame($gameAgent, $gameName, $gameSlug, $gameImage, $paymentType, $acceptedChargeType, $gameRedirect, $limitIPList);

        // return collection
        return GamesResource::collection($this->gamesModel->getGamesList())->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->gamesModel->getTotalPage()
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
        // get game id
        $gameId = request()->gameId;
        // get current page
        $currentPage = request()->currentPage;

        // delete game
        $this->gamesModel->deleteGame($gameId);

        // return collection
        return GamesResource::collection($this->gamesModel->getGamesList())->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->gamesModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Get game info
     * @return GamesResource
     * @created 2020-02-19 LongTHK
     */
    public function info()
    {
        return GamesResource::make($this->gamesModel->getGameInfo(request()->gameId));
    }

    /**
     * Update post
     * @param GamesUpdatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-17 LongTHK
     */
    public function update(GamesUpdatingRequest $request)
    {
        // get game info
        $gameId = $request->gameId;
        $gameAgent = $request->gameAgent;
        $gameName = $request->gameName;
        $gameSlug = $request->gameSlug;
        $gameImage = $request->gameImage;
        $paymentType = $request->paymentType;
        $acceptedChargeType = $request->acceptedChargeType;
        $gameRedirect = $request->gameRedirect;
        $limitIPList = $request->limitIPList;

        // update game
        $this->gamesModel->updateGame($gameId, $gameAgent, $gameName, $gameSlug, $gameImage, $paymentType, $acceptedChargeType, $gameRedirect, $limitIPList);

        // return collection
        return GamesResource::collection($this->gamesModel->getGamesList())->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->gamesModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Arrange games list
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-04-13 LongTHK
     */
    public function arrange()
    {
        // get game order list
        $gamesOrderList = request()->gamesOrderList;

        foreach ($gamesOrderList as $gameOrderItemKey => $gamesOrderItemValue) {
            $this->gamesModel->updateGameOrder($gamesOrderItemValue, $gameOrderItemKey + 1);
        }

        return response()->json([], 200);
    }

    /**
     * Change game status
     */
    public function changeStatus()
    {
        // change game status
        $this->gamesModel->changeStatus(request()->gameId, request()->gameStatus);

        return GamesResource::collection($this->gamesModel->getAllGamesList(request()->user()->role_agent));
    }
}
