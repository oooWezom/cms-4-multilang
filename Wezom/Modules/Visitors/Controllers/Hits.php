<?php
    namespace Wezom\Modules\Visitors\Controllers;

    use Core\Route;
    use Core\Arr;
    use Core\Config;
    use Core\View;
    use Core\Pager\Pager;
    use Core\QB\DB;
    use Wezom\Modules\Visitors\Models\Hits AS Model;

    class Hits extends \Wezom\Modules\Base {

        public $tpl_folder = 'Visitors';
        public $page;
        public $limit;
        public $offset;
        public $sort = 'updated_at';
        public $type = 'DESC';

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Переходы по сайту');
            $this->_seo['title'] = __('Переходы по сайту');
            $this->setBreadcrumbs(__('Переходы по сайту'), 'wezom/'.Route::controller().'/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = (int) Arr::get($_GET, 'limit', Config::get('basic.limit_backend')) < 1 ?: Arr::get($_GET, 'limit', Config::get('basic.limit_backend'));
            $this->offset = ($this->page - 1) * $this->limit;

            $sort = ['updated_at', 'created_at'];
            $type = ['DESC', 'ASC'];

            if (array_key_exists('sort', $_GET)) {
                $arr = explode('-', $_GET['sort']);
                if (count($arr) == 2) {
                    if (in_array($arr[0], $sort)) {
                        $this->sort = $arr[0];
                    }
                    if (in_array(strtoupper($arr[1]), $type)) {
                        $this->type = $arr[1];
                    }
                }
            }
        }


        function indexAction () {
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = Model::countRows();
            $result = Model::getRows(NULL, $this->sort, $this->type, $this->limit, ($page - 1) * $this->limit);
            $pager = Pager::factory( $page, $count, $this->limit )->create();
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                    'answers' => DB::select(DB::expr('DISTINCT status'))->from('visitors_hits')->find_all(),
                    'devices' => DB::select(DB::expr('DISTINCT device'))->from('visitors_hits')->find_all(),
                ], $this->tpl_folder.'/Hits');
        }

    }