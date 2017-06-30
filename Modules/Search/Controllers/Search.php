<?php
namespace Modules\Search\Controllers;

use Core\HTML;
use Core\Route;
use Core\View;
use Core\Arr;
use Core\Config;
use Core\Pager\Pager;
use Modules\Base;
use Modules\Content\Models\Control;
use Modules\Catalog\Models\Items;

class Search extends Base
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
        $this->sort = in_array(Arr::get($_GET, 'sort'), ['name', 'created_at', 'cost']) ? Arr::get($_GET, 'sort') : 'sort';
        $this->type = in_array(strtolower(Arr::get($_GET, 'type')), ['asc', 'desc']) ? strtoupper(Arr::get($_GET, 'type')) : 'ASC';
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
        // Check query
        $query = Arr::get($_GET, 'query');
        if (!$query) {
            return $this->_content = $this->noResults();
        }
        $queries = Items::getQueries($query);
        // Get items list
        $result = Items::searchRows($queries, $this->_limit, $this->_offset);
        // Check for empty list
        if (!count($result)) {
            return $this->_content = $this->noResults();
        }
        // Count of parent groups
        $count = Items::countSearchRows($queries);
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $count, $this->_limit);
        // Render page
        $this->_content = View::tpl(['result' => $result, 'pager' => $this->_pager->create()], 'Catalog/ItemsList');
    }

    public function clean_array_to_search($words = [], $max = 0, $min_length)
    {
        $result = [];
        $i = 0;
        foreach ($words as $key => $value) {
            if (strlen(trim($value)) >= $min_length) {
                $i++;
                if ($i <= $max) {
                    $result[] = trim($value);
                }
            }
        }
        return $result;
    }


    // This we will show when no results
    public function noResults()
    {
        return '<p>'.__('По Вашему запросу ничего не найдено!').'</p>';
    }

}