<?php
namespace Modules\Sitemap\Models;

use Core\QB\DB;
use Core\Common;

class Sitemap extends Common
{

    public static $table = 'sitemap';

    public static function getRows($status = null, $sort = null, $type = null, $limit = null, $offset = null, $filter = true)
    {
        $result = DB::select()->from(static::$table);
        if ($status !== null) {
            $result->where('status', '=', $status);
        }
        if ($filter) {
            $result = static::setFilter($result);
        }
        if ($sort !== null) {
            if ($type !== null) {
                $result->order_by($sort, $type);
            } else {
                $result->order_by($sort);
            }
        }
        $result->order_by('id', 'DESC');
        if ($limit !== null) {
            $result->limit($limit);
            if ($offset !== null) {
                $result->offset($offset);
            }
        }
        return $result->find_all()->as_array('alias');
    }
	
}