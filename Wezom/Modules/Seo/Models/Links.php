<?php
    namespace Wezom\Modules\Seo\Models;

    use Core\Arr;
    use Core\Message;

    class Links extends \Core\Common {

        public static $table = 'seo_links';
        public static $filters = [
            'name' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
            'link' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
        ];
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