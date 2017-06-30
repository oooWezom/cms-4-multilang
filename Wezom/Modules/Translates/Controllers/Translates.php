<?php

namespace Wezom\Modules\Translates\Controllers;

use Core\Arr;
use Core\Config;
use Core\QB\DB;
use Core\Route;
use Core\Image;
use Core\View;

class Translates extends \Wezom\Modules\Base
{

    public $tpl_folder = 'Translates';
    protected $_page = 1;
    protected $_limit;
    protected $_offset;


    /**
     *
     */
    function before()
    {
        parent::before();
        $this->_page = (!(int) Arr::get($_POST, 'start') || !(int) Arr::get($_POST, 'length')) ? 1 : ((int) Arr::get($_POST, 'start') / (int) Arr::get($_POST, 'length')) + 1;
        $limit = Config::get('basic.limit_backend');
        $this->_limit = (int) Arr::get($_POST, 'length') ? (int) Arr::get($_POST, 'length') : $limit;
        $this->_offset = ($this->_page - 1) * $this->_limit;
    }

    function adminAction()
    {
        $this->_seo['h1'] = __('Переводы админ панели');
        $this->_seo['title'] = __('Переводы админ панели');
        $this->setBreadcrumbs(__('Переводы админ панели'), 'backend/' . Route::controller() . '/btranslates');

        return $this->translates();
    }

    function siteAction()
    {
        $this->_seo['h1'] = __('Переводы сайта');
        $this->_seo['title'] = __('Переводы сайта');
        $this->setBreadcrumbs(__('Переводы сайта'), 'backend/' . Route::controller() . '/translates');

        return $this->translates(true);
    }

    protected function translates($frontend = false)
    {
        if ($frontend) {
            $languages = \Wezom\Modules\Ajax\Helpers\Translates::getFrontendLanguages();
        } else {
            $languages = Config::get('i18n.languages');
        }
        $result = [];
        $key = '';
        foreach ($languages AS $key => $lang) {
            if ($frontend) {
                $filename = HOST . '/Plugins/I18n/Translates/' . $lang['alias'] . '/general.php';
            } else {
                $filename = HOST . '/Plugins/I18n/TranslatesBackend/' . $lang['alias'] . '/general.php';
            }
            if (is_file($filename)) {
                if (count($result) == 0) {
                    $keys = array_keys(include $filename);
                    foreach ($keys as $k => $v) {
                        $result['key'][$v] = $v;
                    }
                }
                $result[$lang['alias']] = include $filename;
            }
        }
        if (!$result) {
            return Config::error();
        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            // count total translates
            $response['recordsTotal'] = count($result['key']);

            // filter translates
            if ($search = Arr::get($_POST, 'search.value')) {
                $filteredKeys = [];
                foreach ($result as $key => $translates) {
                    foreach ($translates as $k => $translate) {
                        if (stripos($translate, $search) !== false) {
                            $filteredKeys[$k] = $k;
                        }
                    }
                }
                foreach ($result as $key => $translates) {
                    foreach ($translates as $k => $translate) {
                        if (!in_array($k, $filteredKeys)) {
                            unset($result[$key][$k]);
                        }
                    }
                }
            }

            // count filtered translates
            $response['recordsFiltered'] = count($result['key']);

            // sorting
            $sortingKeys = array_keys($result);
            $sortKey = isset($sortingKeys[Arr::get($_POST, 'order.0.column')]) ? $sortingKeys[Arr::get($_POST, 'order.0.column')] : 'key';
            $sortType = in_array(strtolower(Arr::get($_POST, 'order.0.dir')), ['asc', 'desc']) ? strtolower(Arr::get($_POST, 'order.0.dir')) : 'asc';

            if ($sortType == 'asc') { asort($result[$sortKey]); } else { arsort($result[$sortKey]); }
            $sortedKeys = array_keys($result[$sortKey]);
            foreach ($sortingKeys as $key) {
                $sortedTranslates = [];
                foreach ($sortedKeys as $k) {
                    $sortedTranslates[$k] = $result[$key][$k];
                }
                $result[$key] = $sortedTranslates;
            }

            // pagination
            foreach ($result as $key => $translates) {
                $result[$key] = array_splice(array_values($translates), $this->_offset, $this->_limit);
            }

            // prepare for response
            $keys = array_keys($result);
            $response['data'] = [];
            for ($i = 0; $i < count($result['key']); $i++) {
                foreach($keys as $key) {
                    $response['data'][$i][] = $result[$key][$i];
                }
            }

            die(json_encode($response));
        }

        $this->_content = View::tpl([
            'pageName' => $this->_seo['h1'],
            'count' => count($result[$key]),
            'languages' => $languages,
            'limit' => $this->_limit,
            'offset' => $this->_offset,
            'frontend' => $frontend
        ], $this->tpl_folder . '/Index');
    }

}