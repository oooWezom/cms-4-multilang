<?php
    namespace Wezom\Modules\User\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;

    use Wezom\Modules\User\Models\Roles AS Model;
	use Wezom\Modules\User\Models\Admins;

    class Roles extends \Wezom\Modules\Base {

        public $tpl_folder = 'Users/Roles';

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Роли пользователей');
            $this->_seo['title'] = __('Роли пользователей');
            $this->setBreadcrumbs(__('Роли пользователей'), 'wezom/'.Route::controller().'/index');
        }


        function indexAction () {
            $result = Model::getBackendUsersRoles();
            $this->_toolbar = Widgets::get( 'Toolbar_ListOrders', ['add' => 1]);
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'pageName' => $this->_seo['h1'],
                ], $this->tpl_folder.'/Index');
        }


        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                unset($_POST['FORM']);
                $access = $_POST;
                if( Model::valid($post) ) {
                    $post['alias'] = 'admin';
                    $res = Model::update($post, Route::param('id'));
                    if($res) {
                        Model::setAccess($access, Route::param('id'));
                        Message::GetMessage(1, __('Вы успешно изменили данные!'));
                    } else {
                        Message::GetMessage(0, __('Не удалось изменить данные!'));
                    }
                    $this->redirectAfterSave(Route::param('id'));
                }
                $result = Arr::to_object($post);
            } else {
                $result = Model::getRow(Route::param('id'));
                if($result->alias != 'admin') {
                    return Config::error();
                }
                $access = Model::getAccess(Route::param('id'));
            }
            $this->_toolbar = Widgets::get( 'Toolbar_Edit' );
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/'.Route::controller().'/edit/'.(int) Route::param('id'));
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'access' => $access,
                ], $this->tpl_folder.'/Form');
        }


        function addAction () {
            $access = [];
            if ($_POST) {
                $post = $_POST['FORM'];
                unset($_POST['FORM']);
                $access = $_POST;
                if( Model::valid($post) ) {
                    $post['alias'] = 'admin';
                    $res = Model::insert($post);
                    if($res) {
                        Model::setAccess($access, $res);
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
            $this->_toolbar = Widgets::get( 'Toolbar_Edit' );
            $this->_seo['h1'] = __('Добавление');
            $this->_seo['title'] = __('Добавление');
            $this->setBreadcrumbs(__('Добавление'), 'wezom/'.Route::controller().'/add');
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'access' => $access,
                ], $this->tpl_folder.'/Form');
        }


        function deleteAction() {
            $id = (int) Route::param('id');
            $page = Model::getRow($id);
            if(!$page) {
                Message::GetMessage(0, __('Данные не существуют!'));
                HTTP::redirect('wezom/'.Route::controller().'/index');
            }
			$countAdmins = Admins::countRowsByRole($page->id);
			if ($countAdmins>0) {
				Message::GetMessage(0, __('Нельзя удалять роль, к которой привязаны пользователи!'));
				HTTP::redirect('wezom/'.Route::controller().'/index');
			}
            Model::deleteImage($page->image);
            Model::delete($id);
            Message::GetMessage(1, __('Данные удалены!'));
            HTTP::redirect('wezom/'.Route::controller().'/index');
        }


    }