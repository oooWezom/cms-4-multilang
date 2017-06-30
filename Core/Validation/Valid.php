<?php
    namespace Core\Validation;

    use Core\Arr;
    use Core\Config;
    use Core\HTML;

    class Valid {

        public $_rules = array();
        public $_rulesI18n = array();
        public $_data = array();
        public $_errors = array();


        public function __construct(array $data = array(), array $rules = array(), array $rulesI18n = array()) {
            $this->_data = $data;
            $this->_rules = $rules;
            $this->_rulesI18n = $rulesI18n;
        }


        /**
         * @return array
         */
        public function execute() {
            $r = new \Core\Validation\Rules;
            foreach($this->_rules AS $field => $rules) {
                if( is_array($rules) && count($rules) ) {
                    $value = NULL;
                    if(!array_key_exists($field, $this->_data) && strpos($field, '/') !== false) {
                        $arr = explode('/', $field);
                        if(isset($this->_data[$arr[0]][$arr[1]])) {
                            $value = trim($this->_data[$arr[0]][$arr[1]]);
                        }
                    } else if(array_key_exists($field, $this->_data)) {
                        $value = trim($this->_data[$field]);
                    }
                    foreach($rules AS $rule) {
                        $method = Arr::get($rule, 'key');
                        if( !method_exists($r, $method) ) {
                            continue;
                        }
                        if( in_array($rule['key'], array('regex', 'min_length', 'max_length')) ) {
                            $success = $r::$method($value, Arr::get($rule, 'value'));
                        } else if($rule['key'] == 'unique') {
                            $success = $r::$method($value, $field, Arr::get($rule, 'value'));
                        } else {
                            $success = $r::$method($value);
                        }
                        if( !$success ) {
                            $this->_errors[] = $rule['error'];
                        }
                    }
                }
            }

            foreach(Config::get('languages') AS $k => $v) {
                $data = Arr::get($this->_data, $k, array());
                foreach($this->_rulesI18n AS $field => $rules) {
                    if( is_array($rules) && count($rules) ) {
                        if(array_key_exists($field, $data)) {
                            $value = trim($data[$field]);
                        } else {
                            $value = NULL;
                        }
                        foreach($rules AS $rule) {
                            $method = Arr::get($rule, 'key');
                            if( !method_exists($r, $method) ) {
                                continue;
                            }
                            if( in_array($rule['key'], array('regex', 'min_length', 'max_length')) ) {
                                $success = $r::$method($value, Arr::get($rule, 'value'));
                            } else if($rule['key'] == 'unique') {
                                $success = $r::$method($value, $field, Arr::get($rule, 'value'));
                            } else {
                                $success = $r::$method($value);
                            }
                            if( !$success ) {
                                $this->_errors[] = str_replace(':lang', $v['name'], $rule['error']);
                            }
                        }
                    }
                }
            }

            return $this->_errors;
        }

        /**
         * @param array $errors
         * @return string
         */
        public static function message(array $errors) {
            $message = '<p>Во время заполнения формы возникли следующие ошибки</p>';
            $message .= '<ul>';
            foreach($errors AS $error) {
                $message .= '<li>'.$error.'</li>';
            }
            $message .= '</ul>';
            return $message;
        }

    }