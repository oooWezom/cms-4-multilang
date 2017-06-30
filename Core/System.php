<?php

namespace Core;

class System
{

    /**
     *  Get current IP and set it into the static $_client_ip
     * @return string - current IP
     */
    public static function getRealIP()
    {
        $_server = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];
        foreach ($_server as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }


    /**
     *  Check for messge in SESSION, get it and clear session
     * @return string - message to show
     */
    public static function global_massage()
    {
        $_global_message = '';
        if (isset($_SESSION['GLOBAL_MESSAGE'])) {
            $_global_message = $_SESSION['GLOBAL_MESSAGE'];
            unset($_SESSION['GLOBAL_MESSAGE']);
        }
        return $_global_message;
    }

}