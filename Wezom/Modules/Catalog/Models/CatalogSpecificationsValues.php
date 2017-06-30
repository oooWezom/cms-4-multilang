<?php
    namespace Wezom\Modules\Catalog\Models;

    use Core\QB\DB;

    class CatalogSpecificationsValues extends \Core\Common {

        public static $table = 'catalog_specifications_values';

        /**
         * @param integer $item_id - Item ID
         * @param null/integer $status - 0 or 1
         * @param null/string $sort
         * @param null/string $type - ASC or DESC. No $sort - no $type
         * @param null/integer $limit
         * @param null/integer $offset - no $limit - no $offset
         * @return object
         */
        public static function getRows($item_id) {
            $result = DB::select(static::$table.'.specification_id', static::$table.'.specification_value_id', 'specifications.type_id')
                ->from(static::$table)
                ->join('specifications')->on(static::$table.'.specification_id', '=', 'specifications.id')
                ->where(static::$table.'.catalog_id', '=', $item_id)
                ->where('specifications.status', '=', 1);
            return $result->find_all();
        }

        public static function getRowsAliases($item_id) {
            $result = DB::select(static::$table.'.specification_alias', static::$table.'.specification_value_alias', 'specifications.type_id')
                ->from(static::$table)
                ->join('specifications')->on(static::$table.'.specification_alias', '=', 'specifications.alias')
                ->where(static::$table.'.catalog_id', '=', $item_id)
                ->where('specifications.status', '=', 1);
            return $result->find_all();
        }

        public static function getRowsByObjects($items) {
            $arr = [0];
            foreach( $items AS $obj ) {
                $arr[] = $obj->id;
            }
            $result = DB::select(static::$table.'.specification_id', static::$table.'.specification_value_id', 'specifications.type_id', [static::$table.'.catalog_id', 'id'])
                ->from(static::$table)
                ->join('specifications')->on(static::$table.'.specification_id', '=', 'specifications.id')
                ->where(static::$table.'.catalog_id', 'IN', $arr)
                ->where('specifications.status', '=', 1);
            return $result->find_all();
        }

    }
