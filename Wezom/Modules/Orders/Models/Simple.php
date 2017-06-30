<?php
    namespace Wezom\Modules\Orders\Models;

    use Core\QB\DB;
    use Core\Arr;
    use Core\Message;

    class Simple extends \Core\Common {

        public static $table = 'orders_simple';

        public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL) {
            $result = DB::select('orders_simple.*', ['catalog_i18n.name', 'item_name'], ['catalog.alias', 'item_alias'])
                ->from(static::$table)
                ->join('catalog', 'LEFT')
                        ->on('catalog.id', '=', 'orders_simple.catalog_id')
                ->join('catalog_i18n', 'LEFT')
                        ->on('catalog_i18n.row_id', '=', 'catalog.id')
                        ->on('catalog_i18n.language', '=', DB::expr('"'.\I18n::$default_lang.'"'));
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.created_at', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            $result->group_by(static::$table.'.id');
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
                $result->where(static::$table . '.created_at', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            return $result->count_all();
        }

    }