<?php

namespace Wezom\Modules\Blog\Controllers;

use Core\Common;
use Core\Config;
use Core\HTML;
use Core\Route;
use Core\Widgets;
use Core\Message;
use Core\Arr;
use Core\HTTP;
use Core\View;
use Core\Pager\Pager;
use Wezom\Modules\Base;
use Wezom\Modules\Blog\Models\Blog;
use Wezom\Modules\Blog\Models\BlogComments AS Model;
use Wezom\Modules\Blog\Models\BlogRubrics;

class BlogComments extends Base {

    public $tpl_folder = 'Blog/Comments';
    public $limit;

    function before() {
        parent::before();
        $this->_seo['h1'] = __('Комментарии');
        $this->_seo['title'] = __('Комментарии');
        $this->setBreadcrumbs(__('Комментарии'), 'wezom/' . Route::controller() . '/index');
        $this->limit = (int) Arr::get($_GET, 'limit', Config::get('basic.limit_backend')) < 1 ?: Arr::get($_GET, 'limit', Config::get('basic.limit_backend'));
    }

    function indexAction() {
        if (Arr::get($_GET, 'uid')) {
            $user = Common::factory('users')->getRow(Arr::get($_GET, 'uid'));
            if ($user) {
                $name = trim($user->last_name . ' ' . $user->name);
                $name = $name ?: '#' . $user->id;
                $this->setBreadcrumbs(($user->partner ? __('Комментарии партнера') : __('Комментарии пользователя')) . ' ' . $name);
            }
        }
        $date_s = NULL;
        $date_po = NULL;
        $status = NULL;
        if (Arr::get($_GET, 'date_s')) {
            $date_s = strtotime(Arr::get($_GET, 'date_s'));
        }
        if (Arr::get($_GET, 'date_po')) {
            $date_po = strtotime(Arr::get($_GET, 'date_po'));
        }
        if (isset($_GET['status']) && $_GET['status'] != '') {
            $status = Arr::get($_GET, 'status', 1);
        }
        $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
        $count = Model::countRows($status, $date_s, $date_po);
        $result = Model::getRows($status, $date_s, $date_po, 'id', 'DESC', $this->limit, ($page - 1) * $this->limit);
        $pager = Pager::factory($page, $count, $this->limit)->create();
        $this->_toolbar = Widgets::get('Toolbar/List', ['delete' => 1, 'add' => 1]);
        $this->_content = View::tpl(
                        [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                        ], $this->tpl_folder . '/Index');
    }

    function editAction() {
        if ($_POST) {
            $post = $_POST['FORM'];
            $post['status'] = Arr::get($_POST, 'status', 0);
            $post['blog_id'] = Arr::get($_POST, 'blog_id');
            if (Model::valid($post)) {
                $post['date'] = strtotime($post['date']);
                $post['date_answer'] = strtotime($post['date_answer']);
                $res = Model::update($post, Route::param('id'));
                if ($res) {
                    Message::GetMessage(1, __('Вы успешно изменили данные!'));
                } else {
                    Message::GetMessage(0, __('Не удалось изменить данные!'));
                }
                $this->redirectAfterSave(Route::param('id'));
            }
            $result = Arr::to_object($post);
        } else {
            $result = Model::getRow(Route::param('id'));
        }
        $this->_toolbar = Widgets::get('Toolbar_Edit');
        $this->_seo['h1'] = __('Редактирование');
        $this->_seo['title'] = __('Редактирование');

        if ($result->user_id) {
            $user = Common::factory('users')->getRow($result->user_id);
            if ($user) {
                $name = trim($user->last_name . ' ' . $user->name);
                $name = $name ?: '#' . $user->id;
                $this->setBreadcrumbs(($user->partner ? __('Комментарии партнера') : __('Комментарии пользователя')) . ' ' . $name, HTML::link('wezom/' . Route::controller() . '/index?uid=' . $user->id));
            }
        }
        $this->setBreadcrumbs(__('Редактирование'));

        $this->_content = View::tpl(
                        [
                    'obj' => $result,
                    'item' => Blog::getRowSimple($result->blog_id),
                    'tpl_folder' => $this->tpl_folder,
                    'rubrics' => BlogRubrics::getRows(NULL, 'sort', 'ASC', NULL, NULL, false),
                        ], $this->tpl_folder . '/Form');
    }

    function addAction() {
        if ($_POST) {
            $post = $_POST['FORM'];
            $post['status'] = Arr::get($_POST, 'status', 0);
            $post['blog_id'] = Arr::get($_POST, 'blog_id');
            $post['watched'] = 1;
            if (Model::valid($post)) {
                $post['date'] = strtotime($post['date']);
                $post['date_answer'] = strtotime($post['date_answer']) ? strtotime($post['date_answer']) : NULL;
                $res = Model::insert($post);
                if ($res) {
                    Message::GetMessage(1, __('Вы успешно добавили данные!'));
                    $this->redirectAfterSave($res);
                } else {
                    Message::GetMessage(0, __('Не удалось добавить данные!'));
                }
            }
            $result = Arr::to_object($post);
        } else {
            $result = Model::getRow(Route::param('id'));
        }
        $this->_toolbar = Widgets::get('Toolbar_Edit');
        $this->_seo['h1'] = __('Добавление');
        $this->_seo['title'] = __('Добавление');
        $this->setBreadcrumbs(__('Добавление'), 'wezom/' . Route::controller() . '/edit/' . Route::param('id'));
        $this->_content = View::tpl(
                        [
                    'obj' => $result,
                    'item' => Blog::getRow($result->blog_id),
                    'tpl_folder' => $this->tpl_folder,
                    'rubrics' => BlogRubrics::getRows(NULL, 'sort', 'ASC', NULL, NULL, false),
                        ], $this->tpl_folder . '/Form');
    }

    function deleteAction() {
        $id = (int) Route::param('id');
        $page = Model::getRow($id);
        if (!$page) {
            Message::GetMessage(0, __('Данные не существуют!'));
            HTTP::redirect('wezom/' . Route::controller() . '/index');
        }
        Model::delete($id);
        Message::GetMessage(1, __('Данные удалены!'));
        HTTP::redirect('wezom/' . Route::controller() . '/index');
    }

}
