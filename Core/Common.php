<?php
namespace Core;

use Core\QB\DB;
use Core\QB\Database;
use Core\Validation\Valid;

class Common
{

    public static $table;
    public static $image;
    public static $filters = []; // Filter that gets parameters from global array $_GET. This is array with trusted filter keys
    public static $rules = []; // Fields and their rules for validation

    public static function factory($table, $image = null)
    {
        return new Common($table, $image);
    }

    public function __construct($table = null, $image = null)
    {
        if ($table !== null) {
            static::$table = $table;
        }
        if ($image !== null) {
            static::$image = $image;
        }
    }


    public static function table()
    {
        return static::$table;
    }


    public static function image()
    {
        return static::$image;
    }


    /**
     * @param null $ip - current ip
     * @param null $email - email in POST
     * @param null $phone - phone in POST
     * @param null $date - 0 or 1 (60 seconds timeout)
     * @return int
     */
    public static function isBot($ip = null, $email = null, $phone = null, $date = null)
    {
        if ($ip === null && $email === null && $phone === null && $date === null) {
            return false;
        }
        $result = DB::select([DB::expr('COUNT(id)'), 'count'])->from(static::$table);
        $result->where_open();
        if ($ip !== null) {
            $result->or_where('ip', '=', $ip);
        }
        if ($email !== null) {
            $result->or_where('email', '=', $email);
        }
        if ($phone !== null) {
            $result->or_where('phone', '=', $phone);
        }
        $result->where_close();
        if ($date !== null) {
            $result->where('created_at', '>', time() - 60);
        }
        return $result->count_all();
    }


    /**
     * @param array $data - associative array with insert data
     * @return integer - inserted row ID
     */
    public static function insert($data)
    {
        if (!isset($data['created_at']) and Common::checkField(static::$table, 'created_at')) {
            $data['created_at'] = time();
        }
        $keys = $values = [];
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
        $result = DB::insert(static::$table, $keys)->values($values)->execute();
        if (!$result) {
            return false;
        }
        return $result[0];
    }


    /**
     * @param array $data - associative array with data to update
     * @param string /integer $value - value for where clause
     * @param string $field - field for where clause
     * @return bool
     */
    public static function update($data, $value, $field = 'id')
    {
        if (!isset($data['updated_at']) and Common::checkField(static::$table, 'updated_at')) {
            $data['updated_at'] = time();
        }
        return DB::update(static::$table)->set($data)->where($field, '=', $value)->execute();
    }


    /**
     * @param mixed $value - value
     * @param string $field - field
     * @return object
     */
    public static function delete($value, $field = 'id')
    {
        return DB::delete(static::$table)->where($field, '=', $value)->execute();
    }


    /**
     * @param string $table - table where we check the field
     * @param string $field - check this field
     * @return bool
     */
    public static function checkField($table, $field)
    {
        $cResult = DB::query(Database::SELECT, 'SHOW FIELDS FROM `' . $table . '`')->execute();
        $found = false;
        foreach ($cResult as $arr) {
            if ($arr['Field'] == $field) {
                $found = true;
            }
        }
        return $found;
    }

    /**
     * @param string $table - table where we check the field
     * @param string $field - check this field
     * @return bool
     */
    public static function checkTable($table)
    {
        $cResult = DB::query(Database::SELECT, 'SHOW TABLES LIKE \'' . $table . '\'')->execute();
        
        if(count($cResult)){
            return true;
        }

        return false;
    }


    /**
     * @param string $value - checked alias
     * @param int $id - ID if need off current row with ID = $id
     * @return string - unique alias
     */
    public static function getUniqueAlias($value, $id = null)
    {
        $value = Text::translit($value);
        $count = DB::select([DB::expr('COUNT(id)'), 'count'])
            ->from(static::$table)
            ->where('alias', '=', $value);
        if ($id !== null) {
            $count->where('id', '!=', $id);
        }
        $count = $count->count_all();
        if ($count) {
            return $value . rand(1000, 9999);
        }
        return $value;
    }


    /**
     * @param mixed $value - value
     * @param string $field - field
     * @param null /integer $status - 0 or 1
     * @return object
     */
    public static function getRow($value, $field = 'id', $status = null)
    {
        $result = DB::select()->from(static::$table)->where($field, '=', $value);
        if ($status !== null) {
            $result->where('status', '=', $status);
        }
        return $result->find();
    }


