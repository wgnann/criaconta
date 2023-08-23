<?php

return [
    'title'=> 'Cria Conta',
    'dashboard_url' => '/',
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'menu' => [
        [
            'text' => 'Conta pessoal',
            'url' => '/accounts',
            'can' => '',
        ],
        [
            'text' => 'Conta institucional',
            'url'  => '/institucional',
            'can'  => 'institutional',
        ],
        [
            'text' => 'Conta local',
            'url'  => '/local',
            'can'  => 'institutional',
        ],
        [
            'text' => 'Recuperar senha',
            'url' => '/password',
            'can' => '',
        ],
    ]
    ];
