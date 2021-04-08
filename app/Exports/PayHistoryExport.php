<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayHistoryExport implements FromArray, WithHeadings, WithMapping
{
    /**
     * Pay history list
     * @var
     */
    protected $payHistoryList;

    /**
     * CardHistoryExport constructor.
     * @param array $payHistoryList
     * @created 2020-02-27 LongTHK
     */
    public function __construct(array $payHistoryList)
    {
        $this->payHistoryList = $payHistoryList;
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
            'Username',
            'Rolename',
            'Loại thẻ',
            'Mệnh giá',
            'Duy đổi',
            'Game',
            'Server',
            'Mã lỗi thẻ',
            'Thống báo lỗi nạp thẻ',
            'Mã lỗi nạp game',
            'Thông báo nạp game',
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
        return $this->payHistoryList;
    }

    /**
     * Data mapping
     * @param mixed $historyItem
     * @return array
     */
    public function map($historyItem): array
    {
        return [
            $historyItem->transaction_id,
            $historyItem->username,
            $historyItem->role,
            $historyItem->card_type,
            $historyItem->amount,
            $historyItem->gold,
            $historyItem->product_id,
            $historyItem->server_id,
            $historyItem->card_status,
            $historyItem->card_message,
            $historyItem->pay,
            '',
            $historyItem->create_date
        ];
    }
}
