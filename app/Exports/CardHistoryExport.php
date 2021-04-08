<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CardHistoryExport implements FromArray, WithHeadings, WithMapping
{
    /**
     * Pay history list
     * @var
     */
    protected $cardHistoryList;

    /**
     * CardHistoryExport constructor.
     * @param array $cardHistoryList
     * @created 2020-02-27 LongTHK
     */
    public function __construct(array $cardHistoryList)
    {
        $this->cardHistoryList = $cardHistoryList;
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
            'Loại thẻ',
            'Game',
            'Mệnh giá',
            'Duy đổi',
            'Username',
            'Mã lỗi thẻ',
            'Thông báo',
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
        return $this->cardHistoryList;
    }

    public function map($historyItem): array
    {
        return [
            $historyItem->transaction_id,
            $historyItem->card_type,
            $historyItem->product_id,
            $historyItem->amount,
            $historyItem->gold,
            $historyItem->username,
            $historyItem->card_status,
            $historyItem->card_message,
            $historyItem->create_date
        ];
    }
}
