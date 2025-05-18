<?php
// config/midtrans.php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_ENVIRONMENT', 'sandbox') === 'production',
    'is_sanitized' => true, // Escape HTML entities in item names etc.
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];