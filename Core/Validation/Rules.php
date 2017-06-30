<?php
    namespace Core\Validation;

    use Core\QB\DB;
    use Core\Route;
    use Core\Text;

    class Rules {

        /**
         * Checks if a field is not empty.
         * @return  boolean
         */
        public static function not_empty($value) {
            if (is_object($value) AND $value instanceof \ArrayObject) {
                // Get the array from the ArrayObject
                $value = $value->getArrayCopy();
            }
            // Value cannot be NULL, FALSE, '', or an empty array
            return ! in_array($value, array(NULL, FALSE, '', array()), TRUE);
        }


        /**
         * Checks a field against a regular expression.
         * @param   string  $value      value
         * @param   string  $expression regular expression to match (including delimiters)
         * @return  boolean
         */
        public static function regex($value, $expression) {
            return (bool) preg_match($expression, (string) $value);
        }


        /**
         * Checks that a field is long enough.
         * @param   string  $value  value
         * @param   integer $length minimum length required
         * @return  boolean
         */
        public static function min_length($value, $length) {
            return Text::strlen($value) >= $length;
        }


        /**
         * Checks that a field is short enough.
         * @param   string  $value  value
         * @param   integer $length maximum length required
         * @return  boolean
         */
        public static function max_length($value, $length) {
            return Text::strlen($value) <= $length;
        }


        /**
         * Checks that a field is exactly the right length.
         *
         * @param   string          $value  value
         * @param   integer|array   $length exact length required, or array of valid lengths
         * @return  boolean
         */
        public static function exact_length($value, $length)
        {
            if (is_array($length))
            {
                foreach ($length as $strlen)
                {
                    if (Text::strlen($value) === $strlen)
                        return TRUE;
                }
                return FALSE;
            }

            return Text::strlen($value) === $length;
        }


        /**
         * Check an email address for correct format.
         * @param   string  $email  email address
         * @return  boolean
         */
        public static function email($email) {
            if (Text::strlen($email) > 254) {
                return FALSE;
            }
            $expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})$/iD';
            return (bool) preg_match($expression, (string) $email);
        }


        /**
         * Validate a URL.
         * @param   string  $url    URL
         * @return  boolean
         */
        public static function url($url) {
            // Based on http://www.apps.ietf.org/rfc/rfc1738.html#sec-5
            if ( ! preg_match(
                '~^

                # scheme
                [-a-z0-9+.]++://

                # username:password (optional)
                (?:
                        [-a-z0-9$_.+!*\'(),;?&=%]++   # username
                    (?::[-a-z0-9$_.+!*\'(),;?&=%]++)? # password (optional)
                    @
                )?

                (?:
                    # ip address
                    \d{1,3}+(?:\.\d{1,3}+){3}+

                    | # or

                    # hostname (captured)
                    (
                             (?!-)[-a-z0-9]{1,63}+(?<!-)
                        (?:\.(?!-)[-a-z0-9]{1,63}+(?<!-)){0,126}+
                    )
                )

                # port (optional)
                (?::\d{1,5}+)?

                # path (optional)
                (?:/.*)?

                $~iDx', $url, $matches))
                return FALSE;

            // We matched an IP address
            if ( ! isset($matches[1]))
                return TRUE;

            // Check maximum length of the whole hostname
            // http://en.wikipedia.org/wiki/Domain_name#cite_note-0
            if (strlen($matches[1]) > 253)
                return FALSE;

            // An extra check for the top level domain
            // It must start with a letter
            $tld = ltrim(substr($matches[1], (int) strrpos($matches[1], '.')), '.');
            return ctype_alpha($tld[0]);
        }


        /**
         * Tests if a string is a valid date string.
         * @param   string  $str    date to check
         * @return  boolean
         */
        public static function date($str) {
            return (strtotime($str) !== FALSE);
        }


        /**
         * Checks whether a string consists of alphabetical characters only.
         * @param   string  $str    input string
         * @param   boolean $utf8   trigger UTF-8 compatibility
         * @return  boolean
         */
        public static function alpha($str, $utf8 = TRUE) {
            $str = (string) $str;
            if ($utf8 === TRUE) {
                return (bool) preg_match('/^\pL++$/uD', $str);
            } else {
                return ctype_alpha($str);
            }
        }


        /**
         * Checks whether a string consists of digits only (no dots or dashes).
         * @param   string  $str    input string
         * @param   boolean $utf8   trigger UTF-8 compatibility
         * @return  boolean
         */
        public static function digit($str, $utf8 = TRUE) {
            if ($utf8 === TRUE) {
                return (bool) preg_match('/^\pN++$/uD', $str);
            } else {
                return (is_int($str) AND $str >= 0) OR ctype_digit($str);
            }
        }


        /**
         * Checks whether a string is a valid number (negative and decimal numbers allowed).
         * @param   string  $str    input string
         * @return  boolean
         */
        public static function numeric($str) {
            // Get the decimal point for the current locale
            list($decimal) = array_values(localeconv());
            // A lookahead is used to make sure the string contains at least one digit (before or after the decimal point)
            return (bool) preg_match('/^-?+(?=.*[0-9])[0-9]*+'.preg_quote($decimal).'?+[0-9]*+$/D', (string) $str);
        }


        /**
         * Checks if a string is a proper hexadecimal HTML color value. The validation
         * is quite flexible as it does not require an initial "#" and also allows for
         * the short notation using only three instead of six hexadecimal characters.
         *
         * @param   string  $str    input string
         * @return  boolean
         */
        public static function color($str) {
            return (bool) preg_match('/^#?+[0-9a-f]{3}(?:[0-9a-f]{3})?$/iD', $str);
        }


        /**
         * Checks whether a string is a valid number (decimal numbers allowed) bigger than zero.
         * @param   string  $str    input string
         * @return  boolean
         */
        public static function pos_numeric($str) {
            if( static::numeric($str) && $str > 0 ) {
                return TRUE;
            }
            return FALSE;
        }


        public static function unique($str, $field, $table)  {
            if(Route::param('id')) {
                $res = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($table)->where($field, '=', $str)->where('id', '!=', Route::param('id'))->count_all();
            } else {
                $res = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($table)->where($field, '=', $str)->count_all();
            }
            if($res) {
                return FALSE;
            }
            return TRUE;
        }

    }
