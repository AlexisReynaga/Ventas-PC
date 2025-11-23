<?php

return [
    'base_url' => env('API_BASE_URL', 'http://localhost:8000/api/v1'),
    'admin_email' => env('API_ADMIN_EMAIL'),
    'admin_password' => env('API_ADMIN_PASSWORD'),
    'cache_ttl' => 3600, // segundos
];
