<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IAPHistoryExport implements FromArray, WithHeadings, WithMapping
{
    /**
     * Pay history list
     * @var
     */
    protected $iapHistoryList;

    /**
     * CardHistoryExport constructor.
     * @param array $iapHistoryList
     * @created 2020-03-02 LongTHK
     */
    public function __construct(array $iapHistoryList)
    {
        $this->iapHistoryList = $iapHistoryList;
    }

    /**
     * Define headings
     * @return array
     * @created 2020-02-27 LongTHK
     */
    public function headings(): array
    {
        return [
            'Mã giao dịch',
            'UUID',
            'Username',
            'Mệnh giá',
            'Duy đổi',
            'Payload',
            'Game',
            'Server',
            'OS',
            'Trạng thái',
            'Ngày thực hiện'
        ];
    }

    /**
     * Array for exporting
     * @return array
     * @created 2020-02-27 LongTHK
     */
    public function array(): array
    {
        return $this->iapHistoryList;
    }

    /**
     * Map data
     * @param mixed $historyItem
     * @return array
     */
    public function map($historyItem): array
    {
        return [
            $historyItem->transaction_id,
            $historyItem->uid,
            $historyItem->username,
            $historyItem->order_vnd,
            $historyItem->order_amount,
            $historyItem->payload,
            $historyItem->agent,
            $historyItem->server_id,
            $historyItem->os,
            $historyItem->status,
            $historyItem->created_at
        ];
    }
}
