<?php
    namespace Wezom\Modules\Menu\Models;

    use Core\Arr;
    use Core\Message;

    class Menu extends \Core\CommonI18n {

        public static $table = 'sitemenu';
        public static $rules = [];

        public static function valid($data = [])
        {
            static::$rulesI18n = [
                'name' => [
                    [
                        'error' => __('Название не может быть пустым! (:lang)'),
                        'key' => 'not_empty',
                    ],
                ],
            ];
            static::$rules = [
                'url' => [
                    [
                        'error' => __('Ссылка не может быть пустой!'),
                        'key' => 'not_empty',
                    ],
                    [
                        'error' => __('Ссылка должна содержать только латинские буквы в нижнем регистре, цифры, "/", "?", "&", "=", "-" или "_"!'),
                        'key' => 'regex',
                        'value' => '/^[a-z0-9\-_\/\?\=\&]*$/',
                    ],
                ],
            ];
            return parent::valid($data);
        }

    }