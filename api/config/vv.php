<?php

return [
    'name' => 'VV',
    'host' => '172.19.0.2:8888',
    'token' => env('VV_TOKEN', ''),
    'routes' => [
        'register' => '%s/register/%s/%d',
        'preparation' => '%s/preparation',
    ],
    'post_back_url' => 'https://dev.admin.docudots.com/esimna'
];
