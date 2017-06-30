<?php
namespace Modules\Catalog\Models;

use Core\Config;
use Core\Cookie;
use Core\QB\DB;
use Core\CommonI18n;

class Items extends CommonI18n
{

    public static $table = 'catalog';
    public static $tableI18n = 'catalog_i18n';
    public static $tableImages = 'catalog_images';

    public static function searchRows($queries, $limit = null, $offset = null)
    {
        $result = DB::select(
            static::$table . '.*'
        )
            ->from(static::$table)
            ->join(static::$tableI18n)->on(static::$table.'.id', '=', static::$tableI18n.'.row_id')
            ->where(static::$tableI18n.'.language', '=', \I18n::$lang)
            ->join('brands')->on('brands.alias', '=', static::$table . '.brand_alias')
            ->join('brands_i18n')
                ->on('brands_i18n.row_id', '=', 'brands.id')
            ->where('brands_i18n.language', '=', \I18n::$lang)
            ->where(static::$table . '.status', '=', 1);
        $result->and_where_open();
        $result->or_where_open();
        foreach ($queries as $query) {
            $result->where(static::$tableI18n . '.name', 'LIKE', '%' . $query . '%');
        }
        $result->or_where_close();
        $result->or_where_open();
        foreach ($queries as $query) {
            $result->where(static::$table . '.artikul', 'LIKE', '%' . $query . '%');
        }
        $result->or_where_close();
        $result->or_where_open();
        foreach ($queries as $query) {
            $result->where('brands_i18n.name', 'LIKE', '%' . $query . '%');
        }
        $result->or_where_close();
        $result->and_where_close();
        $result->order_by(static::$table . '.sort', 'ASC');
        $result->order_by(static::$table . '.id', 'DESC');
        if ($limit !== null) {
            $result->limit($limit);
            if ($offset !== null) {
                $result->offset($offset);
            }
        }
        return $result->find_all();
    }


    public static function countSearchRows($queries)
    {
        $result = DB::select([DB::expr('COUNT(' . static::$table . '.id)'), 'count'])
            ->from(static::$table)
            ->join(static::$tableI18n)->on(static::$table.'.id', '=', static::$tableI18n.'.row_id')
            ->where(static::$tableI18n.'.language', '=', \I18n::$lang)
            ->join('brands')->on('brands.alias', '=', static::$table . '.brand_alias')
            ->join('brands_i18n')
                ->on('brands_i18n.row_id', '=', 'brands.id')
            ->where('brands_i18n.language', '=', \I18n::$lang)
            ->where(static::$table . '.status', '=', 1);
        $result->and_where_open();
        $result->or_where_open();
        foreach ($queries as $query) {
            $result->where(static::$tableI18n . '.name', 'LIKE', '%' . $query . '%');
        }
        $result->or_where_close();
        $result->or_where_open();
        foreach ($queries as $query) {
            $result->where(static::$table . '.artikul', 'LIKE', '%' . $query . '%');
        }
        $result->or_where_close();
        $result->or_where_open();
        foreach ($queries as $query) {
            $result->where('brands_i18n.name', 'LIKE', '%' . $query . '%');
        }
        $result->or_where_close();
        $result->and_where_close();
        return $result->count_all();
    }


    public static function getQueries($query)
    {
        $spaces = ['-', '_', '/', '\\', '=', '+', '*', '$', '@', '(', ')', '[', ']', '|', ',', '.', ';', ':', '{', '}'];
        $query = str_replace($spaces, ' ', $query);
        $arr = preg_split("/[\s,]+/", $query);
        return $arr;
    }


    public static function getBrandItems($brand_alias, $sort = null, $type = null, $limit = null, $offset = null)
    {
        $result = DB::select(static::$table . '.*')
            ->from(static::$table)
            ->where(static::$table . '.brand_alias', '=', $brand_alias)
            ->where(static::$table . '.status', '=', 1);
        if ($sort !== null) {
            if ($type !== null) {
                $result->order_by(static::$table . '.' . $sort, $type);
            } else {
                $result->order_by(static::$table . '.' . $sort);
            }
        }
        if ($limit !== null) {
            $result->limit($limit);
            if ($offset !== null) {
                $result->offset($offset);
            }
        }
        return $result->find_all();
    }


