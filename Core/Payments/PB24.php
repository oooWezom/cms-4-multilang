<?php
namespace Core\Payments;

class PB24
{

    private $merid = '105116';
    private $pass = 'K5kAJ2fcx7PnI88UQarxKL6ECt6RbB1G';
    private $apiurl;
    private $errmess;
    private $test = 1;
    private $wait = 90;

    function __construct($merchant = null, $password = null)
    {
        if ($merchant) {
            $this->merid = $merchant;
        }
        if ($password) {
            $this->pass = $password;
        }
    }

    static function factory($merchant = null, $password = null)
    {
        return new PB24($merchant, $password);
    }

    public function generate_order_id()
    {
        return base_convert(time(), 10, 16);
    }

    public function form($orderID, $amt, $details, $return_url, $server_url = '')
    {
        // $data = 'amt='.$amt.'&ccy=UAH&details='.$details.'&ext_details='.$details.'&pay_way=privat24&order='.$orderID.'&merchant='.$this->merid;
        // $signature = $this->calcSignature($data);
        $form = '<form action="https://api.privatbank.ua/p24api/ishop" method="POST">';
        $form .= '<input type="hidden" name="amt" value="' . (float)$amt . '" />';
        $form .= '<input type="hidden" name="ccy" value="UAH" />';
        $form .= '<input type="hidden" name="merchant" value="' . $this->merid . '" />';
        $form .= '<input type="hidden" name="order" value="' . $orderID . '" />';
        $form .= '<input type="hidden" name="details" value="' . $details . '" />';
        $form .= '<input type="hidden" name="ext_details" value="' . $details . '" />';
        $form .= '<input type="hidden" name="pay_way" value="privat24" />';
        $form .= '<input type="hidden" name="return_url" value="' . $return_url . '" />';
        $form .= '<input type="hidden" name="server_url" value="' . $server_url . '" />';
        // $form .= '<input type="hidden" name="signature" value="'.$signature.'" />';
        // $form .= '<input type="button" value="Оплатить" name="confirm" class="PB24" />';
        $form .= '<span class="PB24">' . $amt . ' грн</span>';
        $form .= '</form>';
        return $form;
    }

    public function check_order($orderID)
    {
        return $this->Merchant('ishop_pstatus', [['id' => time(), 'order' => $orderID]]);
    }

    public function get_merchant()
    {
        return $this->merid;
    }

    /**
     * @param (int) $id: 3 - НБУ, 5 - ПриватБанк
     */
    public function courses($id = 3)
    {
        $url = 'https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=' . $id;
        $this->apiurl = $url;
        $result = $this->sendPrpRequest();
        return $this->Response($result);
    }

