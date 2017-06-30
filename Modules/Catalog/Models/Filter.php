<?php
namespace Modules\Catalog\Models;

use Core\Arr;
use Core\HTML;
use Core\QB\DB;
use Core\Route;
use Core\Config;

class Filter
{

    public static $_data;
    public static $_counts;

    public static function getFilteredItemsList($limit, $offset, $sort, $type)
    {
        if (static::$_data) {
            return static::$_data;
        }
        $filter = Config::get('filter_array');
        $result = DB::select(
            'catalog.id',
            'catalog.cost',
            'catalog_i18n.name',
            'catalog.alias',
            'catalog.image',
            'catalog.brand_alias',
            'catalog.model_alias',
            'catalog.available',
            'catalog.specifications'
        )
            ->from('catalog')
            ->join('catalog_i18n')->on('catalog.id','=','catalog_i18n.row_id')
            ->where('catalog_i18n.language', '=', \I18n::$lang)
            ->where('catalog.status', '=', 1)
            ->where('catalog.parent_id', '=', (int)Route::param('group'));

        if (is_array($filter) && array_key_exists('brand', $filter) && count($filter['brand'])) {
            $result->where('catalog.brand_alias', 'IN', $filter['brand']);
            unset($filter['brand']);
        }
        if (is_array($filter) && array_key_exists('model', $filter) && count($filter['model'])) {
            $result->where('catalog.model_alias', 'IN', $filter['model']);
            unset($filter['model']);
        }
        if (is_array($filter) && array_key_exists('maxcost', $filter) && count($filter['maxcost']) && (int)$filter['maxcost'][0] > 0) {
            $result->where('catalog.cost', '<=', (int)$filter['maxcost'][0]);
            unset($filter['maxcost']);
        }
        if (is_array($filter) && array_key_exists('mincost', $filter) && count($filter['mincost']) && (int)$filter['mincost'][0] >= 0) {
            $result->where('catalog.cost', '>=', (int)$filter['mincost'][0]);
            unset($filter['mincost']);
        }
        if (is_array($filter) && array_key_exists('available', $filter) && count($filter['available'])) {
            $result->where('catalog.available', 'IN', $filter['available']);
            unset($filter['available']);
        }

        if (is_array($filter) && count($filter)) {
            $result
                ->select(DB::expr('COUNT(DISTINCT catalog_specifications_values.specification_alias) AS cList'))
                ->join('catalog_specifications_values')->on('catalog_specifications_values.catalog_id', '=', 'catalog.id');
            $result->and_where_open();

            foreach ($filter as $key => $val) {
                $result
                    ->or_where_open()
                    ->where('catalog_specifications_values.specification_alias', '=', $key)
                    ->where('catalog_specifications_values.specification_value_alias', 'IN', $val)
                    ->or_where_close();
            }

            $result->and_where_close();
            $result->having('cList', '>=', count($filter));
        }
        $sortTable = 'catalog';
        if ($sort == 'name') {
            $sortTable = 'catalog_i18n';
        }
        $result = $result->group_by('catalog.id')
            ->order_by('catalog.available', 'ASC')
            ->order_by($sortTable.'.' . $sort, $type)
            ->find_all();
			

        static::$_data = $result;

        $items = [];
        for ($i = $offset; $i < $limit + $offset; $i++) {
            if (!isset($result[$i])) {
                break;
            }
            $items[] = $result[$i];
        }

        return [
            'items' => $items,
            'total' => sizeof($result),
        ];
    }


