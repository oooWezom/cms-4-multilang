<?php
    namespace Wezom\Modules\Seo\Models;

    class Scripts extends \Core\Common {

        public static $table = 'seo_scripts';
        public static $rules = [];

        public static function valid($data = [])
        {
            static::$rules = [
                'name' => [
                    [
                        'error' => 'Название не может быть пустым!',
                        'key' => 'not_empty',
                    ],
                ],
            ];
            return parent::valid($data);
        }

    }