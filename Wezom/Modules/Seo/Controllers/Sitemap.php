<?php
    namespace Wezom\Modules\Seo\Controllers;

    use Core\Config;
    use Core\HTML;
    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\Pager\Pager;
    use Core\Arr;

    use Wezom\Modules\Seo\Models\Sitemap AS Model;

    class Sitemap extends \Wezom\Modules\Base {

        public $tpl_folder = 'Seo/Sitemap';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Настройки карты сайта');
            $this->_seo['title'] = __('Настройки карты сайта');
            $this->setBreadcrumbs(__('Настройки карты сайта'), 'wezom/'.Route::controller().'/index');

        }

        function indexAction () {

			$result = Model::getRows();
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
					'tablename' => Model::$table,
                ], $this->tpl_folder.'/Index');
        }


    }