    // Get clickable filters and min/max costs, included current
    public static function getClickableFilterElements()
    {
        $filter = Config::get('filter_array');
        $result = DB::select(
            'catalog.cost',
            'catalog.brand_alias',
            'catalog.model_alias',
            'catalog.available',
            'catalog.id'
        )
            ->from('catalog')
            ->where('catalog.status', '=', 1)
            ->where('catalog.parent_id', '=', (int)Route::param('group'));

        $result = $result->group_by('catalog.id')->find_all();

        $params = [];
        $costs = [];
		$filtered_costs = [];
        $sortable = Config::get('sortable');
		$sortable = $sortable['spec'];
        $items = [];

        $ids = [];
        foreach ($result as $key => $value) {
            $ids[] = $value->id;
            $costs[] = $value->cost;
            $items[$value->id]['cost'][] = $value->cost;
            if ($value->brand_alias) {
                $items[$value->id]['brand'][] = $value->brand_alias;
            }
            if ($value->model_alias) {
                $items[$value->id]['model'][] = $value->model_alias;
            }
            $items[$value->id]['available'][] = $value->available;
        }

        $result = [];
        if (count($ids)) {
            $result = DB::select('csv.catalog_id', 'csv.specification_alias', 'csv.specification_value_alias')
                ->from(['catalog_specifications_values', 'csv'])
                ->where('csv.catalog_id', 'IN', $ids)
                ->find_all();
        }

        foreach ($result as $key => $value) {
            $items[$value->catalog_id][$value->specification_alias][] = $value->specification_value_alias;
        }

        /*$params = [];
        $costs = [];
        $sortable = Config::get('sortable');
        $items = [];

        $result = self::$_data;
        foreach ($result as $value) {
            $costs[] = $value->cost;
            if (!array_key_exists($value->id, $items)) {
                $items[$value->id] = [];
            }
            $items[$value->id]['cost'][] = $value->cost;
            if ($value->brand_alias) {
                $items[$value->id]['brand'][] = $value->brand_alias;
            }
            if ($value->model_alias) {
                $items[$value->id]['model'][] = $value->model_alias;
            }
            $items[$value->id]['available'][] = $value->available;

            $specifications = json_decode($value->specifications, true);
            foreach ($specifications as $s => $v) {
                if (!array_key_exists($s, $items[$value->id])) {
                    $items[$value->id][$s] = [];
                }
                if (is_array($v)) {
                    $items[$value->id][$s] = array_merge($items[$value->id][$s], $v);
                } else {
                    $items[$value->id][$s][] = $v;
                }
            }
        }*/
        $counts = [];
		$new_costs = [];
        foreach ($items as $item) {
			$for_cost = 0;
            foreach ($sortable as $sort) {
                $filtered = 0;
                $f = $filter;
                if (isset($f[$sort])) {
                    unset($f[$sort]);
                }
                if ($f) {
                    foreach ($f as $key => $value) {
                        if (!isset($item[$key]) and !in_array($key, ['mincost', 'maxcost'])) {
                            $filtered = 1;
							$for_cost = 1;
                        } else {
                            switch ($key) {
                                case 'mincost':
                                    if ($item['cost'][0] < $value[0]) {
                                        $filtered = 1;
                                    }
                                    break;
                                case 'maxcost':
                                    if ($item['cost'][0] > $value[0]) {
                                        $filtered = 1;
                                    }
                                    break;
                                default:
                                    $flag = 0;
                                    foreach ($item[$key] AS $element) {
                                        if (in_array($element, $value)) {
                                            $flag = 1;
                                        }
                                    }
                                    if (!$flag) {
                                        $filtered = 1;
										$for_cost = 1;
                                    }
                                    break;
                            }
                        }
                        if ($filtered) {
                            break;
                        }
                    }
                }
                if (!$filtered and isset($item[$sort])) {
                    foreach ($item[$sort] as $value) {
                        if (!in_array($value, Arr::get($params, $sort, []))) {
                            $params[$sort][] = $value;
                        }
                        if (!array_key_exists($sort, $counts)) {
                            $counts[$sort] = [];
                        }
                        if (!array_key_exists($value, $counts[$sort])) {
                            $counts[$sort][$value] = 0;
                        }
                        $counts[$sort][$value]++;
                    }
                }
            }
			if ($for_cost == 0) {
				$new_costs[] = $item['cost'][0];
			}
        }
        $min = $new_costs ? min($new_costs) : 0;
        $max = $new_costs ? max($new_costs) : 0;


        static::$_counts = $counts;
        return [
            'filter' => $params,
            'counts' => $counts,
            'min' => $min,
            'max' => $max,
        ];
    }


