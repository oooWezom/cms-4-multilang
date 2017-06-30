<?php
    namespace Wezom\Modules\Content\Models;

    use Core\Arr;
    use Core\Message;
    use Core\QB\DB;

    class News extends \Core\CommonI18n {

        public static $table = 'news';
        public static $image = 'news';
        public static $filters = [
            'name' => [
                'table' => 'news_i18n',
                'action' => 'LIKE',
            ],
        ];
        public static $rulesI18n;
        public static $rules;

        public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL, $filter=true) {
            if(!static::$tableI18n) {
                static::$tableI18n = static::$table.'_i18n';
            }
            if ($sort !== null and sizeof(explode('.',$sort))<2) {
                $sort = static::$table.'.'.$sort;
            }
             $result = DB::select(
                static::$tableI18n.'.*',
                static::$table.'.*'
                )
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
                ->where(static::$tableI18n.'.language', '=', \I18n::$default_lang);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.date', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.date', '<=', $date_po + 24 * 60 * 60 - 1);
            }
			
			if( $filter ) {
                $result = static::setFilter($result);
            }
			
            if( $sort !== NULL ) {
                if( $type !== NULL ) {
                    $result->order_by($sort, $type);
                } else {
                    $result->order_by($sort);
                }
            }
            $result->order_by(static::$table.'.id', 'DESC');
            if( $limit !== NULL ) {
                $result->limit($limit);
            }
            if( $offset !== NULL ) {
                $result->offset($offset);
            }
            return $result->find_all();
        }

        public static function countRows($status = NULL, $date_s = NULL, $date_po = NULL, $filter=true) {
            if(!static::$tableI18n) {
                static::$tableI18n = static::$table.'_i18n';
            }
            $result = DB::select(array(DB::expr('COUNT('.static::$table.'.id)'), 'count'))
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
                ->where(static::$tableI18n.'.language', '=', \I18n::$default_lang);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.date', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.date', '<=', $date_po + 24 * 60 * 60 - 1);
            }
			
			if( $filter ) {
                $result = static::setFilter($result);
            }
			
            return $result->count_all();
        }

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
                'date' => [
                    [
                        'error' => __('Дата не может быть пустой!'),
                        'key' => 'not_empty',
                    ],
                    [
                        'error' => __('Укажите правильную дату!'),
                        'key' => 'date',
                    ],
                ],
            ];
            
            return parent::valid($post);
        }

    }