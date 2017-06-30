<?php
    namespace Wezom\Modules\Ajax\Controllers;

    use Core\Arr;
    use Core\Email;
    use Core\HTML;
    use Core\Message;
    use Core\QB\DB;

    class Config extends \Wezom\Modules\Ajax {

        public function testEmailAction() {
            $title = trim(Arr::get($this->post, 'title', ''));
            if (!$title) {
                $this->error(__('Заполните заголовок письма'));
            }
            $body = trim(Arr::get($this->post, 'body', ''));
            if (!$body) {
                $this->error(__('Заполните тело письма'));
            }
            $email = trim(Arr::get($this->post, 'email', ''));
            if (!$email or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error(__('Неправильный Email'));
            }

            if(Email::send($title, $body, $email)){
                $this->success(__('Письмо успешно отправлено'));
            }
            $this->error(__('Произошла ошибка при отправке письма. Проверьте настройки почты'));
        }
    }