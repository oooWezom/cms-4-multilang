<?php
    namespace Wezom\Modules\Catalog\Models;

    use Core\QB\DB;

    class CatalogImages extends \Core\Common {

        public static $table = 'catalog_images';

        /**
         * Get value for field 'sort' for new image
         * @param integer $parent_id - Item ID
         * @return int
         */
        public static function getSortForThisParent($parent_id) {
            $row = DB::select([DB::expr('MAX('.static::$table.'.sort)'), 'max'])
                ->from(static::$table)
                ->where(static::$table.'.catalog_id', '=', $parent_id)
                ->find();
            if( !$row ) {
                return 0;
            }
            return (int) $row->max + 1;
        }


        /**
         * Check for existence of main photo
         * @param integer $parent_id - Item ID
         * @return int
         */
        public static function issetMain($parent_id) {
            return DB::select([DB::expr('COUNT('.static::$table.'.id)'), 'count'])
                ->from(static::$table)
                ->where(static::$table.'.catalog_id', '=', $parent_id)
                ->where(static::$table.'.main', '=', '1')
                ->count_all();
        }

        public static function getRows($item_id, $status = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL, $filter = true) {
            $result = DB::select()->from(static::$table);
            $result->where(static::$table.'.catalog_id', '=', $item_id);
            if( $status !== NULL ) {
                $result->where('status', '=', $status);
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
            $result->order_by('id', 'DESC');
            if( $limit !== NULL ) {
                $result->limit($limit);
                if( $offset !== NULL ) {
                    $result->offset($offset);
                }
            }
            return $result->find_all();
        }


    }
