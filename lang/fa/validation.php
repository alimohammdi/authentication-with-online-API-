<?php

return [
    'required' => 'وارد کردن :attribute الزامی است.',
    'string'   => ':attribute باید به صورت متن باشد.',
    'integer'  => ':attribute باید عدد صحیح باشد.',
    'numeric'  => ':attribute باید عدد باشد.',
    'email'    => ':attribute باید یک ایمیل معتبر باشد.',
    'confirmed' => ' رمز عبور با تاییدآن برابر نیست ',
    'min' => [
        'numeric' => ':attribute باید حداقل :min باشد.',
        'string'  => ':attribute باید حداقل :min کاراکتر باشد.',
        'file'    => 'حجم :attribute باید حداقل :min کیلوبایت باشد.',
             ],
    'max' => [
        'numeric' => ':attribute نمی‌تواند بیشتر از :max باشد.',
        'string'  => ':attribute نمی‌تواند بیشتر از :max کاراکتر باشد.',
        'file'    => 'حجم :attribute نمی‌تواند بیشتر از :max کیلوبایت باشد.',
    ],
    'file'   => ':attribute باید یک فایل معتبر باشد.',
    'mimes'  => ':attribute باید از نوع :values باشد.',
    'date'   => ':attribute باید یک تاریخ معتبر باشد.',
    'attributes' => [
        'waybill_number' => 'شماره بارنامه',
        'issue_date'     => 'تاریخ صدور',
        'base_fare'      => 'کرایه پایه',
        'tracking_code'  => 'کد رهگیری',
        'total_fare'     => 'کرایه کل',
        'file'           => 'فایل بارنامه',
        'mobile'         => 'شماره تماس',
        'passwordLogin' => 'رمز عبور',
        'password'      => 'رمز عبور',
        'mobile_login'  => 'شماره تماس',
        'mobile_register' => 'شماره تماس',
        'content'     => 'متن نظر'
    ],
];
