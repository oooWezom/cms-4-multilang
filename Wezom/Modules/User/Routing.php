<?php   
    
    return [
        // Auth
        'wezom/auth/login' => 'user/auth/login',
        'wezom/auth/edit' => 'user/auth/edit',
        'wezom/auth/logout' => 'user/auth/logout',
        // User
        'wezom/users/index' => 'user/users/index',
        'wezom/users/index/page/<page:[0-9]*>' => 'user/users/index',
        'wezom/users/add' => 'user/users/add',
        'wezom/users/edit/<id:[0-9]*>' => 'user/users/edit',
        'wezom/users/delete/<id:[0-9]*>' => 'user/users/delete',
        // Admins
        'wezom/admins/index' => 'user/admins/index',
        'wezom/admins/index/page/<page:[0-9]*>' => 'user/admins/index',
        'wezom/admins/add' => 'user/admins/add',
        'wezom/admins/edit/<id:[0-9]*>' => 'user/admins/edit',
        'wezom/admins/delete/<id:[0-9]*>' => 'user/admins/delete',
        // Roles
        'wezom/roles/index' => 'user/roles/index',
        'wezom/roles/edit/<id:[0-9]*>' => 'user/roles/edit',
        'wezom/roles/add' => 'user/roles/add',
        'wezom/roles/delete/<id:[0-9]*>' => 'user/roles/delete',
    ];
