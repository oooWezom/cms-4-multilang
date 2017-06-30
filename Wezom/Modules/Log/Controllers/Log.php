<?php
    namespace Wezom\Modules\Log\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Arr;
    use Core\View;
    use Core\Pager\Pager;

    use Wezom\Modules\Log\Models\Log AS Model;

    class Log extends \Wezom\Modules\Base {

        public $tpl_folder = 'Log';
        public $tablename = 'log';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Лента событий');
            $this->_seo['title'] = __('Лента событий');
            $this->setBreadcrumbs(__('Лента событий'), 'wezom/'.Route::controller().'/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = Config::get('basic.limit_backend');
            $this->offset = ($this->page - 1) * $this->limit;
        }

        function indexAction () {
            $date_s = NULL; $date_po = NULL;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }
            $count = Model::countRows($date_s, $date_po);
            $result = Model::getRows($date_s, $date_po, 'created_at', 'DESC', $this->limit, $this->offset);
            $pager = Pager::factory( $this->page, $count, $this->limit )->create();
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                ], $this->tpl_folder.'/Index');
        }

    }