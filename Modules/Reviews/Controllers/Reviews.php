<?php
namespace Modules\Reviews\Controllers;

use Core\Route;
use Core\View;
use Core\Config;
use Core\Pager\Pager;
use Modules\Base;
use Modules\Reviews\Models\Reviews AS Model;
use Modules\Content\Models\Control;

class Reviews extends Base
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
        $this->_template = 'Text';

        $this->_page = !(int)Route::param('page') ? 1 : (int)Route::param('page');
        $this->_limit = (int)Config::get('basic.limit_reviews');
        $this->_offset = ($this->_page - 1) * $this->_limit;
    }

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
        Config::set('content_class', 'news_block');
        // Get Rows
        $result = Model::getRows(1, 'date', 'DESC', $this->_limit, $this->_offset);
        // Get full count of rows
        $count = Model::countRows(1);
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $count, $this->_limit);
		//canonicals settings
		$this->_use_canonical=1;
		$this->_canonical = 'reviews';
        // Render template
        $this->_content = View::tpl(['result' => $result, 'pager' => $this->_pager->create()], 'Reviews/List');
    }

}