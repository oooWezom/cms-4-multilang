<?php
namespace Core;

class Config
{

    /**
     *  Set parameter
     * @param string $parameter - Name of the parameter
     * @param mixed $value - Value of the parameter
     */
    static function set($parameter, $value = null)
    {
        Config::instance()->setParameter($parameter, $value);
    }


    /**
     *  Get parameter
     * @param  string $parameter - Name of parameter we need
     * @return mixed             - Value of this parameter or NULL if not isset
     */
    static function get($parameter)
    {
        return Config::instance()->getParameter($parameter);
    }


    /**
     *  Get all data in Config instance
     */
    static function parse()
    {
        return Config::instance();
    }


    /**
     *  Set error 404
     */
    static function error()
    {
        return Config::instance()->setError();
    }


    /**
     *  Core of the class
     */
    protected $parameters = []; // array with parameters
    protected $error = false; // do we have 404 error on the site? TRUE or FALSE

    static $_instance;

    static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     *  Set parameter
     * @param string $parameter - Name of the parameter
     * @param mixed $value - Value of the parameter
     */
    public function setParameter($parameter, $value = null)
    {
        $this->parameters[$parameter] = $value;
    }


    /**
     *  Get parameter
     * @param  string $parameter - Name of parameter we need
     * @return mixed             - Value of this parameter or NULL if not isset
     */
    public function getParameter($parameter)
    {
        // Explode path by "."
        $tmp = explode('.', $parameter);
        // If parameter exists in instance of the Config class
        if (isset($this->parameters[$tmp[0]])) {
            $parameter = $this->parameters[$tmp[0]];
            unset($tmp[0]);
            return $this->getRecursive($parameter, $tmp);
            // If parameter does not exist in instance of the Config class
        } else if (is_file(HOST . DS . 'Config' . DS . $tmp[0] . '.php')) {
            $parameter = require_once HOST . DS . 'Config' . DS . $tmp[0] . '.php';
            $this->setParameter($tmp[0], $parameter);
            unset($tmp[0]);
            return $this->getRecursive($parameter, $tmp);
        }
        // If wrong parameter name
        return null;
    }


    /**
     *  Recursive get parameter from array
     * @param  array $values - array of parameters in Config class
     * @param  array $keys - array with keys path we need
     * @return mixed         - parameter we request
     */
    public function getRecursive($values, $keys)
    {
        if (empty($keys)) {
            return $values;
        }
        if (!is_array($values)) {
            return null;
        }
        foreach ($keys as $position => $key) {
            if (!isset($values[$key])) {
                return null;
            } else {
                $values = $values[$key];
                unset($keys[$position]);
                return $this->getRecursive($values, $keys);
            }
        }
    }


    /**
     *  Set error 404 on the site
     */
    public function setError()
    {
        header('HTTP/1.0 404 Not Found');
        header("Status: 404 Not Found");
        $this->set('error', true);
        return true;
    }

}