    public static function countBrandItems($brand_alias)
    {
        $result = DB::select([DB::expr('COUNT(' . static::$table . '.id)'), 'count'])
            ->from(static::$table)
            ->where(static::$table . '.brand_alias', '=', $brand_alias)
            ->where(static::$table . '.status', '=', 1);
        return $result->count_all();
    }


    public static function getItemsByFlag($flag, $sort = null, $type = null, $limit = null, $offset = null)
    {
        $result = DB::select(static::$table . '.*', static::$tableI18n.'.name')
            ->from(static::$table)
            ->join(static::$tableI18n)
                ->on(static::$table.'.id', '=', static::$tableI18n.'.row_id')
            ->where(static::$tableI18n.'.language', '=', \I18n::$lang)
            ->where(static::$table . '.' . $flag, '=', 1)
            ->where(static::$table . '.status', '=', 1);
        
        if ($sort !== null) {
            $sortTable = static::$table;
            if ($sort == 'name') {
                $sortTable = static::$tableI18n;
            }
            if ($type !== null) {
                $result->order_by($sortTable . '.' . $sort, $type);
            } else {
                $result->order_by($sortTable . '.' . $sort);
            }
        }
        if ($limit !== null) {
            $result->limit($limit);
            if ($offset !== null) {
                $result->offset($offset);
            }
        }
        return $result->find_all();
    }


    public static function countItemsByFlag($flag)
    {
        $result = DB::select([DB::expr('COUNT(' . static::$table . '.id)'), 'count'])
            ->from(static::$table)
            ->where(static::$table . '.' . $flag, '=', 1)
            ->where(static::$table . '.status', '=', 1);
        return $result->count_all();
    }


    public static function addViewed($id)
    {
        $ids = static::getViewedIDs();
        if (!in_array($id, $ids)) {
            $ids[] = $id;
            Cookie::setArray('viewed', $ids, 60 * 60 * 24 * 30);
        }
        return;
    }


    public static function getViewedIDs()
    {
        $ids = Cookie::getArray('viewed', []);
        return $ids;
    }


    public static function getViewedItems($sort = null, $type = null, $limit = null, $offset = null)
    {
        $ids = Items::getViewedIDs();
        if (!$ids) {
            return [];
        }
        $result = DB::select(static::$table . '.*')
            ->from(static::$table)
            ->where(static::$table . '.id', 'IN', $ids)
            ->where(static::$table . '.status', '=', 1);
        if ($sort !== null) {
            if ($type !== null) {
                $result->order_by(static::$table . '.' . $sort, $type);
            } else {
                $result->order_by(static::$table . '.' . $sort);
            }
        }
        if ($limit !== null) {
            $result->limit($limit);
            if ($offset !== null) {
                $result->offset($offset);
            }
        }
        return $result->find_all();
    }


    public static function countViewedItems()
    {
        $ids = Items::getViewedIDs();
        if (!$ids) {
            return 0;
        }
        $result = DB::select([DB::expr('COUNT(' . static::$table . '.id)'), 'count'])
            ->from(static::$table)
            ->where(static::$table . '.id', 'IN', $ids)
            ->where(static::$table . '.status', '=', 1);
        return $result->count_all();
    }


    public static function getRow($value, $field = 'id', $status = null)
    {
        $result = DB::select(
            static::$table . '.*', 
            static::$tableI18n . '.name',
            static::$tableI18n . '.h1', 
            static::$tableI18n . '.title',
            static::$tableI18n . '.keywords',
            static::$tableI18n . '.description',
            ['brands_i18n.name', 'brand_name'],
            ['models_i18n.name', 'model_name'],
            ['catalog_tree_i18n.name', 'parent_name']
        )
            ->from(static::$table)
            ->join(static::$tableI18n)
                ->on(static::$table.'.id', '=', static::$tableI18n.'.row_id')
            ->join('catalog_tree_i18n', 'LEFT')
                ->on(static::$table . '.parent_id', '=', 'catalog_tree_i18n.row_id')
                ->on('catalog_tree_i18n.language', '=', DB::expr("'".\I18n::$lang."'"))
            ->join('brands', 'LEFT')
                ->on(static::$table . '.brand_alias', '=', 'brands.alias')
                ->on('brands.status', '=', DB::expr('1'))
            ->join('brands_i18n', 'LEFT')
                ->on('brands_i18n.row_id', '=', 'brands.id')
                ->on('brands_i18n.language', '=', DB::expr("'".\I18n::$lang."'"))
            ->join('models', 'LEFT')
                ->on(static::$table . '.model_alias', '=', 'models.alias')
                ->on('models.status', '=', DB::expr('1'))
            ->join('models_i18n', 'LEFT')
                ->on('models_i18n.row_id', '=', 'models.id')
                ->on('models_i18n.language', '=', DB::expr("'".\I18n::$lang."'"))
            ->where(static::$tableI18n.'.language', '=', \I18n::$lang);
		if ($status !== null) {
			$result = $result->where(static::$table . '.status', '=', 1);
		}
           
        $result = $result->where(static::$table . '.id', '=', $value);

        return $result->find();
    }


