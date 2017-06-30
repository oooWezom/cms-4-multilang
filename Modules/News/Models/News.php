<?php

namespace Modules\News\Models;

use Core\QB\DB;
use Core\CommonI18n;

class News extends CommonI18n {

    public static $table = 'news';

    public static function getRowSimple($value, $field = 'id', $status = NULL) {
        $lang = \I18n::$lang;
        static::$tableI18n = static::$table . '_i18n';
        $row = DB::select(static::$tableI18n . '.*', static::$table . '.*')
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                ->where(static::$table . '.' . $field, '=', $value)
                ->where(static::$table . '.date', '<=', time());
        ;
        if ($status <> NULL) {
            $row->where(static::$table . '.status', '=', $status);
        }
        return $row->where(static::$tableI18n . '.language', '=', $lang)->find();
    }

    /**
     * @param null /integer $status - 0 or 1
     * @param null /string $sort
     * @param null /string $type - ASC or DESC. No $sort - no $type
     * @param null /integer $limit
     * @param null /integer $offset - no $limit - no $offset
     * @return object
     */
        public static function getRows($status = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL, $filter = true) {
            $lang = \I18n::$lang;
            static::$tableI18n = static::$table.'_i18n';
            if($sort) {
                $arr = explode('.', $sort);
                if(count($arr) < 2) {
                    $sort = static::$table.'.'.$sort;
                }
            }
            $result = DB::select(
                static::$tableI18n.'.*',
                static::$table.'.*'
            )
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
                ->where(static::$tableI18n.'.language', '=', $lang)
                ->where(static::$table . '.date', '<=', time());
            if( $filter ) {
                $result = static::setFilter($result);
            }
            if( $status <> NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $sort <> NULL ) {
                if( $type <> NULL ) {
                    $result->order_by($sort, $type);
                } else {
                    $result->order_by($sort);
                }
            }
            $result->order_by(static::$table.'.id', 'DESC');
            if( $limit <> NULL ) {
                $result->limit($limit);
                if( $offset <> NULL ) {
                    $result->offset($offset);
                }
            }
            return $result->find_all();
        }

    /**
     * @param null /integer $status - 0 or 1
     * @return int
     */
       public static function countRows($status = NULL, $filter = true) {
            $lang = \I18n::$lang;
            static::$tableI18n = static::$table.'_i18n';
            $result = DB::select(array(DB::expr('COUNT('.static::$table.'.id)'), 'count'))
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
                ->where(static::$tableI18n.'.language', '=', $lang)
                ->where(static::$table . '.date', '<=', time());
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $filter ) {
                $result = static::setFilter($result);
            }
            return $result->count_all();
        }

}