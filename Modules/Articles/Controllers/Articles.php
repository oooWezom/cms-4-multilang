<?php
namespace Modules\Articles\Controllers;

use Core\CommonI18n;
use Core\Route;
use Core\View;
use Core\Config;
use Core\Pager\Pager;
use Core\HTTP;
use Modules\Base;

use Modules\Content\Models\Control;

class Articles extends Base
{

    public $current;
    public $model;

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
        $this->_limit = (int)Config::get('basic.limit_articles');
        $this->_offset = ($this->_page - 1) * $this->_limit;
        $this->model = CommonI18n::factory('articles');
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
        // Get Rows
        $result = $this->model->getRows(1, 'id', 'DESC', $this->_limit, $this->_offset);
        // Get full count of rows
        $count = $this->model->countRows(1);
        // Generate pagination
        $this->_pager = Pager::factory($this->_page, $count, $this->_limit);
		//canonicals settings
		$this->_use_canonical=1;
		$this->_canonical='articles';
        // Render template
        $this->_content = View::tpl(['result' => $result, 'pager' => $this->_pager->create()], 'Articles/List');
    }

    public function innerAction()
    {
        if (Config::get('error')) {
            return false;
        }
        // Check for existance
        $obj = $this->model->getRowSimple(Route::param('alias'), 'alias');

        if (!$obj) {
            return Config::error();
        }
		if ($obj->status != 1) {
			HTTP::redirect('/articles', 301);
		}
        // Seo
        $this->_seo['h1'] = $obj->h1;
        $this->_seo['title'] = $obj->title;
        $this->_seo['keywords'] = $obj->keywords;
        $this->_seo['description'] = $obj->description;
        $this->setBreadcrumbs($obj->name);
        // Add plus one to views
        $obj = $this->model->addView($obj);
        // Render template
        $this->_content = View::tpl(['obj' => $obj], 'Articles/Inner');
		$this->_content.= View::tpl(['obj' => $obj], 'Articles/MicroData');
    }

}