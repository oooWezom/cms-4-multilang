<?php
namespace Core\Payments;
/**
 * Liqpay Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category        LiqPay
 * @package         liqpay/liqpay
 * @version         3.0
 * @author          Liqpay
 * @copyright       Copyright (c) 2014 Liqpay
 * @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *
 * EXTENSION INFORMATION
 *
 * LIQPAY API       https://www.liqpay.com/ru/doc
 *
 */

/**
 * Payment method liqpay process
 *
 * @author      Liqpay <support@liqpay.com>
 */
class LiqPay
{

    private $_api_url = 'https://www.liqpay.com/api/';
    private $_checkout_url = 'https://www.liqpay.com/api/checkout';
    private $_supportedCurrencies = ['EUR', 'UAH', 'USD', 'RUB', 'RUR'];
    private $_public_key;
    private $_private_key;

    public $statuses = [
        'success' => 'Успешный платеж',
        'failure' => 'Неуспешный платеж',
        'wait_secure' => 'Платеж на проверке',
        'wait_accept' => 'Деньги с клиента списаны, но магазин еще не прошел проверку',
        'wait_lc' => 'Аккредитив. Деньги с клиента списаны, ожидается подтверждение доставки товара',
        'processing' => 'Платеж обрабатывается',
        'sandbox' => 'тестовый платеж',
        'subscribed' => 'Подписка успешно оформлена',
        'unsubscribed' => 'Подписка успешно деактивирована',
        'reversed' => 'Возврат клиенту после списания',
    ];


    public static function factory($public_key, $private_key)
    {
        return new LiqPay($public_key, $private_key);
    }


    /**
     * Constructor.
     *
     * @param string $public_key
     * @param string $private_key
     */
    public function __construct($public_key, $private_key)
    {
        if (empty($public_key)) {
            die('public_key is empty');
        }

        if (empty($private_key)) {
            die('private_key is empty');
        }

        $this->_public_key = $public_key;
        $this->_private_key = $private_key;
    }


    /**
     * Call API
     *
     * @param string $path - url
     * @param array $params
     * @return string
     */
    public function api($path, $params = [])
    {
        if (!isset($params['version'])) {
            die('version is null');
        }
        $url = $this->_api_url . $path;
        $public_key = $this->_public_key;
        $private_key = $this->_private_key;
        $data = base64_encode(json_encode(array_merge(compact('public_key'), $params)));
        $signature = base64_encode(sha1($private_key . $data . $private_key, 1));
        $postfields = http_build_query([
            'data' => $data,
            'signature' => $signature
        ]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output);
    }


    /**
     * cnb_form
     *
     * @param array $params
     * @return string
     */
    public function cnb_form($params)
    {
        $language = 'ru';
        $params = $this->cnb_params($params);
        $data = base64_encode(json_encode($params));
        $signature = $this->cnb_signature($params);
        return sprintf('
                <form method="POST" action="%s" accept-charset="utf-8" name="payment">
                    %s
                    %s
                </form>
                ',
            $this->_checkout_url,
            sprintf('<input type="hidden" name="%s" value="%s" />', 'data', $data),
            sprintf('<input type="hidden" name="%s" value="%s" />', 'signature', $signature),
            $language
        );
    }


    /**
     * cnb_signature
     *
     * @param array $params
     * @return string
     */
    public function cnb_signature($params)
    {
        $params = $this->cnb_params($params);
        $private_key = $this->_private_key;
        $json = base64_encode(json_encode($params));
        $signature = $this->str_to_sign($private_key . $json . $private_key);
        return $signature;
    }


    /**
     * cnb_params
     *
     * @param array $params
     * @return array $params
     */
    private function cnb_params($params)
    {
        $params['public_key'] = $this->_public_key;
        if (!isset($params['version'])) {
            die('version is null');
        }
        if (!isset($params['amount'])) {
            die('amount is null');
        }
        if (!isset($params['currency'])) {
            die('currency is null');
        }
        if (!in_array($params['currency'], $this->_supportedCurrencies)) {
            die('currency is not supported');
        }
        if ($params['currency'] == 'RUR') {
            $params['currency'] = 'RUB';
        }
        if (!isset($params['description'])) {
            die('description is null');
        }
        return $params;
    }


    /**
     * str_to_sign
     *
     * @param string $str
     * @return string
     */
    public function str_to_sign($str)
    {
        $signature = base64_encode(sha1($str, 1));
        return $signature;
    }


    /**
     * Valid incoming in server action data
     * @return bool
     */
    public function server_valid()
    {
        if (!isset($_POST['data']) || !isset($_POST['sign'])) {
            return false;
        }
        $sign = base64_encode(sha1($this->_private_key . $_POST['data'] . $this->_private_key, 1));
        if ($sign !== $_POST['sign']) {
            return false;
        }
        $json = base64_decode($_POST['data']);
        $data = json_decode($json);
        if (!is_array($data)) {
            return false;
        }
        if (!isset($data['version']) or $data['version'] != 3) {
            return false;
        }
        if (!isset($data['public_key']) or $data['public_key'] !== $this->_public_key) {
            return false;
        }
        if (!isset($data['order_id']) || !isset($data['status']) || !isset($this->statuses[$data['status']])) {
            return false;
        }

        // GET ORDER ROW HERE
        $order = mysql::query_one('SELECT * FROM order_number WHERE id = ' . $data['order_id']);
        if ($order->liqpay_status && !in_array($order->liqpay_status, ['wait_secure', 'wait_accept', 'wait_lc', 'processing'])) {
            return false;
        }

        // GET ITEMS FROM ORDER
        $items = mysql::query('SELECT * FROM orders WHERE number_order = ' . $data['order_id']);

        // GET COUNT OF GOODS, AMOUNT
        $amount = 0;
        $goods = 0;
        $amount += $order->cost_deliver;
        foreach ($items as $value) {
            $goods += $value->kolvo;
            $amount += $value->cost * $value->kolvo;
        }
        if (!isset($data['amount']) or $data['amount'] !== (float)number_format($amount, 2, '.', '')) {
            return false;
        }
        if (!isset($data['currency']) or $data['currency'] != 'USD') {
            return false;
        }

        // GENERATE DESCRIPTION
        $description = $goods . ' items for ' . number_format($amount - $order->cost_deliver, 2, '.', '') . ' USD + delivery for ' . number_format($order->cost_deliver, 2, '.', '') . ' USD';
        if (!isset($data['description']) or $data['description'] != $description) {
            return false;
        }

        return true;
    }

}
