<?php
    namespace Wezom\Modules\Seo\Controllers;

    use Core\HTML;
    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\Arr;

    use Wezom\Modules\Seo\Models\Templates AS Model;

    class Templates extends \Wezom\Modules\Base {
        
        public $tpl_folder = 'Seo/Templates';

        function before() {
            parent::before();
            $this->_seo['h1'] = __('СЕО шаблоны');
            $this->_seo['title'] = __('СЕО шаблоны');
            $this->setBreadcrumbs(__('СЕО шаблоны'), 'wezom/seo_templates/index');
        }

        function indexAction () {
            $result = Model::getRows(NULL, 'id', 'DESC');
            $this->_filter = Widgets::get( 'Filter_Pages' );
            $this->_toolbar = Widgets::get( 'Toolbar_List' );
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
                $res = Model::update($post, Route::param('id'));
                if($res) {
                    Message::GetMessage(1, __('Вы успешно изменили данные!'));
                } else {
                    Message::GetMessage(0, __('Не удалось изменить данные!'));
                }
                $this->redirectAfterSave(Route::param('id'),'seo_templates');
                $result     = Arr::to_object($post);
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
            
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/seo_templates/index', 'noAdd' => true]);
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/seo_templates/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                [
                    'obj' => $obj,
                    'langs' => $langs,
                    'tpl_folder' => $this->tpl_folder,
                    'languages' => $this->_languages,
                ], $this->tpl_folder.'/Form');
        }
        
    } 