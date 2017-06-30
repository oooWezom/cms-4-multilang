<?php
    namespace Wezom\Modules\Subscribe\Models;

    use Core\Arr;
    use Core\Message;

    class Subscribers extends \Core\Common {

        public static $table = 'subscribers';
        public static $filters = [
            'email' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
        ];
        public static $rules = [];

        public static function valid($data = [])
        {
            static::$rules = [
                'email' => [
                    [
                        'error' => __('Поле "E-Mail" введено некорректно!'),
                        'key' => 'email',
                    ],
                ],
            ];
            return parent::valid($data);
        }
    }