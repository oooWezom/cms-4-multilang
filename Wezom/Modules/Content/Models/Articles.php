<?php
    namespace Wezom\Modules\Content\Models;

    class Articles extends \Core\CommonI18n {

        public static $table = 'articles';
        public static $image = 'articles';
        public static $filters = [
            'name' => [
                'table' => 'articles_i18n',
                'action' => 'LIKE',
            ],
        ];
        public static $rulesI18n;
        public static $rules;

        public static function valid($post)
        {
            static::$rulesI18n = array(
                'name' => array(
                    array(
                        'error' => __('Название не может быть пустым! (:lang)'),
                        'key' => 'not_empty',
                    ),
                ),
            );
            static::$rules = [
                'alias' => [
                    [
                        'error' => __('Алиас не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                    [
                        'error' => __('Алиас должен содержать только латинские буквы в нижнем регистре, цифры, "-" или "_"!'),
                        'key' => 'regex',
                        'value' => '/^[a-z0-9\-_]*$/',
                    ],
                ],
            ];
            
            return parent::valid($post);
        }

    }