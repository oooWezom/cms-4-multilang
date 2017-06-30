<?php
    namespace Wezom\Modules\Catalog\Models;

    use Core\QB\DB;

    class CatalogTreeBrands extends \Core\Common {

        public static $table = 'catalog_tree_brands';

        /**
         * @param integer $catalog_tree_id - Group ID
         * @param null/integer $status - 0 or 1
         * @param null/string $sort
         * @param null/string $type - ASC or DESC. No $sort - no $type
         * @param null/integer $limit
         * @param null/integer $offset - no $limit - no $offset
         * @return object
         */
        public static function getRows($catalog_tree_id, $status = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL) {
            $result = DB::select()->from(static::$table)->where(static::$table.'.catalog_tree_id', '=', $catalog_tree_id);
            if( $status !== NULL ) {
                $result->where('status', '=', $status);
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
                if( $type !== NULL ) {
                    $result->offset($offset);
                }
            }
            return $result->find_all();
        }

    }
