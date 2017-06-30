<?php
namespace Wezom\Modules\Ajax\Controllers;

use Core\Common;
use Core\Arr;
use Core\CommonI18n;
use Core\QB\DB;
use Core\Route;
use Wezom\Modules\Ajax;
use Wezom\Modules\Catalog\Models\Specifications as S;
use Wezom\Modules\Catalog\Models\SpecificationsValues as SV;

class Specifications extends Ajax {

    public function setPositionAction()
    {
        $id = Arr::get($this->post, 'id');
        $sort = Arr::get($this->post, 'sort');
        Common::factory('specifications')->update(['sort' => $sort], $id);
        $this->success();
    }


    /**
     * Add specification value
     * $this->post['name'] => specification value name
     * $this->post['alias'] => specification value alias
     * $this->post['specification_id'] => specification id
     */
    public function addSimpleSpecificationValueAction()
    {
        $post = [];
        foreach($this->_languages as $key => $lang){
            $field_name = 'name_'.$key;
            $post[$key]['name'] = Arr::get($this->post, $field_name);
            if(!$post[$key]['name']) {
                $this->error([
                    'error' => __('Вы не ввели название('.$lang[$lang['name']].') характеристики'),
                ]);
            }
        }

        $post['alias'] = Arr::get($this->post, 'alias');
        if(!$post['alias']) {
            $this->error([
                'error' => __('Вы не указали алиас характеристики'),
            ]);
        }
        $post['specification_id'] = Arr::get($this->post, 'specification_id');
        $post['status'] = 1;

        if(!$post['specification_id']) {
            $this->error([
                'error' => __('Вы ввели не все данные'),
            ]);
        }

        $post['alias'] = SV::getUniqueAlias($post['alias']);

        // Trying to save data
        $new_feature = SV::insert($post);
        if(!$new_feature) {
            $this->error([
                'error' => __('Ошибка на сервере. Повторите попытку позднее'),
            ]);
        }

        // Get full list of values for current specification
        $specification_values_raw = SV::getRowsBySpecificationsID($post['specification_id'], null, 'specifications_values_i18n.name', 'ASC', null, null, true);
        $specification_values = [];
        foreach($specification_values_raw as $specification_value){
            $specification_values[] = $specification_value;
        }
        // Answer
        $this->success([
            'result' => $specification_values,
        ]);
    }


    /**
     * Edit specification value
     * $this->post['id'] => specification value ID
     * $this->post['status'] => specification value status
     * $this->post['name'] => specification value name
     * $this->post['alias'] => specification value alias
     * $this->post['specification_id'] => specification id
     */
    public function editSimpleSpecificationValueAction()
    {
        $post = [];
        foreach($this->_languages as $key => $lang){
            $field_name = 'name_'.$key;
            $post[$key]['name'] = Arr::get($this->post, $field_name);
            if(!$post[$key]['name']) {
                $this->error([
                    'error' => __('Вы не ввели название('.$lang[$lang['name']].') характеристики'),
                ]);
            }
        }
        $post['alias'] = Arr::get($this->post, 'alias');
        if(!$post['alias']) {
            $this->error([
                'error' => __('Вы не указали алиас характеристики'),
            ]);
        }
        $post['status'] = Arr::get($this->post, 'status');
        $post['id'] = Arr::get($this->post, 'id');
        $post['specification_id'] = Arr::get($this->post, 'specification_id');
        if(!$post['id'] || !$post['specification_id']) {
            $this->error([
                'error' => __('Вы ввели не все данные'),
            ]);
        }
        $post['alias'] = SV::getUniqueAlias($post['alias'], $post['id']);

        // Trying to save data
        $update_feature = SV::update($post, $post['id']);

        if(!$update_feature) {
            $this->error([
                'error' => __('Ошибка на сервере. Повторите попытку позднее'),
            ]);
        }

        // Get full list of values for current specification
        $specification_values_raw = SV::getRowsBySpecificationsID($post['specification_id'], null, 'specifications_values_i18n.name', 'ASC', null, null, true);
        $specification_values = [];
        foreach($specification_values_raw as $specification_value){
            $specification_values[] = $specification_value;
        }
        // Answer
        $this->success([
            'result' => $specification_values,
        ]);
    }


