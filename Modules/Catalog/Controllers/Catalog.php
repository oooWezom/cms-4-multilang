<?php
namespace Modules\Catalog\Controllers;

use Core\Common;
use Core\CommonI18n;
use Core\HTML;
use Core\Route;
use Core\View;
use Core\Config;
use Core\Pager\Pager;
use Core\Arr;
use Modules\Catalog\Models\Filter;
use Core\Text;
use Core\HTTP;
use Modules\Base;
use Modules\Catalog\Models\Groups AS Model;
use Modules\Content\Models\Control;

class Catalog extends Base
{

    public $current;
    public $sort;
    public $type;
    protected $_template = 'Catalog';

    public function before()
    {
        parent::before();
        $this->current = Control::getRowSimple('products', 'alias', 1);
        if (!$this->current) {
            return Config::error();
        }
        $this->setBreadcrumbs($this->current->name, $this->current->alias);
        $this->_page = !(int)Route::param('page') ? 1 : (int)Route::param('page');
        $limit = Config::get('basic.limit_groups');
        $sort = 'sort';
        $type = 'ASC';
        $this->_limit = (int)Arr::get($_GET, 'per_page') ? (int)Arr::get($_GET, 'per_page') : $limit;
        $this->_offset = ($this->_page - 1) * $this->_limit;
        $this->sort = in_array(Arr::get($_GET, 'sort'), ['name', 'created_at', 'cost']) ? Arr::get($_GET, 'sort') : $sort;
        $this->type = in_array(strtolower(Arr::get($_GET, 'type')), ['asc', 'desc']) ? strtoupper(Arr::get($_GET, 'type')) : $type;
    }


    // Catalog main page with groups where parent_id = 0
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
        // Get groups with parent_id = 0
        $result = Model::getInnerGroups(0, $this->sort, $this->type, $this->_limit, $this->_offset);
        // Count of parent groups
        $count = Model::countInnerGroups(0);
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $count, $this->_limit);
		//canonicals settings
		$this->_use_canonical=1;
		$this->_canonical='products';
        // Render template
        $this->_content = View::tpl(['result' => $result, 'pager' => $this->_pager->create()], 'Catalog/Groups');
    }


    // Page with groups list
    public function groupsAction()
    {
        if (Config::get('error')) {
            return false;
        }
        // Check for existance
        $group = Model::getRowSimple(Route::param('alias'), 'alias');
        if (!$group) {
            return Config::error();
        }
        if ($group->status != 1) {
			HTTP::redirect('/products',301);
		}
        Route::factory()->setParam('group', $group->id);
        // Count of child groups
        $count = Model::countInnerGroups($group->id);
        if (!$count) {
            return $this->listAction();
        }
        // Seo
        $this->setSeoForGroup($group);
        // Add plus one to views
        Model::addView($group);
        // Get groups list
        $result = Model::getInnerGroups($group->id, $this->sort, $this->type, $this->_limit, $this->_offset);
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $count, $this->_limit);
		//canonicals settings
		$this->_use_canonical=1;
		$this->_canonical='products/'.Route::param('alias');
        // Render template
        $this->_content = View::tpl(['result' => $result, 'pager' => $this->_pager->create()], 'Catalog/Groups');
    }


    // Items list page. Inside group
    public function listAction()
    {
		
        if (Config::get('error')) {
            return false;
        }
        $this->_template = 'ItemsList';
        Route::factory()->setAction('list');
        // Filter parameters to array if need
        $check = Filter::setFilterParameters();
		if ($check['success'] === false) {
			return Config::error();
		}
		if ($check['success'] === true and $check['resort'] === true) {
			$new_filter = Filter::getFilterFromArr(Config::get('filter_array'));
			$url = '/products/'.Route::param('alias').$new_filter;
			HTTP::redirect($url, 301);
		}
        // Set filter elements sortable
      //  Filter::setSortElements();
		
		//print_r(Route::param('filter')); die;
        // Check for existance
        $group = Model::getRowSimple(Route::param('alias'), 'alias');
        if (!$group) {
            return Config::error();
        }
		if ($group->status != 1) {
			HTTP::redirect('/products',301);
		}
        // Seo
        $this->setSeoForGroup($group);

        // Add plus one to views
        Model::addView($group);
        // Get items list
		$this->_limit = (int)Arr::get($_GET, 'per_page') ? (int)Arr::get($_GET, 'per_page') : Config::get('basic.limit');
        $this->_offset = ($this->_page - 1) * $this->_limit;
        $result = Filter::getFilteredItemsList($this->_limit, $this->_offset, $this->sort, $this->type);
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $result['total'], $this->_limit);
		//canonicals settings
		$this->_use_canonical=1;
		$this->_canonical='products/'.Route::param('alias');
        // Render page
        $this->_content = View::tpl(['result' => $result['items'], 'pager' => $this->_pager->create()], 'Catalog/ItemsList');
    }


    // Set seo tags from template for items groups
    public function setSeoForGroup($page)
    {
        $tpl = CommonI18n::factory('seo_templates')->getRowSimple(1);
        $from = ['{{name}}', '{{content}}'];
        $text = trim(strip_tags($page->text));
        $to = [$page->name, $text];
        $res = preg_match_all('/{{content:[0-9]*}}/', $tpl->description, $matches);
        if ($res) {
            $matches = array_unique($matches);
            foreach ($matches[0] AS $pattern) {
                preg_match('/[0-9]+/', $pattern, $m);
                $from[] = $pattern;
                $to[] = Text::limit_words($text, $m[0]);
            }
        }

        $title = $page->title ? $page->title : $tpl->title;
        $h1 = $page->h1 ? $page->h1 : $tpl->h1;
        $keywords = $page->keywords ? $page->keywords : $tpl->keywords;
        $description = $page->description ? $page->description : $tpl->description;

        $this->_seo['h1'] = str_replace($from, $to, $h1);
        $this->_seo['title'] = str_replace($from, $to, $title)
            . ((Arr::get($_GET, 'sort') == 'cost' && Arr::get($_GET, 'type') == 'asc') ? __(', От бютжетных к дорогим') : '')
            . ((Arr::get($_GET, 'sort') == 'cost' && Arr::get($_GET, 'type') == 'desc') ? __(', От дорогих к бютжетным') : '')
            . ((Arr::get($_GET, 'sort') == 'created_at' && Arr::get($_GET, 'type') == 'desc') ? __(', От новых моделей к старым') : '')
            . ((Arr::get($_GET, 'sort') == 'created_at' && Arr::get($_GET, 'type') == 'asc') ? __(', От старых моделей к новым') : '')
            . ((Arr::get($_GET, 'sort') == 'name' && Arr::get($_GET, 'type') == 'asc') ? __(', По названию от А до Я') : '')
            . ((Arr::get($_GET, 'sort') == 'name' && Arr::get($_GET, 'type') == 'desc') ? __(', По названию от Я до А') : '');
        $this->_seo['keywords'] = str_replace($from, $to, $keywords);
        $this->_seo['description'] = str_replace($from, $to, $description);
        $this->_seo['seo_text'] = $page->text;
        $this->generateParentBreadcrumbs($page->parent_id, 'catalog_tree', 'parent_id', '/products/');
        $this->setBreadcrumbs($page->name);
    }

}