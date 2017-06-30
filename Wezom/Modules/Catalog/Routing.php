<?php   
    
    return [
        // Test
        'wezom/test/<id:[0-9]*>' => 'catalog/items/test',
        // Crop
        'wezom/crop/<id:[0-9]*>' => 'catalog/items/crop',
        // Groups
        'wezom/groups/index' => 'catalog/groups/index',
        'wezom/groups/index/page/<page:[0-9]*>' => 'catalog/groups/index',
        'wezom/groups/edit/<id:[0-9]*>' => 'catalog/groups/edit',
        'wezom/groups/delete/<id:[0-9]*>' => 'catalog/groups/delete',
        'wezom/groups/delete_image/<id:[0-9]*>' => 'catalog/groups/deleteImage',
        'wezom/groups/add' => 'catalog/groups/add',
        // Items
        'wezom/items/index' => 'catalog/items/index',
        'wezom/items/index/page/<page:[0-9]*>' => 'catalog/items/index',
        'wezom/items/edit/<id:[0-9]*>' => 'catalog/items/edit',
        'wezom/items/delete/<id:[0-9]*>' => 'catalog/items/delete',
        'wezom/items/add' => 'catalog/items/add',
        // Brands
        'wezom/brands/index' => 'catalog/brands/index',
        'wezom/brands/index/page/<page:[0-9]*>' => 'catalog/brands/index',
        'wezom/brands/edit/<id:[0-9]*>' => 'catalog/brands/edit',
        'wezom/brands/delete/<id:[0-9]*>' => 'catalog/brands/delete',
        'wezom/brands/delete_image/<id:[0-9]*>' => 'catalog/brands/deleteImage',
        'wezom/brands/add' => 'catalog/brands/add',
        // Models
        'wezom/models/index' => 'catalog/models/index',
        'wezom/models/index/page/<page:[0-9]*>' => 'catalog/models/index',
        'wezom/models/edit/<id:[0-9]*>' => 'catalog/models/edit',
        'wezom/models/delete/<id:[0-9]*>' => 'catalog/models/delete',
        'wezom/models/add' => 'catalog/models/add',
        // Specifications
        'wezom/specifications/index' => 'catalog/specifications/index',
        'wezom/specifications/index/page/<page:[0-9]*>' => 'catalog/specifications/index',
        'wezom/specifications/edit/<id:[0-9]*>' => 'catalog/specifications/edit',
        'wezom/specifications/delete/<id:[0-9]*>' => 'catalog/specifications/delete',
        'wezom/specifications/add' => 'catalog/specifications/add',
        // ItemsComments
        'wezom/comments/index' => 'catalog/comments/index',
        'wezom/comments/index/page/<page:[0-9]*>' => 'catalog/comments/index',
        'wezom/comments/add' => 'catalog/comments/add',
        'wezom/comments/edit/<id:[0-9]*>' => 'catalog/comments/edit',
        'wezom/comments/delete/<id:[0-9]*>' => 'catalog/comments/delete',
        // Questions
        'wezom/questions/index' => 'catalog/questions/index',
        'wezom/questions/index/page/<page:[0-9]*>' => 'catalog/questions/index',
        'wezom/questions/edit/<id:[0-9]*>' => 'catalog/questions/edit',
        'wezom/questions/delete/<id:[0-9]*>' => 'catalog/questions/delete',
    ];