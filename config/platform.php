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

];
