<?php
    namespace Wezom\Modules\Catalog\Models;

    use Core\QB\DB;

    class Brands extends \Core\CommonI18n {

        public static $table = 'brands';
        public static $tableI18n = 'brands_i18n';
        public static $filters = [
            'name' => [
                'table' => 'brands_i18n',
                'action' => 'LIKE',
            ],
        ];
        public static $rules = [];


        /**
         * Get brands that communicate with current group
         * @param null|integer $parent_id - Group ID
         * @return object
         */
        public static function getGroupRows($parent_id = NULL) {
            $result = DB::select( static::$tableI18n.'.*', static::$table.'.*')
                ->from(static::$table)
                ->join(static::$tableI18n)
                    ->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
                ->join('catalog_tree_brands')->on('catalog_tree_brands.brand_id', '=', static::$table.'.id')
                ->where(static::$tableI18n.'.language', '=', \I18n::$lang);
            if( $parent_id !== NULL ) {
                $result->where('catalog_tree_brands.catalog_tree_id', '=', $parent_id);
            }
            return $result->order_by(static::$tableI18n.'.name', 'ASC')->group_by(static::$table.'.id')->find_all();
        }

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
                'alias' => [
                    [
                        'error' => __('Алиас не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                    [
                        'error' => __('Алиас должен содержать только латинские буквы в нижнем регистре, цифры!'),
                        'key' => 'regex',
                        'value' => '/^[a-z0-9]*$/',
                    ],
                ],
            ];
            return parent::valid($data);
        }

    }