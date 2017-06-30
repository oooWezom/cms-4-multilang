<?php
    namespace Wezom\Modules\Reviews\Models;

    use Core\QB\DB;

    class Reviews extends \Core\Common {

        public static $table = 'reviews';
        public static $filters = [
            'name' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
            'ip' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
        ];
        public static $rules = [];

        public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL, $filter = true) {
            $result = DB::select()->from(static::$table);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.date', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.date', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            if($filter) {
                $result = parent::setFilter($result);
            }
            if( $sort !== NULL ) {
                if( $type !== NULL ) {
                    $result->order_by($sort, $type);
                } else {
                    $result->order_by($sort);
                }
            }
            $result->order_by('id', 'DESC');
            if( $limit !== NULL ) {
                $result->limit($limit);
                if( $offset !== NULL ) {
                    $result->offset($offset);
                }
            }
            return $result->find_all();
        }

        public static function countRows($status = NULL, $date_s = NULL, $date_po = NULL, $filter = true) {
            $result = DB::select([DB::expr('COUNT('.static::$table.'.id)'), 'count'])->from(static::$table);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.date', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.date', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            if($filter) {
                $result = parent::setFilter($result);
            }
            return $result->count_all();
        }

        public static function valid($data = [])
        {
            static::$rules = [
                'name' => [
                    [
                        'error' => __('Название новости не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                ],
                'text' => [
                    [
                        'error' => __('Отзыв не может быть пустым!'),
                        'key' => 'not_empty',
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
            return parent::valid($data);
        }

    }