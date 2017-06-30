<?php
// Settings of images on the site
return [
    // Watermark path
    'watermark' => 'pic/logo.png',
    // Image types
    'types' => [
        'jpg', 'jpeg', 'png', 'gif',
    ],
    // Banners images
    'banners' => [
        [
            'path' => '',
            'width' => 483,
            'height' => 160,
            'resize' => 1,
            'crop' => 1,
        ],
    ],
    // Slider images
    'slider' => [
        [
            'path' => 'small',
            'width' => 200,
            'height' => 70,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'big',
            'width' => 1460,
            'height' => 500,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'original',
            'resize' => 0,
            'crop' => 0,
        ],
    ],
    // Blog images
    'blog' => [
        [
            'path' => 'small',
            'width' => 200,
            'height' => 160,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'big',
            'width' => 600,
            'height' => 400,
            'resize' => 1,
            'crop' => 0,
        ],
        [
            'path' => 'original',
            'resize' => 0,
            'crop' => 0,
        ],
    ],
    // News images
    'news' => [
        [
            'path' => 'small',
            'width' => 200,
            'height' => 160,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'big',
            'width' => 600,
            'height' => NULL,
            'resize' => 1,
            'crop' => 0,
        ],
        [
            'path' => 'original',
            'resize' => 0,
            'crop' => 0,
        ],
    ],
    // Articles images
    'articles' => [
        [
            'path' => 'small',
            'width' => 200,
            'height' => 160,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'big',
            'width' => 600,
            'height' => NULL,
            'resize' => 1,
            'crop' => 0,
        ],
        [
            'path' => 'original',
            'resize' => 0,
            'crop' => 0,
        ],
    ],
    // Catalog groups images
    'catalog_tree' => [
        [
            'path' => '',
            'width' => 240,
            'height' => 240,
            'resize' => 1,
            'crop' => 1,
        ],
    ],
    // Products images
    'catalog' => [
        [
            'path' => 'small',
            'width' => 60,
            'height' => 60,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'medium',
            'width' => 232,
            'height' => 195,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'big',
            'width' => 678,
            'height' => 520,
            'resize' => 1,
            'crop' => 0,
        ],
        [
            'path' => 'original',
            'resize' => 0,
            'crop' => 0,
        ],
    ],
    'gallery' => [
        [
            'path' => '',
            'width' => 200,
            'height' => 200,
            'resize' => 1,
            'crop' => 1,
        ],
    ],
    'gallery_images' => [
        [
            'path' => 'small',
            'width' => 200,
            'height' => 200,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'medium',
            'width' => 350,
            'height' => 350,
            'resize' => 1,
            'crop' => 1,
        ],
        [
            'path' => 'big',
            'width' => 1280,
            'height' => 1024,
            'resize' => 1,
            'crop' => 0,
        ],
        [
            'path' => 'original',
            'resize' => 0,
            'crop' => 0,
        ],
    ],
];