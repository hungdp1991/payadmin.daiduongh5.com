<?php
return [
    'ROOT_CATEGORY_ID' => 0,

    'PAYMENT_TYPE_LIST' => [
        [
            'caption' => 'Thanh toán dùng ví',
            'value' => 'wallet'
        ],
        [
            'caption' => 'Thanh toán không dùng ví',
            'value' => 'none_wallet'
        ],
        [
            'caption' => 'Đang bảo trì / Chưa phát hành',
            'value' => 'maintenance'
        ],
        [
            'caption' => 'Liên kết',
            'value' => 'outer_link'
        ]
    ],

    'GOLD_TYPES_LIST' => [
        [
            'caption' => 'Thẻ tháng',
            'value' => '1'
        ],
        [
            'caption' => 'Thẻ thường',
            'value' => '2'
        ],
        [
            'caption' => 'Quà',
            'value' => '3'
        ],
    ],

    'SERVER_STATUS_LIST' => [
        [
            'caption' => 'Server mới',
            'value' => 'new'
        ],
        [
            'caption' => 'Server hot',
            'value' => 'hot'
        ],
        [
            'caption' => 'Bình thường',
            'value' => 'normal'
        ],
        [
            'caption' => 'Bảo trì',
            'value' => 'maintenance'
        ],
    ],

    'TRANSACTION_STATUS_LIST' => [
        [
            'caption' => 'Success',
            'value' => '1'
        ],
        [
            'caption' => 'Fail',
            'value' => '-1'
        ],
    ],

    'OS_LIST' => [
        [
            'caption' => 'Android',
            'value' => 'android'
        ],
        [
            'caption' => 'iOS',
            'value' => 'ios'
        ]
    ]
];
