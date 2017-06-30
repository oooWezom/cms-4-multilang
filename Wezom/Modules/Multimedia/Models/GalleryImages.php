<?php
    namespace Wezom\Modules\Multimedia\Models;

    use Core\QB\DB;

    class GalleryImages extends \Core\Common {

        public static $table = 'gallery_images';
        public static $image = 'gallery_images';

        public static function getSortForThisParent($parent_id) {
            $row = DB::select([DB::expr('MAX('.static::$table.'.sort)'), 'max'])
                ->from(static::$table)
                ->where(static::$table.'.gallery_id', '=', $parent_id)
                ->find();
            if( !$row ) {
                return 0;
            }
            return (int) $row->max + 1;
        }

        public static function getRows($parent_id) {
            $result = DB::select()
                ->from(static::$table)
                ->where('gallery_id', '=', $parent_id)
                ->order_by('sort', 'ASC');
            return $result->find_all();
        }

    }