    // Get brands list for filter
    public static function getBrandsWidget()
    {
        return DB::select(
            'brands_i18n.name', 
            'brands.alias',
            'brands.id')
            ->from('brands')
            ->join('brands_i18n')
                ->on('brands_i18n.row_id', '=', 'brands.id')
            ->join('catalog')->on('catalog.brand_alias', '=', 'brands.alias')
            ->where('brands_i18n.language', '=', \I18n::$lang)
            ->where('catalog.status', '=', 1)
            ->where('brands.status', '=', 1)
            ->where('catalog.parent_id', '=', Route::param('group'))
            ->group_by('brands.id')
            ->order_by('brands_i18n.name')
            ->find_all();
    }


    // Get models list for filter
    public static function getModelsWidget()
    {
        $filter = Config::get('filter_array');
        if (!isset($filter['brand']) || !is_array($filter['brand']) || !isset($filter['brand'][0])) {
            return [];
        }
        return DB::select('models_18n.name', 'models.alias', 'models.id')
            ->from('models')
            ->join('catalog')->on('catalog.model_alias', '=', 'models.alias')
            ->join('brands')->on('brands.alias', '=', 'catalog.brand_alias')
            ->join('models_i18n')->on('models_i18n.row_id', '=', 'models.id')
            ->where('brands.status', '=', 1)
            ->where('catalog.status', '=', 1)
            ->where('models.status', '=', 1)
            ->where('brands.alias', 'IN', $filter['brand'])
            ->where('catalog.parent_id', '=', Route::param('group'))
            ->group_by('models.id')
            ->order_by('models_i18n.name')
            ->find_all();
    }


    // Get specifications values list for filter
    public static function getSpecificationsWidget()
    {
        $result = DB::select(
            'specifications_values.id',
            'specifications_values_i18n.name',
            'specifications_values.color',
            'specifications_values.alias',
            ['specifications_i18n.name', 'specification_name'],
            ['specifications.alias', 'specification_alias'],
            ['specifications.id', 'specification_id'],
            ['specifications.type_id', 'specification_type_id']
        )
            ->from('specifications_values')
            ->join('specifications_values_i18n')->on('specifications_values_i18n.row_id', '=', 'specifications_values.id')
            ->join('catalog_specifications_values')
            ->on('catalog_specifications_values.specification_value_alias', '=', 'specifications_values.alias')
            ->join('specifications')
            ->on('specifications.alias', '=', 'catalog_specifications_values.specification_alias')
            ->join('specifications_i18n')->on('specifications_i18n.row_id', '=', 'specifications.id')
            ->join('catalog')
            ->on('catalog_specifications_values.catalog_id', '=', 'catalog.id')
            ->where('catalog.status', '=', 1)
            ->where('specifications.status', '=', 1)
            ->where('catalog.parent_id', '=', Route::param('group'))
            ->where('specifications_values_i18n.language', '=', \I18n::$lang)
            ->where('specifications_i18n.language', '=', \I18n::$lang)
            ->group_by('specifications.alias')
            ->group_by('specifications_values.alias')
//            ->order_by('specifications.sort')
//            ->order_by('specifications_values.sort')
            ->find_all();
        $specifications = [];
        $values = [];
        foreach ($result as $obj) {
            $values[$obj->specification_alias][] = $obj;
            $specifications[$obj->specification_alias] = $obj->specification_name;
        }
        return [
            'list' => $specifications,
            'values' => $values,
        ];
    }


    // Set to memory filter as array
    public static function setFilterParameters()
    {
        if (!Route::param('filter')) {
			self::setParamsForCheck([]);
            return false;
        }
		$fil = explode('/',Route::param('filter'));
				
        foreach ($fil AS $key => $g) {
            $g = rawurldecode($g);
            $g = strip_tags($g);
            $g = stripslashes($g);
            $g = trim($g);
            $s = explode("-", $g);
			if (count($s)==2) {
				$value = $s[1];
				$value = rawurldecode($value);
				$value = strip_tags($value);
				$value = stripslashes($value);
				$value = trim($value);
				$arr_val = explode("_", $value);
				$filter[$s[0]] = $arr_val;
			}
        }
		self::setParamsForCheck($filter);
		$check = self::checkFilterValues($filter,Config::get('filter_array'));
		
		return $check;
        
    }
	
