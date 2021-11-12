<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Maximum Number Of OTP requests
    |--------------------------------------------------------------------------
    |
    | This value is the maximum number of requests that are allowed per hour.
    |
    */

    'maximum_otp_requests_per_hour' => 3,

    /*
      |--------------------------------------------------------------------------
      | OTP Time To Live
      |--------------------------------------------------------------------------
      |
      | This value is the time it takes for the OTP to expire after it is issued.
      | in seconds
      |
      */

    'otp_ttl' => 30,


    /*
      |--------------------------------------------------------------------------
      | OTP No Reset Time
      |--------------------------------------------------------------------------
      |
      | This value is the time in which a new ot request will not require the
      | generation of a new token in seconds
      |
      */

    'otp_no_reset_time' => 300,
];
