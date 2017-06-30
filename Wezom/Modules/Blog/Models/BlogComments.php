<?php

namespace Wezom\Modules\Blog\Models;

use Core\QB\DB;

class BlogComments extends \Core\Common {

    public static $table = 'blog_comments';
    public static $filters = [
        'item_name' => [
            'table' => 'blog_i18n',
            'action' => 'LIKE',
            'field' => 'name',
        ],
        'uid' => [
            'action' => '=',
            'field' => 'user_id',
        ],
    ];
    public static $rules = [];

    public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL) {
        $result = DB::select('blog_comments.*', ['blog_i18n.name', 'blog_name'], ['blog.alias', 'blog_alias'], ['users.name', 'user_name'])
                ->from(static::$table)
                ->join('blog', 'LEFT')->on('blog.id', '=', 'blog_comments.blog_id')
                ->join('blog_i18n', 'LEFT')->on('blog_i18n.row_id', '=', 'blog.id')
                ->join('users', 'LEFT')->on('users.id', '=', 'blog_comments.user_id')
                ->where('blog_i18n.language', '=', \I18n::$default_lang);
        $result = parent::setFilter($result);
        if ($status !== NULL) {
            $result->where(static::$table . '.status', '=', $status);
        }
        if ($date_s) {
            $result->where(static::$table . '.date', '>=', $date_s);
        }
        if ($date_po) {
            $result->where(static::$table . '.date', '<=', $date_po + 24 * 60 * 60 - 1);
        }
        if ($sort !== NULL) {
            if ($type !== NULL) {
                $result->order_by($sort, $type);
            } else {
                $result->order_by($sort);
            }
        }
        if ($limit !== NULL) {
            $result->limit($limit);
        }
        if ($offset !== NULL) {
            $result->offset($offset);
        }
        return $result->find_all();
    }

    public static function countRows($status = NULL, $date_s = NULL, $date_po = NULL) {
        $result = DB::select([DB::expr('COUNT(' . static::$table . '.id)'), 'count'])
                ->from(static::$table)
                ->join('blog', 'LEFT')->on('blog.id', '=', 'blog_comments.blog_id')
                ->join('blog_i18n', 'LEFT')->on('blog_i18n.row_id', '=', 'blog.id')
                ->where('blog_i18n.language', '=', \I18n::$default_lang);
        if ($status !== NULL) {
            $result->where(static::$table . '.status', '=', $status);
        }
        if ($date_s) {
            $result->where(static::$table . '.date', '>=', $date_s);
        }
        if ($date_po) {
            $result->where(static::$table . '.date', '<=', $date_po + 24 * 60 * 60 - 1);
        }
        return $result->count_all();
    }

    public static function valid($data = []) {
        static::$rules = [
            'name' => [
                [
                    'error' => __('Имя не может быть пустым!'),
                    'key' => 'not_empty',
                ],
            ],
            'text' => [
                [
                    'error' => __('Сообщение не может быть пустым!'),
                    'key' => 'not_empty',
                ],
            ],
            'blog_id' => array(
                array(
                    'error' => __('Выберите запись в блоге!'),
                    'key' => 'digit',
                ),
            ),
            'date' => [
                [
                    'error' => __('Дата не может быть пустой!'),
                    'key' => 'not_empty',
                ],
                [
                    'error' => __('Укажите правильную дату!'),
                    'key' => 'date',
                ],
            ],
        ];
        return parent::valid($data);
    }

}
