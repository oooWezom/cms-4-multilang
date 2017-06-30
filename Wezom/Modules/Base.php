<?php
namespace Wezom\Modules;

use Core\Common;
use Core\Config;
use Core\Encrypt;
use Core\Support;
use Core\View;
use Core\System;
use Core\Cron;
use Core\Route;
use Core\HTML;
use Core\HTTP;
use Core\QB\DB;
use Core\User;
use Core\Widgets;
use Core\Arr;
use Modules\Catalog\Models\Brands;
use Modules\Catalog\Models\Groups;

class Base
{

    protected $_template = 'Main';
    protected $_content;
    protected $_config = [];
    protected $_seo = [];
    protected $_breadcrumbs = [];
    protected $_filter = NULL;
    protected $_toolbar = NULL;
    protected $_languages = array();

    protected $_access = 'no';


    public function before()
    {
        $this->_initLang();
        $this->_languages = Config::get('languages') ? Config::get('languages') : array();
        User::factory()->is_remember();
        $this->redirects();
        $cron = new Cron;
        $cron->check();
        $this->config();
        $this->access();
        $this->ssl();
    }


    public function after()
    {
        $this->render();
    }

    private function ssl(){
        if(Config::get('security.ssl')){
            if(!$_SERVER['HTTPS']){
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            }
        } else {
            if($_SERVER['HTTPS']){
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            }
        }
    }

    private function _initLang()
    {
        if (!User::info()) {
            return \I18n::lang(\Core\Config::get('i18n.default'));
        }
        $lang = \Core\Cookie::get('backend_lang');
        if (!$lang || !\Core\Config::get('i18n.languages.' . $lang)) {
            $lang = \Core\Config::get('i18n.default');
        }
        \Core\Cookie::set('backend_lang', $lang, 7 * 24 * 60 * 60);
        \I18n::lang($lang);
    }

    private function redirects()
    {
        if (!User::info() AND Route::controller() != 'auth') {
            HTTP::redirect('wezom/auth/login?ref=' . $_SERVER['REQUEST_URI']);
        }
    }

    public function access()
    {
        if (!User::info()) {
            return false;
        }
        $this->_access = User::caccess();
        if ($this->_access == 'no') {
            $this->no_access();
        }
        if ($this->_access == 'view' && Route::action() != 'index' && Route::action() != 'edit') {
            $this->no_access();
        }
    }

    public function no_access()
    {
        $this->_breadcrumbs = HTML::backendBreadcrumbs($this->_breadcrumbs);
        $data = [];
        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }
        $data['_seo']['h1'] = 'Ошибка';
        $data['_content'] = Widgets::get('NoAccess');
        ob_start();
        extract($data, EXTR_SKIP);
        include(HOST . DS . 'Wezom' . DS . 'Views' . DS . 'Main.php');
        echo ob_get_clean();
        die;
    }

    private function config()
    {
        $result = DB::select('key', 'zna', 'group')
            ->from('config')
            ->join('config_groups')->on('config.group', '=', 'config_groups.alias')
            ->where('config.status', '=', 1)
            ->where('config_groups.status', '=', 1)
            ->find_all();
        $groups = [];
        foreach ($result AS $obj) {
            $groups[$obj->group][$obj->key] = $obj->zna;
        }
        foreach ($groups AS $key => $value) {
            Config::set($key, $value);
        }
        $this->setBreadcrumbs(__('Главная'), 'wezom');
    }

    private function render()
    {

        $help = DB::select('link')
            ->from('instructions')
            ->where('instructions.module', '=', Route::controller())
            ->find();

        $this->_breadcrumbs = HTML::backendBreadcrumbs($this->_breadcrumbs, $help->link);
        $data = [];
        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }
        echo View::tpl($data, $this->_template);
        echo System::global_massage();
    }

    protected function setBreadcrumbs($name, $link = NULL)
    {
        $this->_breadcrumbs[] = ['name' => $name, 'link' => $link];
    }


    protected function generateParentBreadcrumbs($id, $table, $parentAlias, $pre = '/')
    {
        $bread = $this->generateParentBreadcrumbsElement($id, $table, $parentAlias, []);
        if ($bread) {
            $bread = array_reverse($bread);
        }
        foreach ($bread as $obj) {
            $this->setBreadcrumbs($obj->h1, $pre . $obj->alias);
        }
    }

    protected function generateParentBreadcrumbsElement($id, $table, $parentAlias, $bread)
    {
        $page = DB::select('id', $parentAlias, 'alias', 'status', 'h1')->from($table)->where('id', '=', $id)->as_object()->execute()->current();
        if (is_object($page) AND $page->status) {
            $bread[] = $page;
        }
        if (is_object($page) AND (int)$page->$parentAlias > 0) {
            return $this->generateParentBreadcrumbsElement($page->$parentAlias, $table, $parentAlias, $bread);
        }
        return $bread;
    }
	
	public function redirectAfterSave($id, $controller=NULL) {
		
		if ($controller===NULL) {
			$controller=Route::controller();
		}
		
		switch (Arr::get($_POST, 'button', 'save')) {
			case 'save-close':
				HTTP::redirect('wezom/' . $controller . '/index');
				break;
			case 'save-add':
				HTTP::redirect('wezom/' . $controller . '/add');
				break;
			default:
				HTTP::redirect('wezom/' . $controller . '/edit/' . $id);
				break;
		}
		
	}

}