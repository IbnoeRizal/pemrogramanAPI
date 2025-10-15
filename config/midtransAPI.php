<?php

return [
    "server_key" => env("MIDTRANS_SERVER_KEY"),
    "client_key" => env("MIDTRANS_CLIENT_KEY"),
    "is_production" => env("MIDTRANS_ISPRODUCTION",false),
    "merchant_id" => env("MIDTRANS_MERCHANT_ID"),
    "is_sanitized" => env("MIDTRANS_ISSANITIZED",true),
    "is3ds" => env("MIDTRANS_IS3DS",true),
];
