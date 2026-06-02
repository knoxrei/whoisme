<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Legacy platform name (migration notice on login)
    |--------------------------------------------------------------------------
    */
    'legacy_name' => env('LEGACY_PLATFORM_NAME', ''),

    /*
    |--------------------------------------------------------------------------
    | Per-recipient SMTP timeout (seconds) for bulk mail
    |--------------------------------------------------------------------------
    */
    'bulk_mail_timeout' => (int) env('MAIL_SEND_TIMEOUT', 10),

    'bulk_mail_timeout_min' => (int) env('BULK_MAIL_TIMEOUT_MIN', 3),

    'bulk_mail_timeout_max' => (int) env('BULK_MAIL_TIMEOUT_MAX', 120),

];
