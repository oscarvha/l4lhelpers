<?php

return [

    'ip_lookup' => [

        'persist' => true,

        'mode' => 'override',
        // values:
        // - insert   →  insert only
        // - override → override the register if exists

        'refresh_ttl' => 604800,

        'spam_analysis' => [
            'enabled' => true,
            'api_key' => '<API_KEY_HERE>',
        ],

        'limits' => [
            'by_ip' => 3,
            'minutes' => 60,
        ],

    ],
];


