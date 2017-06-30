<?php
namespace Core;

class Arr
{

    /**
     *      Convert an array to an object
     * @param array $array - array with data
     */
    public static function to_object($array)
    {
        $json = json_encode($array);
        $object = json_decode($json);
        return $object;
    }

    /**
     * Retrieve a single key from an array. If the key does not exist in the
     * array, the default value will be returned instead.
     *
     *     // Get the value "username" from $_POST, if it exists
     *     $username = Arr::get($_POST, 'username');
     *
     *     // Get the value "username" from $_POST['form'], if it exists
     *     $username = Arr::get($_POST, 'form.username');
     *
     *     // Get the value "sorting" from $_GET, if it exists
     *     $sorting = Arr::get($_GET, 'sorting');
     *
     * @param   array $array array to extract from
     * @param   string $key key name
     * @param   mixed $default default value
     * @return  mixed
     */
    public static function get($array, $key, $default = null)
    {
        $key = explode('.', $key);
        foreach($key as $k) {
            if (is_array($array)) {
                if (isset($array[$k])) {
                    $default = $array[$k];
                    $array = $array[$k];
                } else {
                    break;
                }
            } elseif (is_object($array)) {
                if (isset($array->{$k})) {
                    $default = $array->{$k};
                    $array = $array->{$k};
                } else {
                    break;
                }
            }
        }
        return $default;
    }


    /**
     * Clear array from XSS
     * @param $array
     * @return mixed
     */
    public static function clearArray($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = static::clearArray($value);
            } else {
                $value = strip_tags($value);
                $value = HTML::chars($value);
                $array[$key] = $value;
            }
        }
        return $array;
    }

}