<?php
namespace Modules\Reviews\Models;

use Core\QB\DB;
use Core\Common;

class Reviews extends Common
{

    public static $table = 'reviews';
	
	public static function getLast() {
		
		$result = DB::select()
					->from(static::$table)
					->order_by('date','DESC')
					->find();
					
		return $result;
		
	}

}