    protected function toArrayMap($data)
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        return is_array($data) ? array_map([$this, __METHOD__], $data) : $data;
    }

    public function Response($xml, $array = 1)
    {
        $res = simplexml_load_string($xml);
        if ($array == 1) {
            return $this->toArrayMap($res);
        } else {
            return $res;
        }
    }

    /**
     *      Достаем баланс Мерчанта
     */
    public function balance()
    {
        $payments = [[
            'id' => time(),
        ]];
        $res = $this->Merchant('balance', $payments);
        $res = $this->Response($res);
        if ($this->getErrMessage()) {
            return '0.00';
        }
        return number_format($res['av_balance'], 2, '.', '');
    }

    /**
     * @param $request Может быть следующим:
     *          rest_yur - выписки по счёту мерчанта - юрлица
     *          rest_fiz - выписки по счёту мерчанта - физлица
     *          balance - текущий баланс по счёту мерчанта
     *          ishop_pstatus - проверка статуса платежа по интернет-эквайрингу
     */
    public function Info($request)
    {
        $url = 'https://api.privatbank.ua/p24api/' . $request;
        $this->apiurl = $url;
        $result = $this->sendPrpRequest();
        return $this->Response($result);
    }

    /**
     * @param $request Может быть следующим:
     *          pay_ua - платёж по Украине
     *          pay_visa - платёж на карту visa любого банка
     *          pay_pb - платёж на карту Приватбанка
     *          ishop_pstatus - проверка статуса платежа отправленного мерчанту через интернет-эквайринг
     *          cardlist - Список карт в аккаунте мерчанта
     */
    public function Merchant($request, $payments)
    {
        $url = 'https://api.privatbank.ua/p24api/' . $request;
        $this->apiurl = $url;
        $result = $this->sendCmtRequest($payments);
        if (isset($result[0]['message']) and trim($result[0]['message']) and isset($result[0]['state']) and !$result[0]['state']) {
            $this->errmess = $result[0]['message'];
        }
        if ($this->getErrMessage()) {
            return false;
        }
        return $result;
    }

    public function sendPrpRequest()
    { // отправка запроса prp, возвращает xml-ответ
        $data = '<oper>prp</oper>';
        return $this->sendRequest($data);
    }

    /*
     * отправка запроса на платёж
     * $payments - массив ассоциативных массивов реквизитов платежей
     * $wait - время задержки платежа в секундах
     * $isTest - тестовый ли платёж
     * возвращает такой же массив только с полями результата
     * или xml, если запрос был информационным
     */
    ### Пример массива $payments для $request = 'pay_pb'
    ### $payments = Array(Array('id' => 1,                          // Номер операции
    ###                         'phone' => '+380992740348',         // Телефон пользователя
    ###                         'b_card_or_acc' => '414943773609',  // Номер карты
    ###                         'details' => 'test',                // Описание
    ###                         'amt' => 5,                         // Сумма
    ###                         'ccy' => 'UAH'));                   // Валюта
    public function sendCmtRequest($payments)
    {
        $data = '<oper>cmt</oper>';
        $data .= '<wait>' . $this->wait . '</wait>';
        $data .= '<test>' . (($this->test) ? 1 : 0) . '</test>';
        foreach ($payments as $pay) {
            $data .= '<payment id="' . $pay['id'] . '">';
            foreach ($pay as $k => $v) {
                if ($k == 'id' || $k == 'debet' || $k == 'credit' || empty($v)) continue;
                $data .= '<prop name="' . $k . '" value="' . rawurlencode($v) . '" />';
            }
            $data .= '</payment>';
        }

        $resp = $this->sendRequest($data);
        if ($resp) {
            if (strpos($resp, "<info>") === false) { // запрос был пакетом платежей
                $dom = new DomDocument('1.0', 'UTF-8');
                $dom->loadXML($resp);
                $xpath = new DOMXPath($dom);
                $q_pays = '//response/data/payment';
                $pays = $xpath->query($q_pays);
                if ($pays->length == 0) {
                    $q_err = '//response/data/error';
                    $err = $xpath->query($q_err);
                    if ($err->length == 0) $this->errmess = "response: " . $resp;
                    else {
                        $this->errmess = $err->item(0)->getAttribute('message');
                    }
                    return false;
                }
                $rez = [];
                for ($i = 0; $i < $pays->length; $i++) {
                    $pay = $pays->item($i);
                    $payrez = [];
                    $payrez['id'] = $pay->getAttribute('id');
                    $payrez['state'] = $pay->getAttribute('state');
                    $payrez['message'] = $pay->getAttribute('message');
                    $payrez['ref'] = $pay->getAttribute('ref');
                    $payrez['amt'] = $pay->getAttribute('amt');
                    $payrez['ccy'] = $pay->getAttribute('ccy');
                    $payrez['comis'] = $pay->getAttribute('comis');
                    $payrez['code'] = $pay->getAttribute('code');
                    $rez[] = $payrez;
                }
                return $rez;
            } else { // запрос был информационным
                $start = strpos($resp, '<info>') + strlen('<info>');
                $end = strpos($resp, '</info>');
                return substr($resp, $start, ($end - $start));
            }
        } else {
            return;
        }
    }

    public function getErrMessage()
    {
        return $this->errmess;
    }

    public function sendRequest($data)
    {
        $str = '<?xml version="1.0" encoding="UTF-8"?><request version="1.0"><merchant>';
        $str .= '<id>' . $this->merid . '</id>';
        $str .= '<signature>' . $this->calcSignature($data) . '</signature>';
        $str .= '</merchant><data>' . $data . '</data></request>';
        return $this->msoap($str);
    }

    public function msoap($xml)
    { // транспортная ф-ция
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

    public function calcSignature($data)
    { // расчёт сигнатуры
        return sha1(md5($data . $this->pass));
    }

}