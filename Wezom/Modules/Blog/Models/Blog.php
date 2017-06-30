<?php

namespace Wezom\Modules\Blog\Models;

use Core\Arr;
use Core\HTML;
use Core\Message;
use Core\QB\DB;

class Blog extends \Core\CommonI18n {

    public static $table = 'blog';
    public static $tableI18n = 'blog_i18n';
    public static $image = 'blog';
    public static $filters = [
        'name' => [
            'table' => 'blog_i18n',
            'action' => 'LIKE',
        ],
        'rubric_id' => [
            'table' => 'blog',
            'action' => '=',
        ],
    ];
    public static $rulesI18n;
    public static $rules = [];

    public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL, $filter = true) {
        $result = DB::select(static::$tableI18n . '.*', static::$table . '.*', ['blog_rubrics_i18n.name', 'rubric'])
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                ->join('blog_rubrics', 'LEFT')->on('blog_rubrics.id', '=', static::$table . '.rubric_id')
                ->join('blog_rubrics_i18n', 'LEFT')->on('blog_rubrics_i18n.row_id', '=', 'blog_rubrics.id')
                ->where('blog_rubrics_i18n.language', '=', \I18n::$default_lang)
                ->where(static::$tableI18n . '.language', '=', \I18n::$default_lang);
        if ($status !== NULL) {
            $result->where(static::$table . '.status', '=', $status);
        }
        if ($date_s) {
            $result->where(static::$table . '.date', '>=', $date_s);
        }
        if ($date_po) {
            $result->where(static::$table . '.date', '<=', $date_po + 24 * 60 * 60 - 1);
        }
        if ($filter) {
            $result = static::setFilter($result);
        }
        if ($sort !== NULL) {
            if ($type !== NULL) {
                $result->order_by(static::$table . '.' . $sort, $type);
            } else {
                $result->order_by(static::$table . '.' . $sort);
            }
        }
        $result->order_by(static::$table . '.id', 'DESC');
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
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                ->where(static::$tableI18n . '.language', '=', \I18n::$default_lang);
        if ($status !== NULL) {
            $result->where(static::$table . '.status', '=', $status);
        }
        if ($date_s) {
            $result->where(static::$table . '.date', '>=', $date_s);
        }
        if ($date_po) {
            $result->where(static::$table . '.date', '<=', $date_po + 24 * 60 * 60 - 1);
        }
        $result = parent::setFilter($result);
        return $result->count_all();
    }

    public static function valid($post) {
        static::$rulesI18n = array(
            'name' => array(
                array(
                    'error' => __('Название не может быть пустым! (:lang)'),
                    'key' => 'not_empty',
                ),
            ),
        );
        static::$rules = [
            'alias' => [
                [
                    'error' => __('Алиас не может быть пустым!'),
                    'key' => 'not_empty',
                ],
                [
                    'error' => __('Алиас должен содержать только латинские буквы в нижнем регистре, цифры, "-" или "_"!'),
                    'key' => 'regex',
                    'value' => '/^[a-z0-9\-_]*$/',
                ],
            ],
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
        return parent::valid($post);
    }

}
