<?php
    namespace Wezom\Modules\Catalog\Models;

    use Core\Arr;
    use Core\Message;
    use Core\QB\DB;

    class Comments extends \Core\Common {

        public static $table = 'catalog_comments';
        public static $filters = [
            'item_name' => [
                'table' => 'catalog_i18n',
                'action' => 'LIKE',
                'field' => 'name',
            ],
        ];
        public static $rules = [];

        public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL) {
            $result = DB::select('catalog_comments.*', ['catalog_i18n.name', 'item_name'], ['catalog.alias', 'item_alias'])
                ->from(static::$table)
                ->join('catalog', 'LEFT')
                    ->on('catalog.id', '=', 'catalog_comments.catalog_id')
                ->join('catalog_i18n', 'LEFT')
                    ->on('catalog.id', '=', 'catalog_i18n.row_id')
                    ->on('catalog_i18n.language', '=', DB::expr('"'.\I18n::$default_lang_backend.'"'));
            $result = parent::setFilter($result);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.date', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.date', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            if( $sort !== NULL ) {
                if( $type !== NULL ) {
                    $result->order_by($sort, $type);
                } else {
                    $result->order_by($sort);
                }
            }
            if( $limit !== NULL ) {
                $result->limit($limit);
            }
            if( $offset !== NULL ) {
                $result->offset($offset);
            }
            return $result->find_all();
        }

        public static function countRows($status = NULL, $date_s = NULL, $date_po = NULL) {
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
            return $result->count_all();
        }

        public static function valid($data = [])
        {
            static::$rules = [
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
                'catalog_id' => [
                    [
                        'error' => __('Выберите товар!'),
                        'key' => 'digit',
                    ],
                ],
            ];
            return parent::valid($data);
        }

    }