<?php
    namespace Wezom\Modules\User\Controllers;

    use Wezom\Modules\User\Models\Roles;
    use Core\Common;
    use Core\Config;
    use Core\Email;
    use Core\HTTP;
    use Core\Message;
    use Core\Route;
    use Core\Arr;
    use Core\Image;
    use Core\System;
    use Core\User;
    use Core\View;
    use Core\Pager\Pager;

    use Wezom\Modules\User\Models\Admins AS Model;
    use Core\Widgets;

    class Admins extends \Wezom\Modules\Base {

        public $tpl_folder = 'Users/Admins';
        public $page;
        public $limit;
        public $offset;
        public $aroles = [];

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Администраторы');
            $this->_seo['title'] = __('Администраторы');
            $this->setBreadcrumbs(__('Администраторы'), 'wezom/admins/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = Config::get('basic.limit_backend');
            $this->offset = ($this->page - 1) * $this->limit;
            $aroles = Roles::getBackendUsersRoles();
            foreach( $aroles AS $obj ) {
                $this->aroles[$obj->id] = $obj->name;
            }
        }


        function indexAction () {
            $status = NULL;
            if ( isset($_GET['status']) ) { $status = Arr::get($_GET, 'status', 1); }
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = Model::countRows($status);
            $result = Model::getRows($status, 'users.id', 'DESC', $this->limit, ($page - 1) * $this->limit);
            $pager = Pager::factory( $page, $count, $this->limit )->create();
            $this->_toolbar = Widgets::get( 'Toolbar_List', ['addLink' => '/wezom/admins/add']);
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                    'roles' => $this->aroles,
                ], $this->tpl_folder.'/Index');
        }


        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( Model::valid($post) ) {
                    $res = Model::insert($post);
                    if($res) {
                        User::factory()->update_password($res, Arr::get($_POST, 'password'));
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
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/admins/index']);
            $this->_seo['h1'] = __('Добавление');
            $this->_seo['title'] = __('Добавление');
            $this->setBreadcrumbs(__('Добавление'), 'wezom/admins/add');
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'roles' => $this->aroles,
                ], $this->tpl_folder.'/Form');
        }


        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( Model::valid($post) ) {
                    $res = Model::update($post, Route::param('id'));
                    if($res) {
                        if( trim(Arr::get($_POST, 'password')) ) {
                            User::factory()->update_password(Route::param('id'), Arr::get($_POST, 'password'));
                        }
                        Message::GetMessage(1, __('Вы успешно добавили данные!'));
                    } else {
                        Message::GetMessage(0, __('Не удалось добавить данные!'));
                    }
                    $this->redirectAfterSave(Route::param('id'));
                }
                $result = Arr::to_object($post);
            } else {
                $result = Model::getRow(Route::param('id'));
            }
            if( isset($result->deleted) && $result->deleted ) {
                $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/archive/admins']);
            } else {
                $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/admins/index']);
            }
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/admins/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'roles' => $this->aroles,
                ], $this->tpl_folder.'/Form');
        }

        function deleteAction() {
            $id = (int) Route::param('id');
            $page = Model::getRow($id);
            if(!$page) {
                Message::GetMessage(0, __('Данные не существуют!'));
                HTTP::redirect('wezom/admins/index');
            }
            Model::delete($id);
            Message::GetMessage(1, __('Данные удалены!'));
            HTTP::redirect('wezom/admins/index');
        }

    }