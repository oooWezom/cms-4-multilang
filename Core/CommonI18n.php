<?php

namespace Core;

use Core\QB\DB;
use Core\Validation\Valid;

class CommonI18n extends Common {

    public static $tableI18n;
    public static $rulesI18n;

    public static function factory($table, $image = NULL) {
        return new CommonI18n($table, $image);
    }

    public function __construct($table = NULL, $image = NULL) {
        if ($table !== NULL) {
            static::$table = $table;
        }
        if ($image !== NULL) {
            static::$image = $image;
        }
        static::$tableI18n = static::$table . '_i18n';
    }

    /**
     * Get table with multi-language data name
     * @return string
     */
    public static function tableI18n() {
        static::$tableI18n = static::$table . '_i18n';
        return static::$tableI18n;
    }

    /**
     * @param array $data - associative array with insert data
     * @return integer - inserted row ID
     */
    public static function insert($data) {
        static::$tableI18n = static::$table . '_i18n';
        $simple = $data;
        $languages = \Core\Config::get('languages') ? \Core\Config::get('languages') : array();
        foreach ($languages AS $key => $lang) {
            unset($simple[$key]);
        }
        $id = parent::factory(static::$table)->insert($simple);
        if (!$id) {
            return false;
        }
        foreach ($languages AS $key => $lang) {
            $_data = Arr::get($data, $key, array());
            $_data['row_id'] = $id;
            $_data['language'] = $key;
            parent::factory(static::$tableI18n)->insert($_data);
        }
        return $id;
    }

    /**
     * @param array $data - associative array with data to update
     * @param string/integer $value - value for where clause
     * @param string $field - field for where clause
     * @return bool
     */
    public static function update($data, $value, $field = 'id') {
        static::$tableI18n = static::$table . '_i18n';
        if ($field != 'id') {
            $result = parent::getRow($value, $field);
            if (!$result) {
                return false;
            }
            $value = $result->id;
        }
        $languages = \Core\Config::get('languages') ? \Core\Config::get('languages') : array();
        foreach ($languages AS $key => $lang) {
            $_data = Arr::get($data, $key, array());
            $check = DB::select(array(DB::expr('COUNT(' . static::$tableI18n . '.id)'), 'count'))
                    ->from(static::$tableI18n)
                    ->where(static::$tableI18n . '.row_id', '=', $value)
                    ->where(static::$tableI18n . '.language', '=', $key)
                    ->count_all();
            if ($check) {
                static::updateI18n($_data, $value, $key);
            } else {
                $_data['row_id'] = $value;
                $_data['language'] = $key;
                parent::factory(static::$tableI18n)->insert($_data);
            }
            unset($data[$key]);
        }
        if ($data) {
            static::$table = str_replace('_i18n', '', static::$table);
            parent::update($data, $value);
        }
        return true;
    }

    /**
     * @param array $data - data to update
     * @param integer $row_id - ID of parent row (not ID of the current language row!)
     * @param string $language - language code (ru, en, ua...)
     * @return bool|object
     */
    public static function updateI18n($data, $row_id, $language) {
        static::$tableI18n = static::$table . '_i18n';
        if (!$row_id || !$language || !$data || !is_array($data)) {
            return false;
        }
        return DB::update(static::$tableI18n)
                        ->set($data)
                        ->where(static::$tableI18n . '.row_id', '=', $row_id)
                        ->where(static::$tableI18n . '.language', '=', $language)
                        ->execute();
    }

    /**
     * @param mixed $value - value
     * @param string $field - field
     * @param null/integer $status - 0 or 1
     * @return object
     */
    public static function getRowSimple($value, $field = 'id', $status = NULL) {
        $lang = \I18n::$lang;
        if (APPLICATION == 'backend') {
            $lang = \I18n::$default_lang_backend;
        }
        static::$tableI18n = static::$table . '_i18n';
        $row = DB::select(static::$tableI18n . '.*', static::$table . '.*')
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                ->where(static::$table . '.' . $field, '=', $value);
        if ($status <> NULL) {
            $row->where(static::$table . '.status', '=', $status);
        }
        return $row->where(static::$tableI18n . '.language', '=', $lang)->find();
    }