	public static function setParamsForCheck($filter) {
					
		// specifications for sort
		$all_spec = ['brand', 'model', 'available', 'mincost', 'maxcost'];
		// values for sort
		$all_params = [];
		// right filter parameters for checking filter
		$filter_params = [];
		
		
		$brands = DB::select('alias')->from('brands')
			->where('status','=',1)
			->order_by('sort','ASC')
			->order_by('id','DESC')
			->find_all();
			
		foreach ($brands as $brand) {
			$all_params['brand'][] = $brand->alias;
			if (isset($filter['brand']) and in_array($brand->alias,$filter['brand'])) {
				$filter_params['brand'][] = $brand->alias;
			}
		}

		$models = DB::select('alias')->from('models')
			->where('status','=',1)
			->order_by('alias','ASC')
			->order_by('id','DESC')
			->find_all();
		foreach ($models as $model) {
			$all_params['model'][] = $model->alias;
			if (isset($filter['model']) and in_array($model->alias,$filter['model'])) {
				$filter_params['model'][] = $model->alias;
			}
		}
		
		$all_params['available'] = [0, 1, 2];
		
		if (isset($filter['available'])) {
			foreach ($filter['available'] as $key=>$val) {
				if ($val>0 and $val<3) {
					$filter_params['available'][]=$val;
				}
			}
		}
		
		if (isset($filter['mincost'])) {
			$filter_params['mincost'] = $filter['mincost'];
		}
		
		if (isset($filter['maxcost'])) {
			$filter_params['maxcost'] = $filter['maxcost'];
		}
		
		$specifications = DB::select('specifications.alias',['specifications_values.alias', 'value'])
			->from('specifications')
			->join('specifications_values')->on('specifications_values.specification_id','=','specifications.id')
			->where('specifications.status','=',1)
			->where('specifications_values.status','=',1)
			->order_by('specifications.sort','asc')
			->order_by('specifications.id','desc')
			->order_by('specifications_values.sort','asc')
			->order_by('specifications_values.id','desc')
			->find_all();

		foreach ($specifications as $spec) {
			if (!in_array($spec->alias,$all_spec)) {
				$all_spec[] = $spec->alias;
			}
			$all_params[$spec->alias][]=$spec->value;
			if (isset($filter[$spec->alias]) and in_array($spec->value,$filter[$spec->alias])) {
				$filter_params[$spec->alias][] = $spec->value;
			}
			
		}	
		Config::set('filter_array', $filter_params);
		Config::set('sortable', ['spec'=>$all_spec, 'params' => $all_params]);

	}
	
	public static function checkFilterValues($filter, $values) {
		
		foreach ($filter as $key=>$zna) {
					
			if (!isset($values[$key])) {
				// error 404
				return ['success'=>false];
			}
			foreach ($zna as $val) {
				if (!in_array($val,$values[$key])) {
					// error 404
					return ['success'=>false];
				}
			}
		}
		
		if ($filter !== $values) {
			// need redirect
			return ['success'=>true, 'resort' => true];
		} else {
			//ok
			return ['success'=>true, 'resort' => false];
		}
		

	}
	
	public static function getFilterFromArr($arr) {
		$filter = '';
		foreach ($arr as $key=>$val) {
			$filter.= '/'.$key.'-'.implode('_',$val);
		}
		return $filter;
	}


    /**
     *  Check for existance parameter in the filter
     * @param  string $element alias of element of the filter
     * @param  string $specification alias of the filter
     * @return string                Word 'checked' or NULL
     */
    public static function checked($element, $specification)
    {
        $filter = [];
        if (Config::get('filter_array')) {
            $filter = Config::get('filter_array');
        }
        if (in_array($element, Arr::get($filter, $specification, []))) {
            return 'checked';
        }
        return null;
    }


    /**
     *  Get a minimal number for price filter
     * @param  int $realMin real minimal number for current catalog group
     * @return int           minimal number for value in price filter
     */
    public static function min($realMin)
    {
        if (!Config::get('filter_array')) {
            return $realMin;
        }
        $filter = Config::get('filter_array');
        if (!isset($filter['mincost'])) {
            return $realMin;
        }
        $min = (int)$filter['mincost'][0];
        if ($min < $realMin) {
            return $realMin;
        }
        return $min;
    }


