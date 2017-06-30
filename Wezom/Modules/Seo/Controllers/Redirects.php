<?php
    namespace Wezom\Modules\Seo\Controllers;

    use Core\Config;
    use Core\Pager\Pager;
    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\Arr;

    use Wezom\Modules\Seo\Models\Redirects AS Model;

    class Redirects extends \Wezom\Modules\Base {

        public $tpl_folder = 'Seo/Redirects';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Перенаправления сайта');
            $this->_seo['title'] = __('Перенаправления сайта');
            $this->setBreadcrumbs(__('Перенаправления сайта'), 'wezom/seo_redirects/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = Config::get('basic.limit_backend');
            $this->offset = ($this->page - 1) * $this->limit;
        }

        function indexAction () {
            $status = NULL;
            if ( isset($_GET['status']) && $_GET['status'] != '' ) { $status = Arr::get($_GET, 'status', 1); }
            $count = Model::countRows($status);
            $result = Model::getRows($status, 'id', 'DESC', $this->limit, $this->offset);
            $pager = Pager::factory( $this->page, $count, $this->limit )->create();
            $this->_toolbar = Widgets::get( 'Toolbar_List', ['addLink' => '/wezom/seo_redirects/add', 'delete' => 1]);
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                ], $this->tpl_folder.'/Index');
        }

        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $arr = explode('/', $post['link_from']);
                if( $arr[0] == 'http' ) { unset($arr[0], $arr[1]); $post['link_from'] = implode('/', $arr); }
                $post['link_from'] = '/'.trim($post['link_from'], '/');
                $arr = explode('/', $post['link_to']);
                if( $arr[0] == 'http' ) { unset($arr[0], $arr[1]); $post['link_to'] = implode('/', $arr); }
                $post['link_to'] = '/'.trim($post['link_to'], '/');
                if( Model::valid($post) ) {
                    $res = Model::insert($post);
                    if($res) {
                        Message::GetMessage(1, __('Вы успешно добавили данные!'));
						$this->redirectAfterSave($res,'seo_redirects');
                    } else {
                        Message::GetMessage(0, __('Не удалось добавить данные!'));
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = [];
            }
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/seo_redirects/index']);
            $this->_seo['h1'] = __('Добавление');
            $this->_seo['title'] = __('Добавление');
            $this->setBreadcrumbs(__('Добавление'), 'wezom/seo_redirects/add');
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                ], $this->tpl_folder.'/Form');
        }

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $arr = explode('/', $post['link_from']);
                if( $arr[0] == 'http' ) { unset($arr[0], $arr[1]); $post['link_from'] = implode('/', $arr); }
                $post['link_from'] = '/'.trim($post['link_from'], '/');
                $arr = explode('/', $post['link_to']);
                if( $arr[0] == 'http' ) { unset($arr[0], $arr[1]); $post['link_to'] = implode('/', $arr); }
                $post['link_to'] = '/'.trim($post['link_to'], '/');
                if( Model::valid($post) ) {
                    $res = Model::update($post, Route::param('id'));
                    if($res) {
                        Message::GetMessage(1, __('Вы успешно изменили данные!'));
                    } else {
                        Message::GetMessage(0, __('Не удалось изменить данные!'));
                    }
                    $this->redirectAfterSave(Route::param('id'),'seo_redirects');
                }
                $result = Arr::to_object($post);
            } else {
                $result = Model::getRow(Route::param('id'));
            }
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/seo_redirects/index']);
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/seo_redirects/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                ], $this->tpl_folder.'/Form');
        }

        function deleteAction() {
            $id = (int) Route::param('id');
            $page = Model::getRow($id);
            if(!$page) {
                Message::GetMessage(0, __('Данные не существуют!'));
                HTTP::redirect('wezom/seo_redirects/index');
            }
            Model::delete($id);
            Message::GetMessage(1, __('Данные удалены!'));
            HTTP::redirect('wezom/seo_redirects/index');
        }

    }