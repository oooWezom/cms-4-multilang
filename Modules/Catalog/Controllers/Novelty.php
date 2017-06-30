<?php
namespace Modules\Catalog\Controllers;

use Core\Route;
use Core\View;
use Core\Config;
use Core\Pager\Pager;
use Core\Arr;
use Modules\Base;
use Modules\Catalog\Models\Items;
use Modules\Content\Models\Control;

class Novelty extends Base
{

    public $current;
    public $sort;
    public $type;

    protected $_template = 'CatalogItemsWithoutFilter';

    public function before()
    {
        parent::before();
        $this->current = Control::getRowSimple('new', 'alias', 1);
        if (!$this->current) {
            return Config::error();
        }
        $this->setBreadcrumbs($this->current->name, $this->current->alias);
        $this->_page = !(int)Route::param('page') ? 1 : (int)Route::param('page');
        $this->_limit = (int)Arr::get($_GET, 'per_page') ? (int)Arr::get($_GET, 'per_page') : Config::get('basic.limit');
        $this->_offset = ($this->_page - 1) * $this->_limit;
        $this->sort = in_array(Arr::get($_GET, 'sort'), ['name', 'created_at', 'cost']) ? Arr::get($_GET, 'sort') : 'id';
        $this->type = in_array(strtolower(Arr::get($_GET, 'type')), ['asc', 'desc']) ? strtoupper(Arr::get($_GET, 'type')) : 'DESC';
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
        $result = Items::getItemsByFlag('new', $this->sort, $this->type, $this->_limit, $this->_offset);
        // Count of parent groups
        $count = Items::countItemsByFlag('new');
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $count, $this->_limit);
		//canonicals settings
		$this->_use_canonical=1;
		$this->_canonical='new';
        // Render template
        $this->_content = View::tpl(['result' => $result, 'pager' => $this->_pager->create()], 'Catalog/ItemsList');
    }

}