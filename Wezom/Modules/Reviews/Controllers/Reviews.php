<?php
    namespace Wezom\Modules\Reviews\Controllers;

    use Core\Config;
    use Core\Pager\Pager;
    use Core\Route;
    use Core\Validation\Rules;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;

    use Wezom\Modules\Reviews\Models\Reviews AS Model;

    class Reviews extends \Wezom\Modules\Base {

        public $tpl_folder = 'Reviews';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Отзывы клиентов');
            $this->_seo['title'] = __('Отзывы клиентов');
            $this->setBreadcrumbs(__('Отзывы клиентов'), 'wezom/'.Route::controller().'/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = Arr::get($_GET, 'limit') > 0 ? (int) $_GET['limit'] : Config::get('basic.limit_backend');
            $this->offset = ($this->page - 1) * $this->limit;
        }

        function indexAction () {
            $date_s = NULL; $date_po = NULL; $status = NULL;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }
            if ( isset($_GET['status']) && $_GET['status'] != '' ) { $status = Arr::get($_GET, 'status', 1); }
            $count = Model::countRows($status, $date_s, $date_po);
            $result = Model::getRows($status, $date_s, $date_po, 'date', 'DESC', $this->limit, $this->offset);
            $pager = Pager::factory( $this->page, $count, $this->limit )->create();
            $this->_toolbar = Widgets::get( 'Toolbar_List', ['delete' => 1, 'add' => 1]);
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

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( Model::valid($post) ) {
                    $post['date'] = strtotime($post['date']);
                    $post['date_answer'] = Rules::date($post['date_answer']) ? strtotime($post['date_answer']) : NULL;
                    $res = Model::update($post, Route::param('id'));
                    if($res) {
                        Message::GetMessage(1, __('Вы успешно изменили данные!'));
                    } else {
                        Message::GetMessage(0, __('Не удалось изменить данные!'));
                    }
                    $this->redirectAfterSave(Route::param('id'));
                }
                $result = Arr::to_object($post);
            } else {
                $result = Model::getRow((int) Route::param('id'));
            }
            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/'.Route::controller().'/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                ], $this->tpl_folder.'/Form');
        }

        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( Model::valid($post) ) {
                    $post['date'] = strtotime($post['date']);
                    $post['date_answer'] = Rules::date($post['date_answer']) ? strtotime($post['date_answer']) : NULL;
                    $res = Model::insert($post);
                    if($res) {
                        Message::GetMessage(1, __('Вы успешно добавили данные!'));
						$this->redirectAfterSave($res);
                    } else {
                        Message::GetMessage(0, __('Не удалось добавить данные!'));
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = [];
            }
            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = __('Добавление');
            $this->_seo['title'] = __('Добавление');
            $this->setBreadcrumbs(__('Добавление'), 'wezom/'.Route::controller().'/add');
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
                HTTP::redirect('wezom/'.Route::controller().'/index');
            }
            Model::delete($id);
            Message::GetMessage(1, __('Данные удалены!'));
            HTTP::redirect('wezom/'.Route::controller().'/index');
        }

    }