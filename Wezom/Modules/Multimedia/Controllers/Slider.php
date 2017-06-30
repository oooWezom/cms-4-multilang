<?php

namespace Wezom\Modules\Multimedia\Controllers;

use Core\Config;
use Core\Pager\Pager;
use Core\Route;
use Core\Widgets;
use Core\Message;
use Core\Arr;
use Core\Image;
use Core\HTTP;
use Core\View;
use Wezom\Modules\Multimedia\Models\Slider AS Model;

class Slider extends \Wezom\Modules\Base {

    public $tpl_folder = 'Multimedia/Slider';
    public $page;
    public $limit;
    public $offset;

    function before() {
        parent::before();
        $this->_seo['h1'] = __('Слайдшоу');
        $this->_seo['title'] = __('Слайдшоу');
        $this->setBreadcrumbs(__('Слайдшоу'), 'wezom/' . Route::controller() . '/index');
        $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
        $this->limit = Config::get('basic.limit_backend');
        $this->offset = ($this->page - 1) * $this->limit;
    }

    function indexAction() {
        $result = Model::getRows(NULL, 'sort', 'ASC');
        $this->_filter = Widgets::get('Filter_Pages');
        $this->_toolbar = Widgets::get('Toolbar_List', ['add' => 1, 'delete' => 1]);
        $this->_content = View::tpl([
                    'result' => $result,                   
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
        ], $this->tpl_folder . '/Index');
    }

    function editAction() {
        if ($_POST) {
            $post = $_POST['FORM'];
            $post['status'] = Arr::get($_POST, 'status', 0);
            if (Model::valid($post)) {
                $res = Model::update($post, Route::param('id'));
                if ($res) {
                    Model::uploadImage(Route::param('id'));
                    Message::GetMessage(1, __('Вы успешно изменили данные!'));
					$this->redirectAfterSave(Route::param('id'));
                } else {
                    Message::GetMessage(0, __('Не удалось изменить данные!'));
                }
            }
            $result     = Arr::to_object($post);
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
        $this->_toolbar = Widgets::get('Toolbar_Edit');
        $this->_seo['h1'] = __('Редактирование');
        $this->_seo['title'] = __('Редактирование');
        $this->setBreadcrumbs(__('Редактирование'), 'wezom/' . Route::controller() . '/edit/' . Route::param('id'));
        $this->_content = View::tpl(
                        [
                    'obj' => $obj,
                    'langs' => $langs,
                    'tpl_folder' => $this->tpl_folder,
                    'languages' => $this->_languages,
                        ], $this->tpl_folder . '/Form');
    }

    function addAction() {
        if ($_POST) {
            $post = $_POST['FORM'];
            $post['status'] = Arr::get($_POST, 'status', 0);
            if (Model::valid($post)) {
                $res = Model::insert($post);
                if ($res) {
                    Model::uploadImage($res);
                    Message::GetMessage(1, __('Вы успешно добавили данные!'));
                } else {
                    Message::GetMessage(0, __('Не удалось добавить данные!'));
                }
                $this->redirectAfterSave($res);
            }
            $result = Arr::to_object($post);
        } else {
            $result = [];
        }
        $this->_toolbar = Widgets::get('Toolbar_Edit');
        $this->_seo['h1'] = __('Добавление');
        $this->_seo['title'] = __('Добавление');
        $this->setBreadcrumbs(__('Добавление'), 'wezom/' . Route::controller() . '/add');
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
            Message::GetMessage(0, __('Данные не существуют!'));
            HTTP::redirect('wezom/' . Route::controller() . '/index');
        }
        Model::deleteImage($page['obj']->image);
        Model::delete($id);
        Message::GetMessage(1, __('Данные удалены!'));
        HTTP::redirect('wezom/' . Route::controller() . '/index');
    }

    function deleteImageAction() {
        $id = (int) Route::param('id');
        $page = Model::getRow($id);
        if (!$page) {
            Message::GetMessage(0, __('Данные не существуют!'));
            HTTP::redirect('wezom/' . Route::controller() . '/index');
        }
        Model::deleteImage($page['obj']->image);
        Message::GetMessage(1, __('Данные удалены!'));
        HTTP::redirect('wezom/' . Route::controller() . '/edit/' . $id);
    }

}