    /**
     * Get all the information about row + all languages translates
     * @param $value
     * @param string $field
     * @param null $status
     * @return array
     */
    public static function getRow($value, $field = 'id', $status = NULL) {
        static::$tableI18n = static::$table . '_i18n';
        $result = array('obj' => array(), 'langs' => array());
        $object = parent::getRow($value, $field, $status);
        if (!$object) {
            return $result;
        }
        $result['obj'] = $object;
        $res = DB::select(static::$tableI18n . '.*')
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                ->where(static::$table . '.' . $field, '=', (int) $value)
                ->find_all();
        foreach ($res AS $obj) {
            $result['langs'][$obj->language] = $obj;
        }
        return $result;
    }

    /**
     * @param null/integer $status - 0 or 1
     * @param null/string $sort
     * @param null/string $type - ASC or DESC. No $sort - no $type
     * @param null/integer $limit
     * @param null/integer $offset - no $limit - no $offset
     * @return object
     */
    public static function getRows($status = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL, $filter = true) {
        $lang = \I18n::$lang;
        if (APPLICATION == 'backend') {
            $lang = \I18n::$default_lang_backend;
        }
        static::$tableI18n = static::$table . '_i18n';
        if ($sort) {
            $arr = explode('.', $sort);
            if (count($arr) < 2) {
                $sort = static::$table . '.' . $sort;
            }
        }
        $result = DB::select(
                        static::$tableI18n . '.*', static::$table . '.*'
                )
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                ->where(static::$tableI18n . '.language', '=', $lang);
        if ($filter) {
            $result = static::setFilter($result);
        }
        if ($status <> NULL) {
            $result->where(static::$table . '.status', '=', $status);
        }
        if ($sort <> NULL) {
            if ($type <> NULL) {
                $result->order_by($sort, $type);
            } else {
                $result->order_by($sort);
            }
        }
        $result->order_by(static::$table . '.id', 'DESC');
        if ($limit <> NULL) {
            $result->limit($limit);
            if ($offset <> NULL) {
                $result->offset($offset);
            }
        }
        return $result->find_all();
    }

    /**
     * @param null/integer $status - 0 or 1
     * @return int
     */
    public static function countRows($status = NULL, $filter = true) {
        $lang = \I18n::$lang;
        if (APPLICATION == 'backend') {
            $lang = \I18n::$default_lang_backend;
        }
        static::$tableI18n = static::$table . '_i18n';
        $result = DB::select(array(DB::expr('COUNT(' . static::$table . '.id)'), 'count'))
                ->from(static::$table)
                ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                ->where(static::$tableI18n . '.language', '=', $lang);
        if ($status !== NULL) {
            $result->where(static::$table . '.status', '=', $status);
        }
        if ($filter) {
            $result = static::setFilter($result);
        }
        return $result->count_all();
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function valid($data = []) {
        if (!static::$rules && !static::$rulesI18n) {
            return TRUE;
        }
        if (static::$rules && static::$rulesI18n) {
            $valid = new Valid($data, static::$rules, static::$rulesI18n);
        } else if (static::$rules) {
            $valid = new Valid($data, static::$rules);
        } else {
            $valid = new Valid($data, [], static::$rulesI18n);
        }
        $errors = $valid->execute();
        if (!$errors) {
            return TRUE;
        }
        $message = Valid::message($errors);
        Message::GetMessage(0, $message, FALSE);
        return FALSE;
    }
    
     /**
     *  Adding +1 in field `views`
     * @param object $row - object
     * @return object
     */
    public static function addView($row)
    {
        $row->views = $row->views + 1;
        DB::update(static::$table)->set(['views' => $row->views])->where('id','=',$row->id)->execute();
        return $row;
    }

}
