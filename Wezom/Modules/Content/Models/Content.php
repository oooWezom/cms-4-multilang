<?php
    namespace Wezom\Modules\Content\Models;
    
    class Content extends \Core\CommonI18n {
    
        public static $table = 'content';
        public static $rules;
        public static $rulesI18n;
    
        public static function valid($post) {
    
            static::$rulesI18n = [
                'name' => [
                    [
                        'error' => __('Название не может быть пустым! (:lang)'),
                        'key' => 'not_empty',
                    ],
                ],
            ];
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