<?php
    namespace Core\SMS;

    // Privat bank OTP API. merid & pass = merchant ID & password from Privat24 cabinet
    use Core\Arr;
    use Core\GeoIP;

    class OTP {

        private $merid = '###'; // Merchant ID
        private $pass = '###'; // Merchant password
        private $apiurl = 'https://otp.privatbank.ua/otp.cgi';

        public static function factory() {
            return new OTP();
        }

        public function send($phone) {
            $phone = trim($phone);
            if( !$this->check_phone($phone) ) {
                return False;
            }
            $result = $this->sendRequest($phone);
            $response = $this->Response($result);
            if ( Arr::get($response, 'xml_status') != 'success' ) {
                return False;
            }
            $response = Arr::get($response, 'phone_auth_create');
            if ( Arr::get($response, 'status') != 'success' ) {
                return False;
            }
            return True;
        }

        public function check( $code, $phone ) {
            $result = $this->checkRequest($phone, $code);
            $response = $this->Response($result);
            if ( Arr::get($response, 'status') != 'success' ) {
                return False;
            }
            return True;
        }

        private function toArrayMap($data) {
            if (is_object($data)) { $data = get_object_vars($data); }
            return is_array($data) ? array_map([$this, __METHOD__], $data) : $data;
        }

        private function Response($xml, $array = 1) {
            $res = simplexml_load_string($xml);
            if($array == 1) {
                return $this->toArrayMap($res);
            } else {
                return $res;
            }
        }

        public function check_phone($phone) {
            if(!$phone) {
                return False;
            }
            if(strlen($phone) < 6) {
                return False;
            }
            if(strlen($phone) > 20) {
                return False;
            }
            return True;
        }

        private function sendRequest($phone) {
            $request = '<?xml version="1.0" encoding="UTF-8"?>';
            $request .= '<request>';
            $request .= '<merchant>';
            $request .= '<id>'.$this->merid.'</id>';
            $request .= '<password>'.$this->pass.'</password>';
            $request .= '<version>2.0</version>';
            $request .= '</merchant>';
            $request .= '<phone_auth_create>';
            $request .= '<phone>'.$phone.'</phone>';
            $request .= '<user_ip>'.GeoIP::ip().'</user_ip>';
            $request .= '<category></category>';
            $request .= '<sms_description></sms_description>';
            $request .= '<order_id></order_id>';
            $request .= '<description></description>';
            $request .= '<direct_auth></direct_auth>';
            $request .= '</phone_auth_create>';
            $request .= '</request>';
            return $this->msoap($request);
        }

        private function checkRequest($phone, $code) {
            $request = '<?xml version="1.0" encoding="UTF-8"?>';
            $request .= '<request>';
            $request .= '<merchant>';
            $request .= '<id>'.$this->merid.'</id>';
            $request .= '<password>'.$this->pass.'</password>';
            $request .= '<version>2.0</version>';
            $request .= '</merchant>';
            $request .= '<phone_auth>';
            $request .= '<password>'.$code.'</password>';
            $request .= '<phone>'.$phone.'</phone>';
            $request .= '<user_ip>'.GeoIP::ip().'</user_ip>';
            $request .= '<category></category>';
            $request .= '<order_id></order_id>';
            $request .= '<operation_id></operation_id>';
            $request .= '</phone_auth>';
            $request .= '</request>';
            return $this->msoap($request);
        }

        private function msoap($xml) { // транспортная ф-ция
            $header = [];
            $header[] = "Content-Type: text/xml";
            $header[] = "\r\n";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            $rez = curl_exec($ch);
            curl_close($ch);
            return $rez;
        }

    }