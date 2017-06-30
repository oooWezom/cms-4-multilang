<?php
namespace Core;

use Mailer;

class Email
{
    /**
     * @param $id
     * @param array $vars
     * @param null $email
     * @param array $files
     * @return bool
     */
    public static function sendTemplate($id, $vars = [], $email = null, $files = [])
    {
        $tpl = CommonI18n::factory('mail_templates')->getRowSimple($id, 'id', 1);
        if (!$tpl) {
            return false;
        }
        $subject = str_replace(array_keys($vars), array_values($vars), $tpl->subject);
        $message = str_replace(array_keys($vars), array_values($vars), $tpl->text);
        return self::send($subject, $message, $email, $files);
    }

    /**
     * Send email
     * @param $subject
     * @param $message
     * @param null|string $email - receivers email
     * @param array $files - attachments
     * @return bool
     * @throws Mailer\MException
     */
    public static function send($subject, $message, $email = null, $files = [])
    {
        $from = Config::get('mail.sender_email') ?: Config::get('mail.admin_email');
        if (!$email) {
            $to = $from;
        } else {
            $to = $email;
        }
        $mail = new Mailer\PHPMailer;
        if (
            (boolean)Config::get('mail.smtp') &&
            Config::get('mail.host') && Config::get('mail.login') && Config::get('mail.pass') &&
            Config::get('mail.secure') && Config::get('mail.port')
        ) {
            $mail->isSMTP();
            $mail->Host = Config::get('mail.host');
            $mail->SMTPAuth = true;
            $mail->Username = Config::get('mail.login');
            $mail->Password = Config::get('mail.pass');
            $mail->SMTPSecure = Config::get('mail.secure');
            $mail->Port = Config::get('mail.port');
        }
        $mail->setFrom($from, Config::get('mail.name'));
        $mail->addReplyTo($from, Config::get('mail.name'));
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        $mail->addAddress($to);

        if (is_array($files) && count($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    $mail->addAttachment($file);
                }
            }
        }

        return $mail->Send();
    }

}