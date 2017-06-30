<?php
namespace Core;

use Core\QB\Database;
use Core\QB\DB;

class Support
{

    public static function selectData($elements, $valueField, $nameField, $emptyField = null)
    {
        $data = [];
        if ($emptyField !== null) {
            if (is_array($emptyField)) {
                $data[$emptyField[0]] = $emptyField[1];
            } else {
                $data[$emptyField] = '';
            }
        }
        foreach ($elements AS $obj) {
            if (is_array($obj)) {
                $data[$obj[$valueField]] = $obj[$nameField];
            } else {
                $data[$obj->{$valueField}] = $obj->{$nameField};
            }
        }
        return $data;
    }


    public static function addItemTag($obj)
    {
        if ($obj->sale) {
            return '<div class="splash2"><span>'.__('акция').'</span></div>';
        }
        if ($obj->top) {
            return '<div class="splash3"><span>'.__('популярное').'</span></div>';
        }
        if ($obj->new and time() - (int)$obj->new_from < Config::get('basic.new_days') * 24 * 60 * 60) {
            return '<div class="splash"><span>'.__('новинка').'</span></div>';
        }
    }

    public static function getRootParent($result, $id)
    {
        if (!$id) {
            return 0;
        }
        foreach ($result AS $obj) {
            if ($obj->id == $id) {
                if ($obj->parent_id == 0) {
                    return $obj->id;
                } else {
                    return Support::getRootParent($result, $obj->parent_id);
                }
            }
        }
    }

    public static function addZero($number)
    {
        $string = (string)$number;
        if (strlen($string) == 2) {
            return $string;
        }
        return '0' . $string;
    }

    public static function getHumanDateRange($from, $to)
    {
        if ($from == $to) {
            return date('d', $from) . '&nbsp;' . Dates::month(date('m', $from));
        }
        if (date('m', $from) == date('m', $to)) {
            return date('d', $from) . ' — ' . date('d', $to) . '&nbsp;' . Dates::month(date('m', $from));
        }
        return date('d', $from) . '&nbsp;' . Dates::month(date('m', $from)) . ' — ' . date('d', $to) . '&nbsp;' . Dates::month(date('m', $to));
    }

    public static function firstWordInSpan($string)
    {
        $words = explode(' ', $string);
        $words[0] = '<span>' . $words[0] . '</span>';
        $string = implode(' ', $words);
        return $string;
    }

    public static function firstWordWithBr($string)
    {
        $words = explode(' ', $string);
        $words[0] = $words[0] . '<br />';
        $string = implode(' ', $words);
        return $string;
    }

    public static function firstWordInSpanWithBr($string)
    {
        $words = explode(' ', $string);
        $words[0] = '<span>' . $words[0] . '</span><br />';
        $string = implode(' ', $words);
        return $string;
    }

    // Options/optgroups tree for tag select
    public static function getSelectOptions($filename, $table, $parentID = NULL, $currentElement = 0, $sort = 'sort', $parentAlias = 'parent_id')
    {
        if ($filename != 'Catalog/Items/Select') {
            $current = Route::param('id');
        } else {
            $current = 0;
        }
        $tree = [];
        $tableI18n = $table.'_i18n';
        $checkMiltilangTable = Common::checkTable($tableI18n);
        $result = null;
        if(!$checkMiltilangTable) {
            $result = DB::select()->from($table)->order_by($sort)->as_object()->execute();
        } else {
            $result = DB::select(
                $table.'.*',
                $tableI18n.'.name'
            )
                ->from($table)
                ->join($tableI18n)
                    ->on($tableI18n.'.row_id', '=', $table.'.id')
                ->where($tableI18n.'.language', '=', \I18n::$lang)
                ->order_by($sort)->as_object()->execute();
        }
        foreach ($result AS $obj) {
            if (!$current) {
                $tree[$obj->$parentAlias][] = $obj;
            } else if ($obj->parent_id != $current AND $obj->id != $current) {
                $tree[$obj->$parentAlias][] = $obj;
            }
        }
        return View::tpl([
            'result' => $tree,
            'currentParent' => 0,
            'space' => '',
            'filename' => $filename,
            'parentID' => $parentID,
            'parentAlias' => $parentAlias,
            'currentID' => $currentElement,
        ], $filename);
    }

    // Get human readable date range for admin filter widget
    public static function getWidgetDatesRange()
    {
        $dates = [];
        if (Arr::get($_GET, 'date_s')) {
            $dates[] = Support::getWidgetDate(Arr::get($_GET, 'date_s'));
        }
        if (Arr::get($_GET, 'date_po') and Arr::get($_GET, 'date_s') != Arr::get($_GET, 'date_po')) {
            $dates[] = Support::getWidgetDate(Arr::get($_GET, 'date_po'));
        }
        return implode(' - ', $dates);
    }

    // Get human readable date range
    public static function getWidgetDate($date)
    {
        $time = strtotime($date);
        return date('j', $time) . ' ' . Dates::month(date('m', $time)) . ' ' . date('Y', $time);
    }

    // Generate link for filters in wezom
    public static function generateLink($key, $value = null, $fakeLink = null)
    {
        $link = $fakeLink ? $fakeLink : Arr::get($_SERVER, 'REQUEST_URI');
        $uri = explode('?', $link);

        $__get = [];
        if (count($uri) > 1) {
            $arr = explode('&', $uri[1]);
            foreach ($arr AS $_a) {
                $g = rawurldecode($_a);
                $g = strip_tags($g);
                $g = stripslashes($g);
                $g = trim($g);
                $___get = explode('=', $g);
                $__get[$___get[0]] = $___get[1];
            }
        }

        if ($value === null) {
            if (!isset($__get[$key])) {
                return $link;
            }
            $arr = explode('&', $uri[1]);
            $get = [];
            foreach ($arr AS $el) {
                $h = explode('=', $el);
                if ($key != $h[0]) {
                    $get[] = $h[0] . '=' . $h[1];
                }
            }
            $uri[1] = implode('&', $get);
            if ($uri[1]) {
                return $uri[0] . '?' . $uri[1];
            }
            return $uri[0];
        }

        if (!isset($__get[$key])) {
            if (isset($uri[1])) {
                return Arr::get($uri, 0) . '?' . Arr::get($uri, 1) . '&' . $key . '=' . $value;
            }
            return Arr::get($uri, 0) . '?' . $key . '=' . $value;
        }
        if (Arr::get($__get, $key) == $value) {
            return $link;
        }
        $arr = explode('&', $uri[1]);
        $get = [];
        foreach ($arr AS $el) {
            $h = explode('=', $el);
            if ($key == $h[0]) {
                $get[] = $key . '=' . $value;
            } else {
                $get[] = $h[0] . '=' . $h[1];
            }
        }

        $uri[1] = implode('&', $get);
        return $uri[0] . '?' . $uri[1];
    }

    public static function getFiles($path = null, $ext = [])
    {
        $dir = HOST;
        if ($path) {
            $dir .= '/' . $path;
        }
        $files = scandir($dir);
        if (sizeof($ext)) {
            $arr = [];
            foreach ($files as $key => $val) {
                if (is_file(HOST . '/' . $val) and in_array(end(explode('.', $val)), $ext)) {
                    $arr[] = $val;
                }
            }
            return $arr;
        } else {
            return $files;
        }
    }

}