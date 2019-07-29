<?php

return [
    'title'=> 'Cria Conta',
    'dashboard_url' => '/',
    'logout_method' => 'GET',
    'logout_url' => 'logout',
    'login_url' => 'login/senhaunica',
    'menu' => [
        [
            'text' => 'Conta institucional',
            'url'  => '/institucional',
            'can'  => 'institutional',
        ],
    ]
];
