<?php   
    
    return [
        // Orders
        'wezom/orders/index' => 'orders/orders/index',
        'wezom/orders/index/page/<page:[0-9]*>' => 'orders/orders/index',
        'wezom/orders/edit/<id:[0-9]*>' => 'orders/orders/edit',
        'wezom/orders/delete/<id:[0-9]*>' => 'orders/orders/delete',
        'wezom/orders/print/<id:[0-9]*>' => 'orders/orders/print',
        'wezom/orders/add_position/<id:[0-9]*>' => 'orders/orders/addPosition',
        'wezom/orders/add' => 'orders/orders/add',
        // Simple orders
        'wezom/simple/index' => 'orders/simple/index',
        'wezom/simple/index/page/<page:[0-9]*>' => 'orders/simple/index',
        'wezom/simple/edit/<id:[0-9]*>' => 'orders/simple/edit',
        'wezom/simple/delete/<id:[0-9]*>' => 'orders/simple/delete',
    ];