    /**
     * @param null /integer $status - 0 or 1
     * @param null /string $sort
     * @param null /string $type - ASC or DESC. No $sort - no $type
     * @param null /integer $limit
     * @param null /integer $offset - no $limit - no $offset
     * @return object
     */
    public static function getRows($status = null, $sort = null, $type = null, $limit = null, $offset = null, $filter = true)
    {
        $result = DB::select()->from(static::$table);
        if ($status !== null) {
            $result->where('status', '=', $status);
        }
        if ($filter) {
            $result = static::setFilter($result);
        }
        if ($sort !== null) {
            if ($type !== null) {
                $result->order_by($sort, $type);
            } else {
                $result->order_by($sort);
            }
        }
        $result->order_by('id', 'DESC');
        if ($limit !== null) {
            $result->limit($limit);
            if ($offset !== null) {
                $result->offset($offset);
            }
        }
        return $result->find_all();
    }


    public static function setFilter($result)
    {
        if (!is_array(static::$filters)) {
            return $result;
        }
        foreach (static::$filters as $key => $value) {
            if (isset($key) && isset($_GET[$key]) && trim($_GET[$key])) {
                $get = strip_tags($_GET[$key]);
                $get = trim($get);
                if (!Arr::get($value, 'action', null)) {
                    $action = '=';
                } else {
                    $action = Arr::get($value, 'action');
                }
                $table = false;
                if (Arr::get($value, 'table', null)) {
                    $table = Arr::get($value, 'table');
                } else if (Arr::get($value, 'table', null) === null) {
                    $table = static::$table;
                }
                if ($action == 'LIKE') {
                    $get = '%' . $get . '%';
                }
                if (Arr::get($value, 'field')) {
                    $key = Arr::get($value, 'field');
                }
                if ($table !== false) {
                    $result->where($table . '.' . $key, $action, $get);
                } else {
                    $result->where(DB::expr($key), $action, $get);
                }
            }
        }
        return $result;
    }


    /**
     * @param null /integer $status - 0 or 1
     * @return int
     */
    public static function countRows($status = null, $filter = true)
    {
        $result = DB::select([DB::expr('COUNT(' . static::$table . '.id)'), 'count'])->from(static::$table);
        if ($status !== null) {
            $result->where(static::$table . '.status', '=', $status);
        }
        if ($filter) {
            $result = static::setFilter($result);
        }
        return $result->count_all();
    }


    /**
     * Upload images for current type
     * @param integer $id - ID in the table for this image
     * @param string $name - name of the input in form for uploaded image
     * @param string $field - field name in the table to save new image name
     * @return bool|object
     */
    public static function uploadImage($id, $name = 'file', $field = 'image')
    {
        if (!static::$image OR !$id) {
            return false;
        }
        $filename = Files::uploadImage(static::$image, $name);
        if (!$filename) {
            return false;
        }
        if (!Common::checkField(static::$table, $field)) {
            return true;
        }
        return DB::update(static::$table)->set([$field => $filename])->where(static::$table . '.id', '=', $id)->execute();
    }


    /**
     * Delete images for current type
     * @param string $filename - file name
     * @param null|integer $id - ID in the table for this image
     * @param string $field - field name in the table to save new image name
     * @return bool
     */
    public static function deleteImage($filename, $id = null, $field = 'image')
    {
        if (!static::$image OR !$filename) {
            return false;
        }
        $result = Files::deleteImage(static::$image, $filename);
        if (!$result) {
            return false;
        }
        if (!Common::checkField(static::$table, $field)) {
            return true;
        }
        if ($id !== null) {
            return DB::update(static::$table)->set([$field => null])->where(static::$table . '.id', '=', $id)->execute();
        }
        return true;
    }


    /**
     *  Adding +1 in field `views`
     * @param object $row - object
     * @return object
     */
    public static function addView($row)
    {
        $row->views = $row->views + 1;
        static::update(['views' => $row->views], $row->id);
        return $row;
    }


    /**
     * @param array $data
     * @return bool
     */
    public static function valid($data = [])
    {
        if (!static::$rules) {
            return true;
        }
        $valid = new Valid($data, static::$rules);
        $errors = $valid->execute();
        if (!$errors) {
            return true;
        }
        $message = Valid::message($errors);
        Message::GetMessage(0, $message, false);
        return false;
    }
}