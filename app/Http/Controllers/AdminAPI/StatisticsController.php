<?php

namespace App\Http\Controllers\AdminAPI;

use App\Exports\CardHistoryExport;
use App\Exports\IAPHistoryExport;
use App\Exports\PayHistoryExport;
use App\Models\Games;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends AdminAPIBaseController
{
    /**
     * Properties
     */
    const PER_PAGE_VALUE = 100;
    const AMOUNT_FROM_VALUE = 1;
    const AMOUNT_TO_VALUE = 1000000;

    /**
     * Model instance
     */
    private $gameModel;

    /**
     * StatisticsController constructor.
     * @param $gameModel
     * @created 2020-02-25 LongTHK
     */
    public function __construct(Games $gameModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->gameModel = $gameModel;
    }

    /**
     * Get daily statistics
     * @return JsonResponse
     * @created 2020-02-25 LongTHK
     */
    public function getDailyStatistics()
    {
        // get date range
        $dateRange = $this->getDateRange(request()->timeRange);

        // start query statement
        $statement = 'CALL sp_statistic_periodic(';

        // check start-end date
        $this->appendQueryStatement($statement, $dateRange['startDate']);
        $this->appendQueryStatement($statement, $dateRange['endDate']);

        // check gameId on append statement
        $this->appendQueryStatement($statement, request()->gameId);

        // check agent on append statement
        $roleAgent = !empty(request()->user()->role_agent) ? request()->user()->role_agent : $this->gameModel->getIdsList();
        $agentList = $this->gameModel->getAgentsByIds($roleAgent);
        $this->appendQueryStatement($statement, implode(',', $agentList));

        // end query statement
        $statement .= ')';

        return response()->json([
            'statisticsList' => DB::select(Str::replaceLast(',', '', $statement))
        ], 200);
    }

    /**
     * Get date range
     * @return array
     * @created 2020-02-26 LongTHK
     */
    private function getDateRange($strTimeRange)
    {
        // get time range
        $currentDate = now();
        // set default time range
        $startDate = $currentDate->format('Y-m-d');
        $endDate = $currentDate->format('Y-m-d');
        // recheck time range
        if (!empty($strTimeRange)) {
            $timeRange = explode(' - ', $strTimeRange);
            $startDate = Carbon::createFromFormat('d/m/Y', $timeRange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $timeRange[1])->format('Y-m-d');
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

    /**
     * Append params to query statement
     * @param $statement
     * @param $param
     * @return string
     * @created 2020-02-26 LongTHK
     */
    private function appendQueryStatement(&$statement, $param)
    {
        return (!empty($param)) ?
            $statement .= '"' . $param . '",' :
            $statement .= 'NULL,';
    }

    /**
     * Get pay history
     * @return JsonResponse
     * @created 2020-02-26 LongTHK
     */
    public function getCardHistory()
    {
        return response()->json([
            'cardHistoryList' => DB::select($this->generateGetPaginatedCardHistoryQueryStatement(request())),
            'pagination' => [
                'currentPage' => request()->currentPage,
                'totalPage' => ceil(sizeof(DB::select($this->generateGetAllCardHistoryQueryStatement(request()))) / self::PER_PAGE_VALUE)
            ]
        ], 200);
    }

    /**
     * Generate query to get paginated card history list
     * @param $request
     * @return string
     * @created 2020-02-27 LongTHK
     */
    private function generateGetPaginatedCardHistoryQueryStatement($request)
    {
        // start query statement
        $statement = 'CALL sp_card_get_historydatetodate_with_pagination(';

        // check transaction id
        $this->appendQueryStatement($statement, $request->transactionId);

        // check username
        $this->appendQueryStatement($statement, $request->username);

        // check serial
        $this->appendQueryStatement($statement, $request->cardSerial);

        // check code
        $this->appendQueryStatement($statement, $request->cardCode);

        // check start-end date
        $dateRange = $this->getDateRange($request->timeRange);
        $this->appendQueryStatement($statement, $dateRange['startDate']);
        $this->appendQueryStatement($statement, $dateRange['endDate']);

        // check options
        $this->appendQueryStatement($statement, $request->transactionStatus);

        // check type card
        $this->appendQueryStatement($statement, $request->cardType);

        // check game id
        $this->appendQueryStatement($statement, $request->gameId);

        // check current page
        $this->appendQueryStatement($statement, $request->currentPage);

        // check per page value
        $this->appendQueryStatement($statement, self::PER_PAGE_VALUE);

        // end query statement
        $statement .= ')';

        // return statement
        return Str::replaceLast(',', '', $statement);
    }

    /**
     * Generate query to get all card history list
     * @param $request
     * @return string
     * @created 2020-02-27 LongTHK
     */
    private function generateGetAllCardHistoryQueryStatement($request)
    {
        // start query statement
        $statement = 'CALL sp_admin_card_get_historydatetodate(';

        // check transaction id
        $this->appendQueryStatement($statement, $request->transactionId);

        // check username
        $this->appendQueryStatement($statement, $request->username);

        // check serial
        $this->appendQueryStatement($statement, $request->cardSerial);

        // check code
        $this->appendQueryStatement($statement, $request->cardCode);

        // check start-end date
        $dateRange = $this->getDateRange($request->timeRange);
        $this->appendQueryStatement($statement, $dateRange['startDate']);
        $this->appendQueryStatement($statement, $dateRange['endDate']);

        // check options
        $this->appendQueryStatement($statement, $request->transactionStatus);

        // check type card
        $this->appendQueryStatement($statement, $request->cardType);

        // check game id
        $this->appendQueryStatement($statement, $request->gameId);

        // end query statement
        $statement .= ')';

        // return statement
        return Str::replaceLast(',', '', $statement);
    }

    /**
     * Export excel file
     * @created 2020-02-27 LongTHK
     */
    public function exportCardHistory()
    {
        return Excel::download(new CardHistoryExport(DB::select($this->generateGetAllCardHistoryQueryStatement(request()))), 'file.xlsx');
    }

    /**
     * Get pay history
     * @return JsonResponse
     * @created 2020-02-27 LongTHK
     */
    public function getPayHistory()
    {
        return response()->json([
            'payHistoryList' => DB::select($this->generateGetPaginatedPayHistoryQueryStatement(request())),
            'pagination' => [
                'currentPage' => request()->currentPage,
                'totalPage' => ceil(sizeof(DB::select($this->generateGetAllPayHistoryQueryStatement(request()))) / self::PER_PAGE_VALUE)
            ]
        ], 200);
    }

    /**
     * Generate query to get paginated pay history list
     * @return string
     * @created 2020-02-27 LongTHK
     */
    public function generateGetPaginatedPayHistoryQueryStatement($request)
    {
        // start query statement
        $statement = 'CALL sp_pay_get_historydatetodate_with_pagination(';

        // check transaction id
        $this->appendQueryStatement($statement, $request->transactionId);

        // check username
        $this->appendQueryStatement($statement, $request->username);

        // check role
        $this->appendQueryStatement($statement, $request->roleName);

        // check serial
        $this->appendQueryStatement($statement, $request->cardSerial);

        // check code
        $this->appendQueryStatement($statement, $request->cardCode);

        // check start-end date
        $dateRange = $this->getDateRange($request->timeRange);
        $this->appendQueryStatement($statement, $dateRange['startDate']);
        $this->appendQueryStatement($statement, $dateRange['endDate']);

        // check options
        $this->appendQueryStatement($statement, $request->transactionStatus);

        // check server
        $this->appendQueryStatement($statement, $request->serverId);

        // check type card
        $this->appendQueryStatement($statement, $request->cardType);

        // check game id
        $this->appendQueryStatement($statement, $request->gameId);

        // check current page
        $this->appendQueryStatement($statement, $request->currentPage);

        // check per page value
        $this->appendQueryStatement($statement, self::PER_PAGE_VALUE);

        // end query statement
        $statement .= ')';

        // return statement
        return Str::replaceLast(',', '', $statement);
    }

    /**
     * Generate query to get all pay history list
     * @return string
     * @created 2020-02-27 LongTHK
     */
    public function generateGetAllPayHistoryQueryStatement($request)
    {
        // start query statement
        $statement = 'CALL sp_pay_get_historydatetodate(';

        // check transaction id
        $this->appendQueryStatement($statement, $request->transactionId);

        // check username
        $this->appendQueryStatement($statement, $request->username);

        // check role
        $this->appendQueryStatement($statement, $request->roleName);

        // check serial
        $this->appendQueryStatement($statement, $request->cardSerial);

        // check code
        $this->appendQueryStatement($statement, $request->cardCode);

        // check start-end date
        $dateRange = $this->getDateRange($request->timeRange);
        $this->appendQueryStatement($statement, $dateRange['startDate']);
        $this->appendQueryStatement($statement, $dateRange['endDate']);

        // check options
        $this->appendQueryStatement($statement, $request->transactionStatus);

        // check server
        $this->appendQueryStatement($statement, $request->serverId);

        // check type card
        $this->appendQueryStatement($statement, $request->cardType);

        // check game id
        $this->appendQueryStatement($statement, $request->gameId);

        // end query statement
        $statement .= ')';

        // return statement
        return Str::replaceLast(',', '', $statement);
    }

    /**
     * Export excel file
     * @created 2020-02-27 LongTHK
     */
    public function exportPayHistory()
    {
        return Excel::download(new PayHistoryExport(DB::select($this->generateGetAllPayHistoryQueryStatement(request()))), 'file.xlsx');
    }

    /**
     * Get IAP history
     * @return JsonResponse
     * @created 2020-03-02 LongTHK
     */
    public function getIAPHistory()
    {
        return response()->json([
            'iapHistoryList' => DB::select($this->generateGetPaginatedIAPHistoryQueryStatement(request())),
            'pagination' => [
                'currentPage' => request()->currentPage,
                'totalPage' => ceil(sizeof(DB::select($this->generateGetAllIAPHistoryQueryStatement(request()))) / self::PER_PAGE_VALUE)
            ]
        ], 200);
    }

    /**
     * Generate query to get all IAP history list
     * @return string
     * @created 2020-03-02 LongTHK
     */
    public function generateGetPaginatedIAPHistoryQueryStatement($request)
    {
        // start query statement
        $statement = 'CALL sp_iap_get_historydatetodate_with_pagination(';

        // check transaction id
        $this->appendQueryStatement($statement, $request->transactionId);

        // check username
        $this->appendQueryStatement($statement, $request->username);

        // check server
        $this->appendQueryStatement($statement, $request->serverId);

        // check start-end date
        $dateRange = $this->getDateRange($request->timeRange);
        $this->appendQueryStatement($statement, $dateRange['startDate']);
        $this->appendQueryStatement($statement, $dateRange['endDate']);

        // check options
        $this->appendQueryStatement($statement, $request->transactionStatus);

        // check from-to amount
        $amountRange = $this->getAmountRange($request->amountFrom, $request->amountTo);
        $this->appendQueryStatement($statement, $amountRange['from']);
        $this->appendQueryStatement($statement, $amountRange['to']);

        // check game id
        $this->appendQueryStatement($statement, $request->gameId);

        // check OS
        $this->appendQueryStatement($statement, $request->os);

        // check current page
        $this->appendQueryStatement($statement, $request->currentPage);

        // check per page value
        $this->appendQueryStatement($statement, self::PER_PAGE_VALUE);

        // end query statement
        $statement .= ')';

        // return statement
        return Str::replaceLast(',', '', $statement);
    }

    /**
     * Get amount range
     * @param $amountFrom
     * @param $amountTo
     * @return array
     * @created 2020-03-02 LongTHK
     */
    private function getAmountRange($amountFrom, $amountTo)
    {
        // define default range
        $amountRange = [
            'from' => self::AMOUNT_FROM_VALUE,
            'to' => self::AMOUNT_TO_VALUE
        ];

        // check from-to amount
        if (!empty($amountFrom)) {
            $amountRange['from'] = $amountFrom;
        }
        // check from-to amount
        if (!empty($amountTo)) {
            $amountRange['to'] = $amountTo;
        }

        return $amountRange;
    }

    /**
     * Generate query to get all IAP history list
     * @return string
     * @created 2020-03-02 LongTHK
     */
    public function generateGetAllIAPHistoryQueryStatement($request)
    {
        // start query statement
        $statement = 'CALL sp_iap_get_historydatetodate(';

        // check transaction id
        $this->appendQueryStatement($statement, $request->transactionId);

        // check username
        $this->appendQueryStatement($statement, $request->username);

        // check server
        $this->appendQueryStatement($statement, $request->serverId);

        // check start-end date
        $dateRange = $this->getDateRange($request->timeRange);
        $this->appendQueryStatement($statement, $dateRange['startDate']);
        $this->appendQueryStatement($statement, $dateRange['endDate']);

        // check options
        $this->appendQueryStatement($statement, $request->transactionStatus);

        // check from-to amount
        $amountRange = $this->getAmountRange($request->amountFrom, $request->amountTo);
        $this->appendQueryStatement($statement, $amountRange['from']);
        $this->appendQueryStatement($statement, $amountRange['to']);

        // check game id
        $this->appendQueryStatement($statement, $request->gameId);

        // check OS
        $this->appendQueryStatement($statement, $request->os);

        // end query statement
        $statement .= ')';

        // return statement
        return Str::replaceLast(',', '', $statement);
    }

    /**
     * Export IAP history to excel file
     * @created 2020-03-02 LongTHK
     */
    public function exportIAPHistory()
    {
        return Excel::download(new IAPHistoryExport(DB::select($this->generateGetAllIAPHistoryQueryStatement(request()))), 'file.xlsx');
    }
}
