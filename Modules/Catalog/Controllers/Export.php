<?php
namespace Modules\Catalog\Controllers;

use Core\Common;
use Core\HTML;
use Core\Route;
use Core\View;
use Core\Config;
use Core\Pager\Pager;
use Core\Arr;
use Core\Text;
use Core\HTTP;
use Modules\Base;
use Modules\Catalog\Models\Groups;
use Modules\Catalog\Models\Items;


class Export extends Base
{

    public function before()
    {
        parent::before();
      
    }
	
	public function ymlAction() {
		
		$groups = Groups::getRows(1,'sort','ASC');
		$items = Items::getRows(1, 'sort', 'ASC');
		
		
		$dom = new \domDocument('1.0', 'utf-8');
		$root = $dom->createElement('yml_catalog'); 
		$root->setAttribute('date', date('Y-m-d H:i'));
		$shop = $dom->createElement('shop'); 
		
		//shop data
		$name = $dom->createElement('name', Config::get('export.name'));
		$shop->appendChild($name);
		$company = $dom->createElement('company', Config::get('export.company'));
		$shop->appendChild($company);
		$url = $dom->createElement('url', 'http://'.$_SERVER['HTTP_HOST'].'/');
		$shop->appendChild($url);
		$platform = $dom->createElement('platform', 'WEZOM CMS');
		$shop->appendChild($platform);
		$version = $dom->createElement('version', '4.0');
		$shop->appendChild($version);
		$agency = $dom->createElement('agency', 'WEZOM');
		$shop->appendChild($agency);
		$email = $dom->createElement('email', Config::get('export.email'));
		$shop->appendChild($email);
		
		//currencies
		$currencies = $dom->createElement('currencies');
		$currency = $dom->createElement('currency');
		$currency->setAttribute('id', 'UAH');
		$currency->setAttribute('rate', '1');
		$currencies->appendChild($currency);
		$shop->appendChild($currencies);
		
		//categories
		$categories = $dom->createElement('categories');
		foreach ($groups as $obj) {
			$category  = $dom->createElement('category', $obj->name);
			$category->setAttribute('id', $obj->id);
			if ($obj->parent_id >0) {
				$category->setAttribute('parent_id', $obj->parent_id);
			}
			$categories->appendChild($category);
		}
		
		// товары
		$offers = $dom->createElement('offers');
		foreach ($items as $obj) {
			$offer = $dom->createElement('offer');
			$offer->setAttribute('id', $obj->id);
			$offer->setAttribute('available', $obj->available==1 ? true : false);
			$url = $dom->createElement('url', 'http://'.$_SERVER['HTTP_HOST'].'/'.$obj->alias.'/p'.$obj->id);
			$offer->appendChild($url);
			$price = $dom->createElement('price', $obj->cost);
			$offer->appendChild($price);
			if ($obj->cost_old and $obj->sale) {
				$oldprice = $dom->createElement('oldprice', $obj->cost_old);
				$offer->appendChild($oldprice);
			}
			$currencyID = $dom->createElement('currencyID', 'UAH');
			$offer->appendChild($currencyID);
			$categoryID = $dom->createElement('categoryID', $obj->parent_id);
			$offer->appendChild($categoryID);
			
			$images = Items::getItemImages($obj->id);
			if (sizeof($images)) {
				foreach ($images as $image) {
					if (is_file(HOST.HTML::media('images/catalog/big/'.$image->image, false))) {
						$image = $dom->createElement('picture', HTML::media('images/catalog/big/'.$image->image, true));
						$offer->appendChild($image);
					}
				}
			}
			
			$name = $dom->createElement('name', htmlspecialchars($obj->name));
			$offer->appendChild($name);
			
			$vendor = $dom->createElement('vendor', htmlspecialchars($obj->brand_name));
			$offer->appendChild($vendor);
			
			if ($obj->model_name) {
				$model = $dom->createElement('model', htmlspecialchars($obj->model_name));
				$offer->appendChild($model);
			}
			
			$params = Items::getItemSpecifications($obj->id, $obj->parent_id);
			if (sizeof ($params)) {
				foreach ($params as $key=>$val) {
					$param = $dom->createElement('param', htmlspecialchars($val));
					$param->setAttribute('name', htmlspecialchars($key));
					$offer->appendChild($param);
				}
			}
			
			$offers->appendChild($offer);
		}
		
		$shop->appendChild($categories);
		$shop->appendChild($offers);
		$root->appendChild($shop);
		$dom->appendChild($root);
		$dom->save(HOST."/export/yml.xml");
		die('Файл выгрузки сгенерирован!');
		
	}

    
}