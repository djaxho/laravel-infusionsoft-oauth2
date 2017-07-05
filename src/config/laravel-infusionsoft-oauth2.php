<?php 

return [

    'ISDK_API_HOST' => env('ISDK_API_HOST', false),
    'ISDK_API_CLIENTID' => env('ISDK_API_CLIENTID', false),
    'ISDK_API_CLIENTSECRET' => env('ISDK_API_CLIENTSECRET', false),
    'ISDK_API_REDIRECT' => env('ISDK_API_REDIRECT', false),
    'ISDK_API_TOKENTABLE' => env('ISDK_API_TOKENTABLE', 'is_oauth2_token')
];