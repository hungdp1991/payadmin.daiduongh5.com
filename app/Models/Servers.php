<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function Aws\boolean_value;

class Servers extends Model
{
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'servers';
    protected $primaryKey = 'id';
    protected $casts = [
        'pay_status' => 'boolean',
    ];
    protected $perPage = 30;

    /**
     * Create Servers - Game relation
     * @return mixed
     */
    public function game()
    {
        return $this->belongsTo(Games::class, 'product_id', 'id');
    }

    /**
     * Get servers list depend on page
     * @param $gameIdsList
     * @param $currentPage
     * @return mixed
     * @created 2020-02-20 LongTHK
     */
    public function getServersList($gameIdsList, $currentPage = 1)
    {
        return $this->when(!empty($gameIdsList), function($query) use($gameIdsList) {
            return $query->whereIn('product_id', $gameIdsList);
        })
            ->orderByDesc('created_at')
            ->skip(($currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    /**
     * Get total page after paginate
     * @param $gameIdsList
     * @return float
     * @created 2020-02-20 LongTHK
     */
    public function getTotalPage($gameIdsList)
    {
        return ceil($this->when(!empty($gameIdsList), function($query) use($gameIdsList) {
                return $query->whereIn('product_id', $gameIdsList);
            })->count() / $this->perPage);
    }

    /**
     * Delete server
     * @param $id
     * @return bool|void|null
     * @created 2020-02-20 LongTHK
     */
    public function deleteServer($id)
    {
        $this->find($id)->delete();
    }

    /**
     * Create new server
     * @param $serverId
     * @param $serverName
     * @param $serverSlug
     * @param $serverStatus
     * @param $paymentStatus
     * @param $keyWebCharge
     * @param $keyIAPCharge
     * @param $gameId
     * @created 2020-02-20 LongTHK
     */
    public function createServer($serverId, $serverName, $serverSlug, $serverStatus, $paymentStatus, $keyWebCharge, $keyIAPCharge, $gameId)
    {
        // create new instance
        $newServer = new Servers();

        // set info
        $newServer->server_id = $serverId;
        $newServer->server_name = $serverName;
        $newServer->server_slug = $serverSlug;
        $newServer->server_status = $serverStatus;
        $newServer->pay_status = $paymentStatus;
        $newServer->key_web_charge = $keyWebCharge;
        $newServer->key_iap_charge = $keyIAPCharge;
        $newServer->product_id = $gameId;

        // save
        $newServer->save();
    }

    /**
     * Get server info
     * @param $id
     * @return mixed
     * @created 2020-02-20 LongTHK
     */
    public function getServerInfo($id)
    {
        return $this->find($id);
    }

    /**
     * Update server
     * @param $id
     * @param $serverId
     * @param $serverName
     * @param $serverSlug
     * @param $serverStatus
     * @param $paymentStatus
     * @param $keyWebCharge
     * @param $keyIAPCharge
     * @param $gameId
     * @created 2020-02-20 LongTHK
     */
    public function updateServer($id, $serverId, $serverName, $serverSlug, $serverStatus, $paymentStatus, $keyWebCharge, $keyIAPCharge, $gameId)
    {
        // get server
        $server = $this->find($id);

        // set info
        $server->server_id = $serverId;
        $server->server_name = $serverName;
        $server->server_slug = $serverSlug;
        $server->server_status = $serverStatus;
        $server->pay_status = $paymentStatus;
        $server->key_web_charge = $keyWebCharge;
        $server->key_iap_charge = $keyIAPCharge;
        $server->product_id = $gameId;

        // save
        $server->save();
    }

    /**
     * Update server status
     * @param $id
     * @param $serverStatus
     * @created 2020-02-21 LongTHK
     */
    public function updateServerStatus($id, $serverStatus)
    {
        // get server
        $server = $this->find($id);

        // set info
        $server->server_status = $serverStatus;

        // save
        $server->save();
    }

    /**
     * Update server payment status
     * @param $id
     * @param $paymentStatus
     * @created 2020-02-21 LongTHK
     */
    public function updateServerPaymentStatus($id, $paymentStatus)
    {
        // get server
        $server = $this->find($id);

        // set info
        $server->pay_status = boolean_value($paymentStatus);

        // save
        $server->save();
    }

    /**
     * Search servers list
     * @param $gameIdsList
     * @param $gameId
     * @param $serverStatus
     * @param $paymentStatus
     * @created 2020-02-21 LongTHK
     * @return mixed
     */
    public function searchServersList($gameIdsList, $gameId, $serverStatus, $paymentStatus)
    {
        return $this->when(!empty($gameIdsList), function ($query) use($gameIdsList) {
            return $query->whereIn('product_id', $gameIdsList);
        })->when(!empty($gameId), function ($query) use ($gameId) {
            return $query->where('product_id', '=', $gameId);
        })->when(!empty($serverStatus), function ($query) use ($serverStatus) {
            return $query->where('server_status', '=', $serverStatus);
        })->when($paymentStatus !== null, function ($query) use ($paymentStatus) {
            return $query->where('pay_status', '=', $paymentStatus);
        })->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get all servers list belong to game
     * @param $gameId
     * @return mixed
     * @created 2020-02-27 LongTHK
     */
    public function getServersListByGameId($gameId)
    {
        return $this->where('product_id', '=', $gameId)
            ->orderBy('created_at')
            ->get();
    }
}
