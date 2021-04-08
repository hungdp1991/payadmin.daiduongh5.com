<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Requests\Servers\ServersCreatingRequest;
use App\Http\Requests\Servers\ServersUpdatingRequest;
use App\Http\Resources\ServersResource;
use App\Models\Games;
use App\Models\Servers;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServersController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $serversModel;
    private $gamesModel;

    /**
     * ServersController constructor.
     * @param $serversModel
     * @param $gamesModel
     * @created 2020-02-20 LongTHk
     */
    public function __construct(Servers $serversModel, Games $gamesModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->serversModel = $serversModel;
        $this->gamesModel = $gamesModel;
    }

    /**
     * Get paginated servers list
     * @return AnonymousResourceCollection
     * @created 2020-02-13 LongTHK
     */
    public function list()
    {
        // get current page
        $currentPage = request()->currentPage;
        // get game ids list
        $gameIdsList = request()->user()->role_agent;

        // return collection
        return ServersResource::collection($this->serversModel->getServersList($gameIdsList, $currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->serversModel->getTotalPage($gameIdsList)
            ]
        ]);
    }

    /**
     * Delete server
     * @return AnonymousResourceCollection
     * @created 2020-02-20 LongTHK
     */
    public function delete()
    {
        // get id
        $id = request()->id;
        // get game ids list
        $gameIdsList = request()->user()->role_agent;

        // delete post
        $this->serversModel->deleteServer($id);

        // return collection
        return ServersResource::collection($this->serversModel->getServersList($gameIdsList))->additional([
            'pagination' => [
                'currentPage' => request()->currentPage,
                'totalPage' => $this->serversModel->getTotalPage($gameIdsList)
            ]
        ]);
    }

    /**
     * Create new server
     * @param ServersCreatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-13 LongTHK
     */
    public function create(ServersCreatingRequest $request)
    {
        // create new server
        $this->serversModel->createServer($request->serverId, $request->serverName, $request->serverSlug, $request->serverStatus, $request->paymentStatus, $request->keyWebCharge, $request->keyIAPCharge, $request->gameId);
        // get current page
        $currentPage = $request->currentPage;
        // get game ids list
        $gameIdsList = request()->user()->role_agent;

        // return new users list
        return ServersResource::collection($this->serversModel->getServersList($gameIdsList, $currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->serversModel->getTotalPage($gameIdsList)
            ]
        ]);
    }

    /**
     * Get server info
     * @return ServersResource
     * @created 2020-02-13 LongTHK
     */
    public function info()
    {
        return ServersResource::make($this->serversModel->getServerInfo(request()->id));
    }

    /**
     * Update server
     * @param ServersUpdatingRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-20 LongTHK
     */
    public function update(ServersUpdatingRequest $request)
    {
        // update server
        $this->serversModel->updateServer($request->id, $request->serverId, $request->serverName, $request->serverSlug, $request->serverStatus, $request->paymentStatus, $request->keyWebCharge, $request->keyIAPCharge, $request->gameId);
        // get current page
        $currentPage = $request->currentPage;
        // get game ids list
        $gameIdsList = request()->user()->role_agent;

        // return new servers list
        return ServersResource::collection($this->serversModel->getServersList($gameIdsList, $currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->serversModel->getTotalPage($gameIdsList)
            ]
        ]);
    }

    /**
     * Update server status
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function updateServerStatus()
    {
        // update server
        $this->serversModel->updateServerStatus(request()->id, request()->serverStatus);
        // get current page
        $currentPage = request()->currentPage;
        // get game ids list
        $gameIdsList = request()->user()->role_agent;

        // return new servers list
        return ServersResource::collection($this->serversModel->getServersList($gameIdsList, $currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->serversModel->getTotalPage($gameIdsList)
            ]
        ]);
    }

    /**
     * Update server payment status
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function updateServerPayment()
    {
        // update server
        $this->serversModel->updateServerPaymentStatus(request()->id, request()->paymentStatus);
        // get current page
        $currentPage = request()->currentPage;
        // get game ids list
        $gameIdsList = request()->user()->role_agent;

        // return new servers list
        return ServersResource::collection($this->serversModel->getServersList($gameIdsList, $currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->serversModel->getTotalPage($gameIdsList)
            ]
        ]);
    }

    /**
     * Search servers list
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function search()
    {
        return ServersResource::collection($this->serversModel->searchServersList(request()->user()->role_agent, request()->gameId, request()->serverStatus, request()->paymentStatus));
    }

    /**
     * Get all servers list belong to game
     * @created 2020-02-27 LongTHK
     */
    public function listByGame()
    {
        // get game agent
        $gameAgent = request()->gameAgent;

        // get game id by game agent
        $gameId = empty($gameAgent) ?
            '' :
            $this->gamesModel->getGameInfoByAgent($gameAgent)->id;

        // get all servers belong to game
        return ServersResource::collection($this->serversModel->getServersListByGameId($gameId))->additional([
            'gameAgent' => $gameAgent
        ]);
    }
}
