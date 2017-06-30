<?php

namespace Wezom\Modules\Ajax\Controllers;

use Core\Arr;
use Core\Common;
use Core\Email;
use Core\HTML;
use Core\Message;
use Core\QB\DB;
use Core\Config;
use Core\View;
use Wezom\Modules\Orders\Models\OrdersItems;
use Wezom\Modules\User\Models\Users;
use Wezom\Modules\MailTemplates\Models\MailTemplates;

class Orders extends \Wezom\Modules\Ajax {

    /**
     * Generate associative array from serializeArray data
     * @param $data
     * @return array
     */
    public function getDataFromSerialize($data) {
        $arr = [];
        foreach ($data AS $el) {
            $arr[$el['name']] = $el['value'];
        }
        return $arr;
    }

    /**
     * Change status of the order
     * $this->post['id'] => order ID
     * $this->post['data'] => incoming serialized data
     */
    public function orderStatusAction() {
        if (!Arr::get($this->post, 'id')) {
            $this->error([
                'msg' => __('Выберите заказ!'),
            ]);
        }

        $__order = \Wezom\Modules\Orders\Models\Orders::getRow(Arr::get($this->post, 'id')); // Old older. We need status of this order

        $post = $this->getDataFromSerialize(Arr::get($this->post, 'data'));
        $statuses = Config::get('order.statuses');
        if (!isset($statuses[Arr::get($post, 'status')]) OR ! isset($post['status'])) {
            $this->error([
                'msg' => __('Укажите статус!'),
            ]);
        }

        if (Arr::get($post, 'status') != $__order->status) {
            Common::factory('orders')->update(['status' => Arr::get($post, 'status')], Arr::get($this->post, 'id'));

            if ((int) Arr::get($post, 'sendEmail', 0)) {
                $order = \Wezom\Modules\Orders\Models\Orders::getRow(Arr::get($this->post, 'id'));
                $mail = false;
                if (Arr::get($post, 'status') == 1) {
                    $mail = MailTemplates::getRowSimple(21,'id',1, $__order->user_lang);
                } else if (Arr::get($post, 'status') == 3) {
                    $mail = MailTemplates::getRowSimple(20,'id',1, $__order->user_lang);
                }
                if ($mail && filter_var($order->email, FILTER_VALIDATE_EMAIL)) {
                    $from = ['{{site}}', '{{name}}', '{{last_name}}', '{{middle_name}}', '{{amount}}', '{{id}}'];
                    $to = [
                        Arr::get($_SERVER, 'HTTP_HOST'), $order->name, $order->last_name, $order->middle_name,
                        $order->amount, $order->id
                    ];
                    $subject = str_replace($from, $to, $mail->subject);
                    $text = str_replace($from, $to, $mail->text);
                    Email::send($subject, $text, $order->email);
                }
            }

            if ($__order->user_id) {
                $user = Users::getForOrder($__order->user_id);
                if ($user && $user->partner) {
                    if (Arr::get($post, 'status') == 1) {
                        Common::factory('orders')->update(['done' => time()], Arr::get($this->post, 'id'));
                    } else if (Arr::get($post, 'status') != 1 && $__order->status == 1) {
                        Common::factory('orders')->update(['done' => NULL], Arr::get($this->post, 'id'));
                    }
                }
            }
        }

        Message::GetMessage(1, __('Данные сохранены!'));
        $this->success(['reload' => 1]);
    }

    /**
     * Change delivery settings
     * $this->post['id'] => order ID
     * $this->post['data'] => incoming serialized data
     */
    public function orderDeliveryAction() {
        if (!Arr::get($this->post, 'id')) {
            $this->error([
                'msg' => __('Выберите заказ!'),
            ]);
        }
        $post = $this->getDataFromSerialize(Arr::get($this->post, 'data'));
        $delivery = Config::get('order.delivery');
        if (!isset($delivery[Arr::get($post, 'delivery')]) OR ! isset($post['delivery'])) {
            $this->error([
                'msg' => __('Укажите способ доставки!'),
            ]);
        }
        $data = ['delivery' => Arr::get($post, 'delivery')];
        Common::factory('orders')->update($data, Arr::get($this->post, 'id'));
        $this->success([
            'msg' => __('Данные сохранены!'),
        ]);
    }

