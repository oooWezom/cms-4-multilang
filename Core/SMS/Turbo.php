<?php
    namespace Core\SMS;

    use Core\Cookie;

    class Turbo {
        private $_sender = '###';
        private $_login = '###';
        private $_password = '###';
        private $_expires = 600;
        private $_length = 8;
        private $_type = 'numeric';

        public $_test = false;
        public $_test_answer = true;

        public static function factory() {
            return new self();
        }

        public function get_expire_period() {
            return $this->_expires;
        }

        public function get_code_length() {
            return $this->_length;
        }

        public function get_code_type() {
            return $this->_type;
        }

        private function check_phone($phone) {
            if(!$phone) {
                return False;
            }
            if(strlen($phone) < 6) {
                return False;
            }
            if(strlen($phone) > 20) {
                return False;
            }
            preg_match('/^\+380[0-9]*$/', $phone, $math1);
            preg_match('/^\+7[0-9]*$/', $phone, $math2);
            if(!$math1[0] && !$math2[0]) {
                return False;
            }
            return True;
        }

        private function check_message($message) {
            if(!$message) {
                return False;
            }
            return True;
        }

        public function send($phone, $message) {
            # Check phone number and message for correct
            $message = strip_tags($message);
            $message = trim($message);
            $phone = trim($phone);
            if(!$this->check_phone($phone) || !$this->check_message($message)) {
                return False;
            }
            # Get client by SOAP
            $client = new \SoapClient('http://turbosms.in.ua/api/wsdl.html');
            # Auth data
            $auth = [
                'login'     => $this->_login,
                'password'  => $this->_password,
            ];
            # Login to service
            $result = $client->Auth($auth);
            if (!$result->AuthResult) {
                return False;
            }
            # Get balance
            $credits = $client->GetCreditBalance();
            if (!$credits->GetCreditBalanceResult) {
                return False;
            }
            # SMS data
            $sms = [
                'sender'        => $this->_sender,
                'destination'   => $phone,
                'text'          => $message,
            ];
            # Send SMS
            $resultSend = $client->SendSMS($sms);
            if ( !is_array($resultSend->SendSMSResult->ResultArray) ) {
                return False;
            }
            # If something wrong
            return True;
        }

        public function set_cookie($code, $phone) {
            # Set cookie
            $data = $this->hash_data(['code' => $code, 'phone' => $phone]);
            Cookie::delete('sms');
            Cookie::set('sms', $data, $this->_expires);
        }

        public function check_cookie($code, $phone) {
            $data = $this->hash_data(['code' => $code, 'phone' => $phone]);
            $cookie = Cookie::get('sms');
            if($cookie != $data) {
                return False;
            }
            Cookie::delete('sms');
            return True;
        }

        private function hash_data($data) {
            $data = json_encode($data);
            $data = base64_encode($data);
            $data = sha1($data);
            $data = strtoupper($data);
            return $data;
        }

    }