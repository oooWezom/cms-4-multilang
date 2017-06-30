<?php
    namespace Wezom\Modules\Seo\Controllers;

    use Core\Config;
    use Core\HTML;
    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\Files;
    use Core\Arr;
    use Core\Support;

    use Wezom\Modules\Seo\Models\Links AS Model;

    class Seofiles extends \Wezom\Modules\Base {

        public $tpl_folder = 'Seo/Files';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Список файлов');
            $this->_seo['title'] = __('Список файлов');
            $this->setBreadcrumbs(__('Список файлов'), 'wezom/seo_files/index');

        }

        function indexAction () {
           
            $files=Support::getFiles('', ['txt','html']);
			$this->_toolbar = Widgets::get( 'Toolbar_ListOnlyAdd', ['addLink' => '/wezom/seo_files/add']);
            $this->_content = View::tpl(
                [
                    'result' => $files,
                ], $this->tpl_folder.'/Index');
        }

        function addAction () {
            if (Arr::get( $_FILES['file'], 'tmp_name')) {
                $name = Arr::get( $_FILES['file'], 'name');
                if (is_file(HOST.'/'.$name)) {
                    Message::GetMessage(0, __('Файл с именем уже существует! Сначала удалите существующий файл!', [':name' => $name]));
                    HTTP::redirect('wezom/seo_files/index');
                }
                $filename = Files::uploadFileOriginal();
                if(is_file(HOST.'/'.$filename)) {
                    Message::GetMessage(1, __('Вы успешно добавили данные!'));
                } else {
                    Message::GetMessage(0, __('Не удалось добавить данные!'));
                }
                $this->redirectAfterSave(urlencode(base64_encode($filename)),'seo_files');
            } 
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/seo_files/index']);
            $this->_seo['h1'] = __('Добавление');
            $this->_seo['title'] = __('Добавление');
            $this->setBreadcrumbs(__('Добавление'), 'wezom/seo_files/add');
            $this->_content = View::tpl(
                [
                    'tpl_folder' => $this->tpl_folder,
                ], $this->tpl_folder.'/Add');
        }

        function editAction () {

            $name = base64_decode(rawurldecode((Route::param('filename'))));
            if (!is_file(HOST.'/'.$name)) {
                Message::GetMessage(0, __('Такого файла на существует!'));
                HTTP::redirect('wezom/seo_files/index');
            }
            
            if ($_POST) {
                $text = $_POST['FORM']['text'];
                file_put_contents(HOST.'/'.$name,$text);
                Message::GetMessage(1, __('Вы успешно изменили данные!'));
				$this->redirectAfterSave(Route::param('filename'),'seo_files');
            } 

            $text=file_get_contents(HOST.'/'.$name);
            
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => '/wezom/seo_files/index','noAdd'=>1]);
            $this->_seo['h1'] = __('Редактирование');
            $this->_seo['title'] = __('Редактирование');
            $this->setBreadcrumbs(__('Редактирование'), 'wezom/seo_files/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                [
                    'text' => $text,
                    'name' => $name,
                    'tpl_folder' => $this->tpl_folder,
                ], $this->tpl_folder.'/Form');
        }

        function deleteAction() {
            $name=base64_decode(rawurldecode((Route::param('filename'))));
            if(!is_file(HOST.'/'.$name)) {
                Message::GetMessage(0, __('Данные не существуют!'));
                HTTP::redirect('wezom/seo_files/index');
            }
            @unlink(HOST.'/'.$name);
            Message::GetMessage(1, __('Данные удалены!'));
            HTTP::redirect('wezom/seo_files/index');
        }

    }