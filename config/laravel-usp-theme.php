<?php

$right_menu = [
    [
        // menu utilizado para views da biblioteca senhaunica-socialite.
        'key' => 'senhaunica-socialite',
    ],
    [
        'text' => '<i class="fas fa-hard-hat"></i>',
        'title' => 'Logs',
        'target' => '_blank',
        'url' => config('app.url').'/logs',
        'align' => 'right',
        'can' => 'admin',
    ],
];

$menu = [
    [
        'text' => 'Conta pessoal',
        'url' => '/accounts',
        'can' => 'user',
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
        'text' => 'Minhas contas',
        'url' => '/accounts/list',
        'can' => 'institutional',
    ],
    [
        'text' => 'Recuperar senha',
        'url' => '/accounts',
        'can' => 'user',
    ],
    [
        'text' => '<span class="text-danger">Todas as contas</span>',
        'url' => '/accounts/list-admin',
        'can' => 'admin',
    ],
];

return [
    'title'=> config('app.name'),
    'app_url' => config('app.url'),
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'menu' => $menu,
    'right_menu' => $right_menu,
];

