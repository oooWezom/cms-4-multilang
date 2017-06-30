<?php
namespace Core;

/**
 * The Encrypt library provides two-way encryption of text and binary strings
 * using the [Mcrypt](http://php.net/mcrypt) extension, which consists of three
 * parts: the key, the cipher, and the mode.
 *
 * The Key
 * :  A secret passphrase that is used for encoding and decoding
 *
 * The Cipher
 * :  A [cipher](http://php.net/mcrypt.ciphers) determines how the encryption
 *    is mathematically calculated. By default, the "rijndael-128" cipher
 *    is used. This is commonly known as "AES-128" and is an industry standard.
 *
 * The Mode
 * :  The [mode](http://php.net/mcrypt.constants) determines how the encrypted
 *    data is written in binary form. By default, the "nofb" mode is used,
 *    which produces short output with high entropy.
 *
 * @package    Kohana
 * @category   Security
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Encrypt
{

    /**
     * @var  string  default instance name
     */
    public static $default = 'default';

    /**
     * @var  array  Encrypt class instances
     */
    public static $instances = [];

    /**
     * @var  string  OS-dependent RAND type to use
     */
    protected static $_rand;

    /**
     * Returns a singleton instance of Encrypt. An encryption key must be
     * provided in your "encrypt" configuration file.
     *
     *     $encrypt = Encrypt::instance();
     *
     * @param   string $name configuration group name
     * @return  Encrypt
     */
    public static function instance($name = null)
    {
        if ($name === null) {
            // Use the default instance name
            $name = Encrypt::$default;
        }

        if (!isset(Encrypt::$instances[$name])) {
            // Load the configuration data
            $config = Config::get('encrypt');

            if (!isset($config['key'])) {
                // No default encryption key is provided!
                die('No encryption key is defined in the encryption configuration group: ' . $name);
            }

            if (!isset($config['mode'])) {
                // Add the default mode
                $config['mode'] = MCRYPT_MODE_NOFB;
            }

            if (!isset($config['cipher'])) {
                // Add the default cipher
                $config['cipher'] = MCRYPT_RIJNDAEL_128;
            }

            // Create a new instance
            Encrypt::$instances[$name] = new Encrypt($config['key'], $config['mode'], $config['cipher']);
        }

        return Encrypt::$instances[$name];
    }


    /**
     * Encrypts a string and returns an encrypted string that can be decoded.
     *
     *     $data = $encrypt->encode($data);
     *
     * The encrypted binary data is encoded using [base64](http://php.net/base64_encode)
     * to convert it to a string. This string can be stored in a database,
     * displayed, and passed using most other means without corruption.
     *
     * @param   string $data data to be encrypted
     * @return  string
     */
    public function encode($data)
    {
        return base64_encode($data);
    }

    /**
     * Decrypts an encoded string back to its original value.
     *
     *     $data = $encrypt->decode($data);
     *
     * @param   string $data encoded string to be decrypted
     * @return  FALSE   if decryption fails
     * @return  string
     */
    public function decode($data)
    {
        return base64_decode($data);
    }

}