    /**
     *  Get a maximum number for price filter
     * @param  [int] $realMin  real maximum number for current catalog group
     * @return [int]           maximum number for value in price filter
     */
    public static function max($realMax)
    {
        if (!Config::get('filter_array')) {
            return $realMax;
        }
        $filter = Config::get('filter_array');
        if (!isset($filter['maxcost'])) {
            return $realMax;
        }
        $max = $filter['maxcost'][0];
        if ($max > $realMax) {
            return $realMax;
        }
        return $max;
    }


    // Set to memory the algorithm of filter elements
 /*   public static function setSortElements()
    {
        $sortable = ['brand', 'model', 'available', 'mincost', 'maxcost'];
        $result = DB::select('alias')->from('specifications')->where('status', '=', 1)->order_by('sort','ASC')->order_by('id','DESC')->find_all();
        foreach ($result as $obj) {
            $sortable[] = $obj->alias;
        }
        Config::set('sortable', $sortable);
    }*/


    // Generate input for filter
    public static function generateInput($filter, $obj, $alias, $type = 'simple')
    {
        $check = (isset($filter[$alias]) and in_array($obj->alias, $filter[$alias]));
        $checked = Filter::checked($obj->alias, $alias);
        $disabled = (!$check and !$checked) ? 'disabled' : '';
        $input = '';
        //Count items for filter value
        $count = static::$_counts[$alias][$obj->alias];
        switch ($type) {
            case 'color':
                if (!$disabled) {
                    $input .= '<a class="toolTip" href="' . Filter::generateLinkWithFilter($alias, $obj->alias) . '" data-title="' . $obj->name . '">';
                }
                $input .= '<input  id="' . $alias . $obj->alias . '" value="' . $obj->alias . '" type="checkbox" ' . $checked . $disabled . ' />';
                $input .= '<ins><div style="background-color:' . $obj->color . '"></div></ins>';
                if (!$disabled) {
                    $input .= '</a>';
                }
                break;
            default:
                $input .= '<label class="checkBlock" for="' . $alias . $obj->id . '">';
                if (!$disabled) {
                    $input .= '<a href="' . Filter::generateLinkWithFilter($alias, $obj->alias) . '">';
                }
                $input .= '<input  id="' . $alias . $obj->alias . '" value="' . $obj->alias . '" type="checkbox" ' . $checked . $disabled . ' />';
                $input .= '<ins></ins>';
                $input .= '<p>' . $obj->name . '</p>';
                if (!$disabled) {
                    $input .= '</a>';
                }
                $input .= '</label>';
                break;
        }
        return $input;
    }


    // Generate unique input for filter
    public static function generateElseInput($filter, $name, $value, $alias)
    {
        $value = (string)$value;
        $check = (isset($filter[$alias]) and in_array($value, $filter[$alias]));
        $checked = Filter::checked($value, $alias);
        $disabled = (!$check and !$checked) ? 'disabled' : '';
        $input = '';

        $input .= '<label class="checkBlock" for="' . $alias . $value . '">';
        if (!$disabled) {
            $input .= '<a href="' . Filter::generateLinkWithFilter($alias, $value) . '">';
        }
        $input .= '<input  id="' . $alias . $value . '" value="' . $value . '" type="checkbox" ' . $checked . $disabled . ' />';
        $input .= '<ins></ins>';
        $input .= '<p>' . $name . '</p>';
        if (!$disabled) {
            $input .= '</a>';
        }
        $input .= '</label>';

        return $input;
    }


    // Sort link in items list
    public static function setSortLink($sort = null, $type = null)
    {
        $arr = explode('?', $_SERVER['REQUEST_URI']);
        $link = $arr[0];
        $_get = explode('&', $arr[1]);
        $get = [];
        foreach ($_get as $k) {
            $r = explode('=', $k);
            $get[$r[0]] = $r[1];
        }
        if (isset($get['sort'])) {
            unset($get['sort']);
        }
        if (isset($get['type'])) {
            unset($get['type']);
        }
        if ($sort !== null) {
            $get['sort'] = $sort;
            if ($type !== null) {
                $get['type'] = $type;
            }
        }
        if (count($get)) {
            $add = [];
            foreach ($get as $key => $value) {
                if ($key && $value) {
                    $add[] = $key . '=' . $value;
                }
            }
            if ($add) {
                $link .= '?' . implode('&', $add);
            }
        }
        return $link;
    }


