<?php   
    
    return [
        // System pages
        'wezom/control/index' => 'content/control/index',
        'wezom/control/index/page/<page:[0-9]*>' => 'content/control/index',
        'wezom/control/edit/<id:[0-9]*>' => 'content/control/edit',
        // Content
        'wezom/content/index' => 'content/content/index',
        'wezom/content/index/page/<page:[0-9]*>' => 'content/content/index',
        'wezom/content/edit/<id:[0-9]*>' => 'content/content/edit',
        'wezom/content/delete/<id:[0-9]*>' => 'content/content/delete',
        'wezom/content/add' => 'content/content/add',
        // News
        'wezom/news/index' => 'content/news/index',
        'wezom/news/index/page/<page:[0-9]*>' => 'content/news/index',
        'wezom/news/edit/<id:[0-9]*>' => 'content/news/edit',
        'wezom/news/delete/<id:[0-9]*>' => 'content/news/delete',
        'wezom/news/delete_image/<id:[0-9]*>' => 'content/news/deleteImage',
        'wezom/news/add' => 'content/news/add',
        // Articles
        'wezom/articles/index' => 'content/articles/index',
        'wezom/articles/index/page/<page:[0-9]*>' => 'content/articles/index',
        'wezom/articles/edit/<id:[0-9]*>' => 'content/articles/edit',
        'wezom/articles/delete/<id:[0-9]*>' => 'content/articles/delete',
        'wezom/articles/delete_image/<id:[0-9]*>' => 'content/articles/deleteImage',
        'wezom/articles/add' => 'content/articles/add',
    ];