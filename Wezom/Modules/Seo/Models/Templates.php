<?php
    namespace Wezom\Modules\Seo\Models;

    class Templates extends \Core\CommonI18n {

        public static $table = 'seo_templates';
        public static $rules = [];
        public static $rulesI18n;

        public static function valid($data = [])
        {
            static::$rulesI18n = [
                'h1' => [
                    [
                        'error' => __('H1 не может быть пустым! (:lang)'),
                        'key' => 'not_empty',
                    ],
                ],
                'title' => [
                    [
                        'error' => __('Title не может быть пустым! (:lang)'),
                        'key' => 'not_empty',
                    ],
                ],
            ];
            
            return parent::valid($data);
        }

    }