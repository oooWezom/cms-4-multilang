<?php
namespace Wezom\Modules\Catalog\Models;

use Core\Arr;
use Core\Message;
use Core\QB\DB;

class Specifications extends \Core\CommonI18n {

    public static $table = 'specifications';
    public static $tableI18n = 'specifications_i18n';
    public static $filters = [
        'name' => [
            'table' => 'specifications_i18n',
            'action' => 'LIKE',
        ],
    ];
    public static $rulesI18n = [];
    public static $rules = [];

    public static function valid($data = [])
    {
        static::$rulesI18n = [
            'name' => [
                [
                    'error' => __('Название не может быть пустым! (:lang)'),
                    'key' => 'not_empty',
                ],
            ],
        ];
        static::$rules = [
            'alias' => [
                [
                    'error' => __('Алиас не может быть пустым!'),
                    'key' => 'not_empty',
                ],
                [
                    'error' => __('Алиас должен содержать только латинские буквы в нижнем регистре или цифры!'),
                    'key' => 'regex',
                    'value' => '/^[a-z0-9]*$/',
                ],
            ],
        ];
        return parent::valid($data);
    }

    /**
     * Get specifications that communicate with current group
     * @param null|integer $parent_id - Group ID
     * @return object
     */
    public static function getGroupRows($parent_id = null) {
        $lang = \I18n::$lang;
        if(APPLICATION == 'backend') {
            $lang = \I18n::$default_lang_backend;
        }

        $result = DB::select(
            static::$tableI18n.'.*',
            static::$table.'.*'
        )
            ->from(static::$table)
            ->join('catalog_tree_specifications')->on('catalog_tree_specifications.specification_id', '=', static::$table.'.id')
            ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
            ->where(static::$tableI18n.'.language', '=', $lang);
        if($parent_id !== null) {
            $result->where('catalog_tree_specifications.catalog_tree_id', '=', $parent_id);
        }
        return $result->order_by(static::$table.'.sort', 'ASC')
            ->group_by(static::$table.'.id')
            ->find_all();
    }
}