<?php

return [
    'types'               => [
        'deposit'  => 'واریز',
        'withdraw' => 'برداشت',
    ],
    'insufficientBalance' => 'موجودی حساب کافی نیست.',
    'deposit'             => [
        'success' => 'با موفقیت مقدار :quantity به کیف پول کاربر :user واریز شد. مانده حساب کاربر : :total_quantity',
    ],
    'withdraw'            => [
        'success' => 'با موفقیت مقدار :quantity از کیف پول کاربر :user درخواست شد. مانده حساب کاربر : :total_quantity',
    ],
    'properties'          => [
        'payer'                => 'پرداخت کننده',
        'receiver'             => 'دریافت کننده',
        'title'                => 'عنوان طرح',
        'transactionable_id'   => 'آیدی منبع',
        'transactionable_type' => 'نوع منبع',
        'quantity'             => 'مقدار',
        'status'               => 'وضعیت',
        'type'                 => 'نوع تراکنش',
        'user_id'              => 'ایدی کاربر',
        'admin_id'             => 'آیدی ادمین',
        'created_at'           => 'تاریخ',
        'meta'                 => 'متا',
        'reference'            => 'کد پیگیری',
        'gateway'              => 'درگاه',
        'callback_url'         => 'آدرس بازگشتی'
    ]
];
