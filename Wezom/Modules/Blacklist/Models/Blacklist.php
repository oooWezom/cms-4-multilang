<?php
    namespace Wezom\Modules\Blacklist\Models;

    class Blacklist extends \Core\Common {

        public static $table = 'blacklist';
        public static $filters = [
            'ip' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
        ];
        public static $rules = [];

        public static function valid($data = []) {
            static::$rules = [
                'ip' => [
                    [
                        'error' => __('IP не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                ],
            ]; ;
            return parent::valid($data);
        }

    }