    /**
     * Change payment settings
     * $this->post['id'] => order ID
     * $this->post['data'] => incoming serialized data
     */
    public function orderPaymentAction() {
        if (!Arr::get($this->post, 'id')) {
            $this->error([
                'msg' => __('Выберите заказ!'),
            ]);
        }
        $post = $this->getDataFromSerialize(Arr::get($this->post, 'data'));
        $payment = Config::get('order.payment');
        if (!isset($payment[Arr::get($post, 'payment')]) OR ! isset($post['payment'])) {
            $this->error([
                'msg' => __('Неверные данные!'),
            ]);
        }
        Common::factory('orders')->update(['payment' => Arr::get($post, 'payment')], Arr::get($this->post, 'id'));
        $this->success([
            'msg' => __('Данные сохранены!'),
        ]);
    }

    /**
     * Change user information
     * $this->post['id'] => order ID
     * $this->post['data'] => incoming serialized data
     */
    public function orderUserAction() {
        if (!Arr::get($this->post, 'id')) {
            $this->error([
                'msg' => __('Выберите заказ!'),
            ]);
        }
        $post = $this->getDataFromSerialize(Arr::get($this->post, 'data'));
        if (!Arr::get($post, 'phone')) {
            $this->error([
                'msg' => __('Укажите номер телефона!'),
            ]);
        }
        if (!Arr::get($post, 'name')) {
            $this->error([
                'msg' => __('Укажите имя!'),
            ]);
        }if (!Arr::get($post, 'last_name')) {
            $this->error([
                'msg' => __('Укажите фамилию!'),
            ]);
        }
        if (!Arr::get($post, 'email')) {
            $this->error([
                'msg' => __('Укажите E-Mail!'),
            ]);
        }
        Common::factory('orders')->update([
            'name' => Arr::get($post, 'name'),
            'last_name' => Arr::get($post, 'last_name'),
            'middle_name' => Arr::get($post, 'middle_name'),
            'email' => Arr::get($post, 'email'),
            'phone' => Arr::get($post, 'phone'),
        ], Arr::get($this->post, 'id'));
        $this->success([
            'msg' => __('Данные сохранены!'),
        ]);
    }

    /**
     * TODO update this method and script in my.js to array changes and without "size_id"
     * Change items information
     * $this->post['id'] => order ID
     * $this->post['catalog_id'] => item ID
     * $this->post['count'] => new item count in order
     * $this->post['size_id'] => item size ID
     */
    public function orderItemsAction() {
        if (!Arr::get($this->post, 'id')) {
            $this->error();
        }
        if (!Arr::get($this->post, 'catalog_id')) {
            $this->error();
        }
        $res = DB::update('orders_items')->set([
                    'count' => Arr::get($this->post, 'count'),
        ])
                ->where('order_id', '=', Arr::get($this->post, 'id'))
                ->where('catalog_id', '=', Arr::get($this->post, 'catalog_id'))
                ->execute();
        if ($res) {
            Common::factory('orders')->update(['changed' => 1], Arr::get($this->post, 'id'));
            $this->success(['email_button' => true]);
        }
        $this->success(['email_button' => false]);
    }

    /**
     * Delete item position from the order
     * $this->post['order_id'] => order ID
     * $this->post['order_item_id'] => order item ID
     */
    public function orderPositionDeleteAction() {
        $orderId = Arr::get($this->post, 'order_id');
        if (!$orderId) {
            $this->error();
        }
        $itemId = Arr::get($this->post, 'order_item_id');
        if (!$itemId) {
            $this->error();
        }
        if (OrdersItems::delete($itemId)) {
            Common::factory('orders')->update(['changed' => 1], $orderId);
        }
        $this->success([
            'msg' => __('Позиция удалена!'),
        ]);
    }

    public function sendEmailAction() {
        $id = (int) Arr::get($this->post, 'id');
        if (!$id) {
            $this->error(__('Такого заказа не существует!'));
        }
        $order = \Wezom\Modules\Orders\Models\Orders::getRow($id);
        if ($order->changed != 1) {
            $this->error(__('Содержимое заказа не было изменено!'));
        }
        $items = OrdersItems::getRows($id);
        if (!$items) {
            $this->error(__('Невозможно отправить оповещение о пустом заказе!'));
        }
        
        Email::sendTemplate(15, [
            '{{site}}' => Arr::get($_SERVER, 'HTTP_HOST'),
            '{{name}}' => $order->name,
            '{{last_name}}' => $order->last_name,
            '{{middle_name}}' => $order->middle_name,
            '{{id}}' => $order->id,
            '{{amount}}' => $order->amount,
            '{{items}}' => View::tpl(['cart' => $items], 'Catalog/ItemsMail')
        ], $order->email);
        Common::factory('orders')->update(['changed' => 0], $order->id);
        $this->success();
    }

}
