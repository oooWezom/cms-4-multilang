<?php

namespace Wezom\Modules\Blog\Controllers;

use Core\Config;
use Core\Route;
use Core\Widgets;
use Core\Message;
use Core\Arr;
use Core\HTTP;
use Core\View;
use Core\Pager\Pager;
use Wezom\Modules\Blog\Models\BlogRubrics AS Model;

class BlogRubrics extends \Wezom\Modules\Base {

    public $tpl_folder = 'Blog/Rubrics';
    public $limit;

    function before() {
        parent::before();
        $this->_seo['h1'] = __('Рубрики');
        $this->_seo['title'] = __('Рубрики');
        $this->setBreadcrumbs(__('Рубрики'), 'wezom/' . Route::controller() . '/index');
        $this->limit = (int) Arr::get($_GET, 'limit', Config::get('basic.limit_backend')) < 1 ?: Arr::get($_GET, 'limit', Config::get('basic.limit_backend'));
    }

    function indexAction() {
        $status = NULL;
        if (isset($_GET['status']) && $_GET['status'] != '') {
            $status = Arr::get($_GET, 'status', 1);
        }
        $result = Model::getRows($status, 'sort', 'ASC');
        $this->_toolbar = Widgets::get('Toolbar/List', ['add' => 1, 'delete' => 1]);
        $this->_content = View::tpl(
                        [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'pageName' => $this->_seo['h1'],
                        ], $this->tpl_folder . '/Index');
    }

    function editAction() {
        if ($_POST) {
            $post = $_POST['FORM'];
            $post['status'] = Arr::get($_POST, 'status', 0);
            if (Model::valid($post)) {
                $post['alias'] = Model::getUniqueAlias(Arr::get($post, 'alias'), Route::param('id'));
                $res = Model::update($post, Route::param('id'));
                if ($res) {
                    Message::GetMessage(1, __('Вы успешно изменили данные!'));
                    $this->redirectAfterSave(Route::param('id'));
                } else {
                    Message::GetMessage(0, __('Не удалось изменить данные!'));
                }
            }
            $result = Arr::to_object($post);
            $langs = [];
            foreach ($this->_languages as $key => $lang) {
                $langs[$key] = $result->$key;
                unset($result->key);
            }
            $obj = $result;
        } else {
            $result = Model::getRow((int) Route::param('id'));
            $obj = Arr::get($result, 'obj', []);
            $langs = Arr::get($result, 'langs', []);
        }
        $this->_toolbar = Widgets::get('Toolbar/Edit');
        $this->_seo['h1'] = __('Редактирование');
        $this->_seo['title'] = __('Редактирование');
        $this->setBreadcrumbs(__('Редактирование'), 'wezom/' . Route::controller() . '/edit/' . Route::param('id'));
        $this->_content = View::tpl(
                        [
                    'obj' => $obj,
                    'langs' => $langs,
                    'tpl_folder' => $this->tpl_folder,
                    'rubrics' => $this->rubrics,
                    'languages' => $this->_languages,
                        ], $this->tpl_folder . '/Form');
    }

    function addAction() {
        if ($_POST) {
            $post = $_POST['FORM'];
            $post['status'] = Arr::get($_POST, 'status', 0);
            if (Model::valid($post)) {
                $post['alias'] = Model::getUniqueAlias(Arr::get($post, 'alias'));
                $res = Model::insert($post);
                if ($res) {
                    Message::GetMessage(1, 'Вы успешно добавили данные!');
                } else {
                    Message::GetMessage(0, 'Не удалось добавить данные!');
                }
                $this->redirectAfterSave($res);
            }
            $result = Arr::to_object($post);
        } else {
            $result = [];
        }
        $this->_toolbar = Widgets::get('Toolbar/Edit');
        $this->_seo['h1'] = 'Добавление';
        $this->_seo['title'] = 'Добавление';
        $this->setBreadcrumbs('Добавление', 'wezom/' . Route::controller() . '/add');
        $this->_content = View::tpl(
                        [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'languages' => $this->_languages,
                        ], $this->tpl_folder . '/Form');
    }

    function deleteAction() {
        $id = (int) Route::param('id');
        $page = Model::getRow($id);
        if (!$page) {
            Message::GetMessage(0, 'Данные не существуют!');
            HTTP::redirect('wezom/' . Route::controller() . '/index');
        }
        Model::deleteImage($page->image);
        Model::delete($id);
        Message::GetMessage(1, 'Данные удалены!');
        HTTP::redirect('wezom/' . Route::controller() . '/index');
    }

}