    // Check if this is current sortable
    public static function isThisSort($sort = null, $type = null)
    {
        if (Arr::get($_GET, 'sort') == $sort and Arr::get($_GET, 'type') == $type) {
            return 'active';
        }
        return null;
    }


    // Link with per page argument
    public static function setPerPageLink($number)
    {
        $arr = explode('?', $_SERVER['REQUEST_URI']);
        $link = $arr[0];
        $_get = explode('&', $arr[1]);
        $get =[];
        foreach ($_get as $k) {
            $r = explode('=', $k);
            $get[$r[0]] = $r[1];
        }
        if (isset($get['per_page'])) {
            unset($get['per_page']);
        }
        $get['per_page'] = $number;
        $add = [];
        foreach ($get as $key => $value) {
            if ($key && $value) {
                $add[] = $key . '=' . $value;
            }
        }
        if ($add) {
            $link .= '?' . implode('&', $add);
        }
        return $link;
    }

    /**
     * Generate link with filter parameters
     * @param  string $key [specification alias]
     * @param  string $value [specification value alias]
     * @return string        [link]
     */
    public static function generateLinkWithFilter($key, $value)
    {
        // Devide url from GET parameters
        $uri = Arr::get($_SERVER, 'REQUEST_URI');
        $uri = explode('?', $uri);
        $link = $uri[0];
        $get = '';
        if (count($uri) > 1) {
            $get = '?' . $uri[1];
        }
        // Clear link from pagination
        if ((int)Route::param('page')) {
            $link = str_replace('/page/' . (int)Route::param('page'), '', $link);
        }
        // If filter is empty - create it and return
        $filter = Config::get('filter_array');
        // Clear link from filter
		if (Route::param('filter')) {
			$link = str_replace('/' . Route::param('filter'), '', $link);
		}
        // Setup filter
        if (is_array($filter) && isset($filter[$key])) {
            if (!in_array($value, $filter[$key])) {
                $filter[$key][] = $value;
            } else {
                unset($filter[$key][array_search($value, $filter[$key])]);
                if (empty($filter[$key]) or (count($filter[$key]) == 1 and !trim(end($filter[$key])))) {
                    unset($filter[$key]);
                }
            }
        } else {
            $filter[$key] = [$value];
        }
        // Generate link with filter
        $filter = Filter::generateFilter($filter);
        // Return link
        if ($filter) {
            return $link . '/' . $filter . $get;
        }
        return $link . $get;
    }


    /**
     *  Create filter part of URI
     * @param  array $array [associative array with filter elements]
     * @return string        [part of new URI]
     */
    public static function generateFilter($array)
    {
        // Sort filter elements
        $array = Filter::sortFilter($array);
        // Generate and return filters part
        $link = [];
		if (sizeof($array)) {
			foreach ($array as $key => $values) {
				$link[] = $key . '-' . implode('_', $values);
			}
		}

        return implode('/', $link);
    }


    /**
     *  Sort our filter
     * @param  array $array Our filter in array
     * @return array         Our filter but sorted!
     */
    public static function sortFilter($array)
    {
        $template = Config::get('sortable');
        foreach ($template['spec'] as $tpl) {
            if (isset($array[$tpl]) and !empty($array[$tpl]) and !(count($array[$tpl]) == 1 and trim((string)end($array[$tpl])) == "")) {
                $filter[$tpl] = [];
				if ($tpl != 'mincost' and $tpl != 'maxcost') {
					foreach ($template['params'][$tpl] as $key=>$val) {
						if (in_array($val,$array[$tpl])) {
							$filter[$tpl][]=$val;
						}
					}
				} else {
					$filter[$tpl] = $array[$tpl];
				}
            }
        }
        return $filter;
    }

}