    public static function getItemImages($item_id)
    {
        $result = DB::select('image')
            ->from(static::$tableImages)
            ->where(static::$tableImages . '.catalog_id', '=', $item_id)
            ->order_by(static::$tableImages . '.sort');
        return $result->find_all();
    }


    public static function getItemSpecifications($item_id, $parent_id)
    {
        $specifications = DB::select('specifications.*', 'specifications_i18n.name')
            ->from('specifications')
            ->join('specifications_i18n')
                ->on('specifications.id','=', 'specifications_i18n.row_id')
            ->join('catalog_tree_specifications', 'LEFT')->on('catalog_tree_specifications.specification_id', '=', 'specifications.id')
            ->where('catalog_tree_specifications.catalog_tree_id', '=', $parent_id)
            ->where('specifications.status', '=', 1)
            ->where('specifications_i18n.language','=',\I18n::$lang)
            ->order_by('specifications_i18n.name')
            ->as_object()->execute();
        $res = DB::select('specifications_values.*','specifications_values_i18n.name')
            ->from('specifications_values')
            ->join('specifications_values_i18n')
                ->on('specifications_values.id','=', 'specifications_values_i18n.row_id')
            ->join('catalog_specifications_values', 'LEFT')->on('catalog_specifications_values.specification_value_alias', '=', 'specifications_values.alias')
            ->where('catalog_specifications_values.catalog_id', '=', $item_id)
            ->where('specifications_values_i18n.language', '=', \I18n::$lang)
            ->where('specifications_values.status', '=', 1)
            ->as_object()->execute();
        $specValues = [];
        foreach ($res as $obj) {
            $specValues[$obj->specification_id][] = $obj;
        }
        $spec = [];
        foreach ($specifications as $obj) {
            if (isset($specValues[$obj->id]) and is_array($specValues[$obj->id]) and count($specValues[$obj->id])) {
                if ($obj->type_id == 3) {
                    $spec[$obj->name] = '';
                    foreach ($specValues[$obj->id] AS $o) {
                        $spec[$obj->name] .= $o->name . ', ';
                    }
                    $spec[$obj->name] = substr($spec[$obj->name], 0, -2);
                } else {
                    $spec[$obj->name] = $specValues[$obj->id][0]->name;
                }
            }
        }
        return $spec;
    }
	
	public static function getReviews($catalog_id) {
		
		$result = DB::select()->from('catalog_comments')
						->where('catalog_id','=',$catalog_id)
						->where('status','=',1)
						->order_by('date','DESC')
						->find_all();
						
		return $result;
		
	}
	
	public static function getRows($status = null, $sort = null, $type = null, $limit = null, $offset = null, $filter = true)
    {
        $result = DB::select(
            static::$table.'.*',
            ['brands_i18.name', 'brand_name'],
            ['models.name','model_name']
        )
        ->from(static::$table)
        ->join('brands','left')->on(static::$table.'.brand_alias', '=', 'brands.alias')
        ->join('brands_i18n')->on('brands_i18n.row_id', '=', 'brands.id')
        ->join('models','left')->on(static::$table.'.model_alias', '=', 'models.alias')
        ->where('brands_i18n.language', '=', \I18n::$lang);    
        if ($status !== null) {
            $result->where(static::$table.'.status', '=', $status);
        }
        if ($filter) {
            $result = static::setFilter($result);
        }
        if ($sort !== null) {
            if ($type !== null) {
                $result->order_by(static::$table.'.'.$sort, $type);
            } else {
                $result->order_by(static::$table.'.'.$sort);
            }
        }
        $result->order_by(static::$table.'.id', 'DESC');
        if ($limit !== null) {
            $result->limit($limit);
            if ($offset !== null) {
                $result->offset($offset);
            }
        }
        return $result->find_all();
    }

}