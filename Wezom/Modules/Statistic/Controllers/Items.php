<?php
    namespace Wezom\Modules\Statistic\Controllers;

    use Core\Config;
    use Core\HTML;
    use Core\Route;
    use Core\Arr;
    use Core\Image;
    use Core\Support;
    use Core\View;
    use Core\Pager\Pager;

    use Wezom\Modules\Statistic\Models\Items AS Model;

    class Items extends \Wezom\Modules\Base {

        public $tpl_folder = 'Statistic';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Статистика по товарам');
            $this->_seo['title'] = __('Статистика по товарам');
            $this->setBreadcrumbs(__('Статистика по товарам'), 'wezom/statistic/items');
            $this->page = (int )Route::param('page') ?: 1;
            $this->limit = (int) Arr::get($_GET, 'limit', Config::get('basic.limit_backend')) ?: 1;
            $this->offset = ($this->page - 1) * $this->limit;
        }


        function indexAction() {
            $page = (int)Route::param('page') ? (int)Route::param('page') : 1;
            $count = Model::countRows();
            $result = Model::getRows($this->limit, ($page - 1) * $this->limit);
            $pager = Pager::factory($page, $count, $this->limit)->create();
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                    'tree' => Support::getSelectOptions('Catalog/Items/Select', 'catalog_tree', Arr::get($_GET, 'parent_id')),
                ], $this->tpl_folder . '/Items');
        }

    }