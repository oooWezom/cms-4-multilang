<?php   
    
    return [
        // Subscribe
        'wezom/subscribe/index' => 'subscribe/subscribe/index',
        'wezom/subscribe/index/page/<page:[0-9]*>' => 'subscribe/subscribe/index',
        'wezom/subscribe/send' => 'subscribe/subscribe/send',
        // Subscribers
        'wezom/subscribers/index' => 'subscribe/subscribers/index',
        'wezom/subscribers/index/page/<page:[0-9]*>' => 'subscribe/subscribers/index',
        'wezom/subscribers/edit/<id:[0-9]*>' => 'subscribe/subscribers/edit',
        'wezom/subscribers/delete/<id:[0-9]*>' => 'subscribe/subscribers/delete',
        'wezom/subscribers/add' => 'subscribe/subscribers/add',
    ];
