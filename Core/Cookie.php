<?php
namespace Core;
/**
 * Cookie helper.
 */
class Cookie
{

    /**
     * @var  string  Magic salt to add to the cookie
     */
    public static $salt = 'SdwK32jlkjlk23-0-234=';

    /**
     * @var  integer  Number of seconds before the cookie expires
     */
    public static $expiration = 0;

    /**
     * @var  string  Restrict the path that the cookie is available to
     */
    public static $path = '/';

    /**
     * @var  string  Restrict the domain that the cookie is available to
     */
    public static $domain = null;

    /**
     * @var  boolean  Only transmit cookies over secure connections
     */
    public static $secure = false;

    /**
     * @var  boolean  Only transmit cookies over HTTP, disabling Javascript access
     */
    public static $httponly = false;

    /**
     * Gets the value of a signed cookie. Cookies without signatures will not
     * be returned. If the cookie signature is present, but invalid, the cookie
     * will be deleted.
     *
     *     // Get the "theme" cookie, or use "blue" if the cookie does not exist
     *     $theme = Cookie::get('theme', 'blue');
     *
     * @param   string $key cookie name
     * @param   mixed $default default value to return
     * @return  string
     */
    public static function get($key, $default = null)
    {
        if (!isset($_COOKIE[$key])) {
            // The cookie does not exist
            return $default;
        }

        // Get the cookie value
        $cookie = $_COOKIE[$key];

        // Find the position of the split between salt and contents
        $split = strlen(Cookie::salt($key, null));

        if (isset($cookie[$split]) and $cookie[$split] === '~') {
            // Separate the salt and the value
            list ($hash, $value) = explode('~', $cookie, 2);

            if (static::_slow_equals(Cookie::salt($key, $value), $hash)) {
                // Cookie signature is valid
                return $value;
            }

            // The cookie signature is invalid, delete it
            static::delete($key);
        }

        return $default;
    }


    public static function getArray($key, $default = null)
    {
        $cookie = static::get($key, $default);
        if (is_array($cookie)) {
            return $cookie;
        }
        if (!$cookie) {
            return null;
        }
        $cookie = base64_decode($cookie);
        $cookie = json_decode($cookie, true);
        return $cookie;
    }


    /**
     * Sets a signed cookie. Note that all cookie values must be strings and no
     * automatic serialization will be performed!
     *
     * [!!] By default, Cookie::$expiration is 0 - if you skip/pass NULL for the optional
     *      lifetime argument your cookies will expire immediately unless you have separately
     *      configured Cookie::$expiration.
     *
     *
     *     // Set the "theme" cookie
     *     Cookie::set('theme', 'red');
     *
     * @param   string $name name of cookie
     * @param   string $value value of cookie
     * @param   integer $lifetime lifetime in seconds
     * @return  boolean
     * @uses    Cookie::salt
     */
    public static function set($name, $value, $lifetime = null)
    {
        if ($lifetime === null) {
            // Use the default expiration
            $lifetime = Cookie::$expiration;
        }

        if ($lifetime !== 0) {
            // The expiration is expected to be a UNIX timestamp
            $lifetime += static::_time();
        }

        // Add the salt to the cookie value
        $value = Cookie::salt($name, $value) . '~' . $value;

        return static::_setcookie($name, $value, $lifetime, Cookie::$path, Cookie::$domain, Cookie::$secure, Cookie::$httponly);
    }


    public static function setArray($name, $array, $lifetime = null)
    {
        $value = json_encode($array);
        $value = base64_encode($value);
        return static::set($name, $value, $lifetime);
    }


    /**
     * Deletes a cookie by making the value NULL and expiring it.
     *
     *     Cookie::delete('theme');
     *
     * @param   string $name cookie name
     * @return  boolean
     */
    public static function delete($name)
    {
        // Remove the cookie
        unset($_COOKIE[$name]);

        // Nullify the cookie and make it expire
        return static::_setcookie($name, null, -86400, Cookie::$path, Cookie::$domain, Cookie::$secure, Cookie::$httponly);
    }

    /**
     * Generates a salt string for a cookie based on the name and value.
     *
     *     $salt = Cookie::salt('theme', 'red');
     *
     * @param   string $name name of cookie
     * @param   string $value value of cookie
     *
     * @throws Kohana_Exception if Cookie::$salt is not configured
     * @return  string
     */
    public static function salt($name, $value)
    {
        // Require a valid salt
        if (!Cookie::$salt) {
            die('A valid cookie salt is required. Please set Cookie::$salt. For more information check the documentation');
        }

        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

        return hash_hmac('sha1', $agent . $name . $value . Cookie::$salt, Cookie::$salt);
    }

    /**
     * Proxy for the native setcookie function - to allow mocking in unit tests so that they do not fail when headers
     * have been sent.
     *
     * @param string $name
     * @param string $value
     * @param integer $expire
     * @param string $path
     * @param string $domain
     * @param boolean $secure
     * @param boolean $httponly
     *
     * @return bool
     * @see setcookie
     */
    protected static function _setcookie($name, $value, $expire, $path, $domain, $secure, $httponly)
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * Proxy for the native time function - to allow mocking of time-related logic in unit tests
     *
     * @return int
     * @see    time
     */
    protected static function _time()
    {
        return time();
    }

    /**
     * Compare two hashes in a time-invariant manner.
     * Prevents cryptographic side-channel attacks (timing attacks, specifically)
     *
     * @param string $a cryptographic hash
     * @param string $b cryptographic hash
     * @return boolean
     */
    protected static function _slow_equals($a, $b)
    {
        $diff = strlen($a) ^ strlen($b);
        for ($i = 0; $i < strlen($a) and $i < strlen($b); $i++) {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $diff === 0;
    }

}
