<?php
namespace Modules\Catalog\Controllers;

use Core\CommonI18n;
use Core\Route;
use Core\View;
use Core\Config;
use Core\Pager\Pager;
use Core\Arr;
use Core\Text;
use Core\Common;
use Core\HTTP;
use Modules\Base;
use Modules\Content\Models\Control;
use Modules\Catalog\Models\Items;
use Modules\Catalog\Models\Brands AS Model;

class Brands extends Base
{

    public $current;
    public $sort;
    public $type;


    public function before()
    {
        parent::before();
        $this->current = Control::getRowSimple(Route::controller(), 'alias', 1);
        if (!$this->current) {
            return Config::error();
        }
        $this->setBreadcrumbs($this->current->name, $this->current->alias);
        $this->_template = 'CatalogItemsWithoutFilter';
        $this->_page = !(int)Route::param('page') ? 1 : (int)Route::param('page');
        $this->_limit = (int)Arr::get($_GET, 'per_page') ? (int)Arr::get($_GET, 'per_page') : Config::get('basic.limit');
        $this->_offset = ($this->_page - 1) * $this->_limit;
        $this->sort = in_array(Arr::get($_GET, 'sort'), ['name', 'created_at', 'cost']) ? Arr::get($_GET, 'sort') : 'id';
        $this->type = in_array(strtolower(Arr::get($_GET, 'type')), ['asc', 'desc']) ? strtoupper(Arr::get($_GET, 'type')) : 'DESC';
    }

    // Brands list page
    public function indexAction()
    {
        if (Config::get('error')) {
            return false;
        }
        $this->_template = 'Text';
        // Seo
        $this->_seo['h1'] = $this->current->h1;
        $this->_seo['title'] = $this->current->title;
        $this->_seo['keywords'] = $this->current->keywords;
        $this->_seo['description'] = $this->current->description;
        // Get brands list
        $result = Model::getRows(1, 'brands_i18n.name');
        // Get alphabet
        $alphabet = Text::get_alphabet($result);
        $this->_content = View::tpl(['alphabet' => $alphabet], 'Brands/List');
    }


    // Items page
    public function innerAction()
    {
        if (Config::get('error')) {
            return false;
        }
        $this->_template = 'CatalogItemsWithoutFilter';
        // Check for existance
        $brand = Model::getRowSimple(Route::param('alias'), 'alias');
        if (!$brand) {
            return Config::error();
        }
		if ($brand->status != 1) {
			HTTP::redirect('/brands',301);
		}
        // Seo
        $this->_seo['h1'] = $brand->h1;
        $this->_seo['title'] = $brand->title;
        $this->_seo['keywords'] = $brand->keywords;
        $this->_seo['description'] = $brand->description;
        $this->setSeoForBrand($brand);
        // Get popular items
        $result = Items::getBrandItems($brand->alias, $this->sort, $this->type, $this->_limit, $this->_offset);
        // Set description of the brand to show it above the sort part
        Config::set('brand_description', View::tpl(['brand' => $brand], 'Brands/Inner'));
        // Count of parent groups
        $count = Items::countBrandItems($brand->alias);
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $count, $this->_limit);
		//canonicals settings
		$this->_use_canonical=1;
		$this->_canonical='brands/'.Route::param('alias');
        // Render template
        $this->_content = View::tpl(['result' => $result, 'pager' => $this->_pager->create()], 'Catalog/ItemsList');
    }
	
	// Set seo tags from template for brands
    public function setSeoForBrand($page)
    {
        $tpl = CommonI18n::factory('seo_templates')->getRowSimple(3);
        $from = ['{{name}}'];
        $to = [$page->name];
        $this->_seo['h1'] = $page->h1 ? str_replace($from, $to, $page->h1) : str_replace($from, $to, $tpl->h1);
        $this->_seo['title'] = $page->title ? str_replace($from, $to, $page->title) : str_replace($from, $to, $tpl->title);
        $this->_seo['keywords'] = $page->keywords ? str_replace($from, $to, $page->keywords) : str_replace($from, $to, $tpl->keywords);
        $this->_seo['description'] = $page->description ? str_replace($from, $to, $page->description) : str_replace($from, $to, $tpl->description);
		$this->setBreadcrumbs($page->name);
    }

}