<?php
namespace Wezom\Modules\Catalog\Models;

use Core\QB\DB;

class SpecificationsValues extends \Core\CommonI18n {

    public static $table = 'specifications_values';
    public static $tableI18n = 'specifications_values_i18n';
    public static $filters = [
        'name' => [
            'table' => 'specifications_values_i18n',
            'action' => 'LIKE',
        ],
    ];
    public static $rulesI18n;
    public static $rules;

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
            'name' => [
                [
                    'error' => __('Название значения характеристики не может быть пустым!'),
                    'key' => 'not_empty',
                ],
            ],
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
     * @param $specifications
     * @return object
     */
    public static function getRowsBySpecifications($specifications) {
        $specifications_ids = [0];
        foreach($specifications as $s) {
            $specifications_ids[] = $s->id;
        }

        return self::getRowsBySpecificationsID($specifications_ids, null, 'sort');
    }


    /**
     * @param $specification_id
     * @param $alias
     * @return mixed
     */
    public static function checkValue($specification_id, $alias) {
        $lang = \I18n::$lang;
        if(APPLICATION == 'backend') {
            $lang = \I18n::$default_lang_backend;
        }

        $result = DB::select(
            static::$tableI18n.'.*',
            static::$table.'.*'
        )
            ->from(static::$table)
            ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
            ->where(static::$tableI18n.'.language', '=', $lang)
            ->where(static::$table.'.alias', '=', $alias)
            ->where(static::$table.'.specification_id', '=', $specification_id)
            ->where(static::$table.'.status', '=', 1);
        return $result->find();
    }


    /**
     * @param null $specifications_ids
     * @param null $status
     * @param null $sort
     * @param null $type
     * @param null $limit
     * @param null $offset
     * @param bool $all_rows
     * @return object|array
     */
    public static function getRowsBySpecificationsID($specifications_ids, $status = null, $sort = null, $type = null, $limit = null, $offset = null, $all_rows = false) {
        $lang = \I18n::$lang;
        if(APPLICATION == 'backend') {
            $lang = \I18n::$default_lang_backend;
        }

        if(!$specifications_ids) {
            $specifications_ids = [0];
        }
        if(!is_array($specifications_ids)) {
            $specifications_ids = [(int)$specifications_ids];
        }

        if($sort) {
            $arr = explode('.', $sort);
            if(count($arr) < 2) {
                $sort = static::$table.'.'.$sort;
            }
        }

        $result = DB::select(
            static::$tableI18n.'.*',
            static::$table.'.*'
        )
            ->from(static::$table)
            ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
            ->where(static::$table.'.specification_id', 'IN', $specifications_ids);
        if($all_rows){
            // add nothing
        } else {
            $result->where(static::$tableI18n.'.language', '=', $lang);
        }
        if($status !== null) {
            $result->where(static::$table.'.status', '=', $status);
        }
        if($sort !== null) {
            if($type !== null) {
                $result->order_by($sort, $type);
            } else {
                $result->order_by($sort);
            }
        }
        if($limit !== null) {
            $result->limit($limit);
            if($type !== null) {
                $result->offset($offset);
            }
        }

        if($all_rows){
            $list = [];
            $list_raw = $result->find_all()->as_array();
            foreach($list_raw as $item){
                $field_name = 'name_'.$item->language;
                if($list[$item->id]){
                    // do nothing
                } else {
                    $list[$item->id] = $item;
                }
                $list[$item->id]->$field_name = $item->name;
            }

            return $list;
        }

        return $result->find_all();
    }
}