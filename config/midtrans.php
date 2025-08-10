<?php

return [
  'server_key' => env('MIDTRANS_SERVER_KEY'),
  'client_key' => env('MIDTRANS_CLIENT_KEY'),
  'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
  'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
  'is_3ds' => env('MIDTRANS_IS_3DS', true),

  // Snap URLs - Fixed the URL structure
  'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js',
];
