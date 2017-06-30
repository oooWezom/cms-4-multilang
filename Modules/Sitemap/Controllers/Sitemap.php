<?php
namespace Modules\Sitemap\Controllers;

use Core\CommonI18n;
use Core\Config;
use Core\Route;
use Core\View;
use Modules\Base;
use Modules\Catalog\Models\Brands;
use Modules\Catalog\Models\Groups;
use Modules\Content\Models\Control;
use Modules\News\Models\News;
use Modules\Sitemap\Models\Sitemap as Model;

class Sitemap extends Base
{

    public $current;

    public function before()
    {
        parent::before();
        $this->current = Control::getRowSimple(Route::controller(), 'alias', 1);
        if (!$this->current) {
            return Config::error();
        }
        $this->setBreadcrumbs($this->current->name, $this->current->alias);
    }

    // Search list
    public function indexAction()
    {
        if (Config::get('error')) {
            return false;
        }
        // Seo
        $this->_seo['h1'] = $this->current->h1;
        $this->_seo['title'] = $this->current->title;
        $this->_seo['keywords'] = $this->current->keywords;
        $this->_seo['description'] = $this->current->description;
		
		$map = Model::getRows(1,'sort','ASC');
		$arr = [];
		foreach ($map as $obj) {
			$arr[$obj->parent_id][] = $obj;
		}
		
		$links = [];
		if (isset($map['content'])) {
			$result = CommonI18n::factory('content')->getRows(1, 'sort', 'ASC');
			$pages = [];
			foreach ($result as $obj) {
				$pages[$obj->parent_id][] = $obj;
			}
			$links['content'] = $pages;
		}
		
		if (isset($map['news_list'])) {
			$list = News::getRows(1, 'date', 'DESC');
			$links['news_list'] = $list;
		}
		
		if (isset($map['articles_list'])) {
			$list = CommonI18n::factory('articles')->getRows(1, 'id', 'DESC');
			$links['articles_list'] = $list;
		}
		
		if (isset($map['blog_rubrics'])) {
			$list = CommonI18n::factory('blog_rubrics')->getRows(1, 'sort', 'ASC');
			$links['blog_rubrics'] = $list;
		}
		
		if (isset($map['blog_list'])) {
			$list = CommonI18n::factory('blog')->getRows(1, 'date', 'DESC');
			$links['blog_list'] = $list;
		}
		
		if (isset($map['gallery_list'])) {
			$list = CommonI18n::factory('gallery')->getRows(1, 'sort', 'ASC');
			$links['gallery_list'] = $list;
		}
		
		if (isset($map['catalog_groups'])) {
			$list =Groups::getRows(1, 'sort', 'ASC');
			
			$pages = [];
			foreach ($list as $obj) {
				$pages[$obj->parent_id][] = $obj;
			}
			$links['catalog_groups'] = $pages;
		}
		if (isset($map['catalog_items'])) {
			$list = CommonI18n::factory('catalog')->getRows(1, 'sort', 'ASC');
			$pages = [];
			foreach ($list as $obj) {
				$pages[$obj->parent_id][] = $obj;
			}
			$links['catalog_items'] = $pages;
		}
		
		if (isset($map['brands_list'])) {
			$list = Brands::getRows(1, 'brands.sort', 'ASC');
			$links['brands_list'] = $list;
		}
        // Render page
        $this->_content = View::tpl(['result' => $arr, 'links'=>$links], 'Sitemap/Index');
    }
	
}