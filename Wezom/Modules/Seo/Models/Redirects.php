<?php
    namespace Wezom\Modules\Seo\Models;

    class Redirects extends \Core\Common {

        public static $table = 'seo_redirects';
        public static $filters = [
            'link_from' => [
                'action' => 'LIKE',
            ],
            'link_to' => [
                'action' => 'LIKE',
            ],
            'type' => [
                'action' => '=',
            ],
        ];

    }