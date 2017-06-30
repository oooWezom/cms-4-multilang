<?php
namespace Modules\Unsubscribe\Controllers;

use Core\Common;
use Core\Route;
use Core\HTTP;
use Core\Message;
use Modules\Base;

class Unsubscribe extends Base
{

    public function indexAction()
    {
        $model = Common::factory('subscribers');
        $subscriber = $model->getRow(Route::param('hash'), 'hash', 1);
        if (!$subscriber) {
            Message::GetMessage(0, __('Вы не подписаны на рассылку с нашего сайта!'));
            HTTP::redirect('/');
        }
        $model->update(['status' => 0], $subscriber->id);
        Message::GetMessage(1, __('Вы успешно отписались от рассылки новостей с нашего сайта!'));
        HTTP::redirect('/');
    }

}