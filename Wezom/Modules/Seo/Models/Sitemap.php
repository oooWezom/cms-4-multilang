<?php
    namespace Wezom\Modules\Seo\Models;

    use Core\Arr;
    use Core\QB\DB;

    class Sitemap extends \Core\Common {

        public static $table = 'sitemap';

		public static function getRows() {
			
			$result = DB::select()
						->from(static::$table)
						->order_by('sort','ASC')
						->order_by('id','DESC')
						->find_all();
						
			$arr = [];
			foreach ($result as $obj) {
				$arr[$obj->parent_id][] = $obj;
			}
			
			return $arr;
			
		}
		
		public static function updateStatus($id, $status) {
			
			$ids = [];
			if ($status == 1) {
				$ids = Sitemap::getParents($id);
			} else {
				$ids = Sitemap::getChildren($id);
			}
			$ids[] = $id;
			DB::update(static::$table)->set(['status' => $status])->where('id','IN', $ids)->execute();
			return $ids;
		}
		
		public static function getParents($child_id, $parents = []) {
			
			$child = DB::select('parent_id')->from(static::$table)
							->where('id','=',$child_id)
							->find();
			
			if ($child and $child->parent_id!=0) {
				$parents[] = $child->parent_id;
				$parents = Sitemap::getParents($child->parent_id, $parents);
			}
			
			return $parents;	
			
		}
		
		public static function getChildren($parent_id, $children = []) {
			
			$list = DB::select('id')->from(static::$table)
							->where('parent_id','=',$parent_id)
							->find_all();
			
			if (sizeof($list)) {
				
				foreach ($list as $obj) {
					$children[] = $obj->id;
					$children = Sitemap::getChildren($obj->id,$children);
				}
				
			}
			
			return $children;	
			
		}


    }