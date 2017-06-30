<?php
namespace Wezom\Modules\Catalog\Models;

use Core\Arr;
use Core\Common;
use Core\Message;
use Core\QB\DB;

class Models extends \Core\CommonI18n {

    public static $table = 'models';
    public static $tableI18n = 'models_i18n';
    public static $filters = [
        'name' => [
            'table' => 'models_i18n',
            'action' => 'LIKE',
        ],
        'brand_alias' => [
            'table' => null,
            'action' => '=',
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
                    'error' => __('Алиас должен содержать только латинские буквы в нижнем регистре, цифры!'),
                    'key' => 'regex',
                    'value' => '/^[a-z0-9]*$/',
                ],
            ],
            'brand_alias' => [
                [
                    'error' => __('Выберите производителя!'),
                    'key' => 'not_empty',
                ],
            ],
        ];
        $brand = Brands::getRow(Arr::get($data, 'brand_alias'), 'alias');
        if(!$brand) {
            Message::GetMessage(0, __('Выберите бренд из списка!'));
            return false;
        }
        return parent::valid($data);
    }

    /**
     * Get models for current brand
     * @param string $brand_alias
     * @return object
     */
    public static function getBrandRows($brand_alias) {
        $lang = \I18n::$lang;
        if(APPLICATION == 'backend') {
            $lang = \I18n::$default_lang_backend;
        }

        return DB::select(
                static::$tableI18n.'.*',
                static::$table.'.*'
            )
            ->from(static::$table)
            ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n.'.row_id', '=', static::$table.'.id')
            ->where(static::$table.'.brand_alias', '=', $brand_alias)
            ->where(static::$tableI18n.'.language', '=', $lang)
            ->order_by(static::$tableI18n.'.name', 'ASC')
            ->find_all();
    }

}