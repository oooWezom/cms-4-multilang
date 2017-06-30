<?php
    namespace Wezom\Modules\Content\Controllers;

    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;

    use Wezom\Modules\Content\Models\Control AS Model;

    class Control extends \Wezom\Modules\Base {

        public $tpl_folder = 'Content/Control';

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Системные страницы');
            $this->_seo['title'] = __('Системные страницы');
            $this->setBreadcrumbs(__('Системные страницы'), 'wezom/'.Route::controller().'/index');
        }

        function indexAction () {
            $result = Model::getRows(NULL, 'control_i18n.name', 'ASC');
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
                if( Model::valid($post) ) {
                    $res = Model::update($post, Route::param('id'));
                    if($res) {
                        Message::GetMessage(1, __('Вы успешно изменили данные!'));
                    } else {
                        Message::GetMessage(0, __('Не удалось изменить данные!'));
                    }
                    $this->redirectAfterSave(Route::param('id'));
                }
                $result = Arr::to_object($post);
                $langs = [];
                foreach ($this->_languages as $key => $lang) {
                    $langs[$key] = $result->$key;
                    unset($result->key);
                }
                $obj = $result;
            } else {
                $result = Model::getRow(Route::param('id'));
                $obj = Arr::get($result, 'obj', []);
                $langs = Arr::get($result, 'langs', []);
            }
            $this->_toolbar = Widgets::get('Toolbar/Edit', ['noAdd' => true]);
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/'.Route::controller().'/index');
            $this->_content = View::tpl(
                [
                    'obj' => $obj,
                    'langs' => $langs,
                    'tpl_folder' => $this->tpl_folder,
                    'languages' => $this->_languages,
                ], $this->tpl_folder.'/Form');
        }

    }