    /**
     * Add specification value
     * $this->post['name'] => specification value name
     * $this->post['color'] => specification value color hex code
     * $this->post['alias'] => specification value alias
     * $this->post['specification_id'] => specification ID
     */
    public function addColorSpecificationValueAction()
    {
        $post = [];
        foreach($this->_languages as $key => $lang){
            $field_name = 'name_'.$key;
            $post[$key]['name'] = Arr::get($this->post, $field_name);
            if(!$post[$key]['name']) {
                $this->error([
                    'error' => __('Вы не ввели название('.$lang[$lang['name']].') характеристики'),
                ]);
            }
        }
        $post['color'] = Arr::get($this->post, 'color');
        $post['alias'] = Arr::get($this->post, 'alias');
        if(!$post['alias']) {
            $this->error([
                'error' => __('Вы не указали алиас характеристики'),
            ]);
        }
        $post['specification_id'] = Arr::get($this->post, 'specification_id');
        $post['status'] = 1;

        if(!$post['specification_id'] || !preg_match('/^#[0-9abcdef]{6}$/', $post['color'], $matches)) {
            $this->error([
                'error' => __('Вы ввели не все данные'),
            ]);
        }

        $post['alias'] = SV::getUniqueAlias($post['alias']);

        // Trying to save data
        $new_feature = SV::insert($post);
        if(!$new_feature) {
            $this->error([
                'error' => __('Ошибка на сервере. Повторите попытку позднее'),
            ]);
        }

        // Get full list of values for current specification
        $specification_values_raw = SV::getRowsBySpecificationsID($post['specification_id'], null, 'specifications_values_i18n.name', 'ASC', null, null, true);
        $specification_values = [];
        foreach($specification_values_raw as $specification_value){
            $specification_values[] = $specification_value;
        }
        // Answer
        $this->success([
            'result' => $specification_values,
        ]);
    }


    /**
     * Edit specification value
     * $this->post['name'] => specification value name
     * $this->post['color'] => specification value color hex code
     * $this->post['alias'] => specification value alias
     * $this->post['specification_id'] => specification ID
     * $this->post['id'] => specification value ID
     */
    public function editColorSpecificationValueAction()
    {
        $post = [];
        foreach($this->_languages as $key => $lang){
            $field_name = 'name_'.$key;
            $post[$key]['name'] = Arr::get($this->post, $field_name);
            if(!$post[$key]['name']) {
                $this->error([
                    'error' => __('Вы не ввели название('.$lang[$lang['name']].') характеристики'),
                ]);
            }
        }
        $post['alias'] = Arr::get($this->post, 'alias');
        if(!$post['alias']) {
            $this->error([
                'error' => __('Вы не указали алиас характеристики'),
            ]);
        }
        $post['status'] = Arr::get($this->post, 'status');
        $post['id'] = Arr::get($this->post, 'id');
        $post['specification_id'] = Arr::get($this->post, 'specification_id');
        if(!$post['id'] || !$post['specification_id'] || !preg_match('/^#[0-9abcdef]{6}$/', $post['color'], $matches)) {
            $this->error([
                'error' => __('Вы ввели не все данные'),
            ]);
        }
        $post['alias'] = SV::getUniqueAlias($post['alias'], $post['id']);

        // Trying to save data
        $update_feature = SV::update($post, $post['id']);

        if(!$update_feature) {
            $this->error([
                'error' => __('Ошибка на сервере. Повторите попытку позднее'),
            ]);
        }

        // Get full list of values for current specification
        $specification_values_raw = SV::getRowsBySpecificationsID($post['specification_id'], null, 'specifications_values_i18n.name', 'ASC', null, null, true);
        $specification_values = [];
        foreach($specification_values_raw as $specification_value){
            $specification_values[] = $specification_value;
        }
        // Answer
        $this->success([
            'result' => $specification_values,
        ]);
    }


    /**
     * Delete specification value
     * $this->post['id'] => specification value ID
     */
    public function deleteSpecificationValueAction()
    {
        // Check data
        $id = Arr::get($this->post, 'id');
        if(!$id) {
            $this->error([
                'error' => __('Вы ввели не все данные'),
            ]);
        }
        // Trying to delete value
        $result = CommonI18n::factory('specifications_values')->delete($id);
        // Error if failed saving
        if(!$result) {
            $this->error([
                'error' => __('Ошибка на сервере. Повторите попытку позднее'),
            ]);
        }
        // Answer
        $this->success();
    }
}