<?php
    namespace Wezom\Modules\Seo\Controllers;

    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\Arr;

    use Wezom\Modules\Seo\Models\Scripts AS Model;

    class Scripts extends \Wezom\Modules\Base {

        public $tpl_folder = 'Seo/Scripts';

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Метрика и счетчики');
            $this->_seo['title'] = __('Метрика и счетчики');
            $this->setBreadcrumbs(__('Метрика и счетчики'), 'wezom/seo_scripts/index');
        }

        function indexAction () {
            $result = Model::getRows(NULL, 'id', 'DESC');
            $this->_filter = Widgets::get( 'Filter_Pages' );
            $this->_toolbar = Widgets::get( 'Toolbar_List', ['addLink' => '/wezom/seo_scripts/add', 'delete' => 1]);
            $this->_content = View::tpl(
                [
                    'result'        => $result,
                    'tpl_folder'    => $this->tpl_folder,
                    'tablename'     => Model::$table,
                ], $this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( Model::valid($post) ) {
                    $res = Model::update($post, Route::param('id'));
                    if($res) {
                        Message::GetMessage(1, __('Вы успешно изменили данные!'));
                    } else {
                        Message::GetMessage(0, __('Не удалось изменить данные!'));
                    }
                    $this->redirectAfterSave(Route::param('id'),'seo_scripts');
                }
                $result     = Arr::to_object($post);
            } else {
                $result = Model::getRow(Route::param('id'));
            }
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/seo_scripts/index']);
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/seo_scripts/edit/'.Route::param('id'));
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
                    $res = Model::insert($post);
                    if($res) {
                        Message::GetMessage(1, __('Вы успешно добавили данные!'));
						$this->redirectAfterSave($res,'seo_scripts');
                    } else {
                        Message::GetMessage(0, __('Не удалось добавить данные!'));
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = [];
            }
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/seo_scripts/index']);
            $this->_seo['h1'] = __('Добавление');
            $this->_seo['title'] = __('Добавление');
            $this->setBreadcrumbs(__('Добавление'), 'wezom/seo_scripts/add');
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
                HTTP::redirect('wezom/seo_scripts/index');
            }
            Model::delete($id);
            Message::GetMessage(1, __('Данные удалены!'));
            HTTP::redirect('wezom/seo_scripts/index');
        }

    }