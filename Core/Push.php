<?php

namespace Core;

use Core\QB\DB;

class Push
{

    public static $table_user = 'push_users';
    public static $table_config = 'push_config';
    public static $table_send = 'push_send';
    public static $table_messages = 'push_message';
    public static $image = 'push';

    /**
     * Создание группы
     *
     * @param   varchar $group_name Название созданой группы
     * @param   varchar $reg_ids Ключ пользователя от GCM
     *
     * @return  Возвращает notification_key
     */
    public static function createGroup($group_name, $reg_ids)
    {

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://android.googleapis.com/gcm/notification",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"operation": "create", "notification_key_name": "' . $group_name . '", "registration_ids": ["' . $reg_ids . '"]}',
            CURLOPT_HTTPHEADER => [
                "authorization: key=AIzaSyDUJrKYxP8SACxMt3EyKK9RVKh6rVC53FQ",
                "cache-control: no-cache",
                "content-type: application/json",
                "project_id: 42931818645"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $key = json_decode($response);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $key->notification_key;
        }
    }

    /**
     * Отправялет пуш сообщение в группу
     *
     * @param   integer $key_group Ключ группы в которую отправляется пуш уведомление
     *
     * @return  json массив
     */
    public static function sendIntoGroup($key_group)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://android.googleapis.com/gcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"to" : "' . $key_group . '"}',
            CURLOPT_HTTPHEADER => [
                "authorization: key=AIzaSyDUJrKYxP8SACxMt3EyKK9RVKh6rVC53FQ",
                "cache-control: no-cache",
                "content-type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }
    }

    /**
     * Добавление пользователя в группу
     *
     * @param   integer $reg_id Регистрационный номер пользователя
     * @param   array $data Массив с данными группы (Название и ключ аутентификации)
     *
     * @return  id группы в которую отправили.
     */
    public static function addToGroup($reg_id, $data)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://android.googleapis.com/gcm/notification",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"operation": "add",
                                    "notification_key_name": "' . $data->name . '",
                                    "notification_key":"' . $data->notification_key . '",
                                    "registration_ids": ["' . $reg_id . '"]}',
            CURLOPT_HTTPHEADER => [
                "authorization: key=AIzaSyDUJrKYxP8SACxMt3EyKK9RVKh6rVC53FQ",
                "cache-control: no-cache",
                "content-type: application/json",
                "project_id: 42931818645"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $key = json_decode($response);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $key->notification_key;
        }
    }

    /**
     * Записываем пользователя в БД
     *
     * @param type $reg_id registarion ID пользователя
     * @param type $browser Браузер пользователя
     * @param type $ip IP адрес
     * @param type $user_id Если залогинен как пользователь то ID если нет NULL
     * @param type $date_subscribe Текущая дата
     *
     * @return true/false
     */
    public static function insertUserInfo($reg_id, $browser, $ip, $user_id, $date_subscribe)
    {
        DB::insert(static::$table_user, ['reg_id', 'browser', 'ip', 'user_id', 'time'])->values([$reg_id, $browser, $ip, $user_id, $date_subscribe])->execute();
    }

    /**
     * Записываем пуш сообщение в БД
     *
     * @param array() $data Массив елементов
     *
     * @return integer айди заинсерченйо записи
     */
    public static function insertPushBD($data)
    {
        if (!isset($data['created_at']) and Common::checkField(static::$table_messages, 'created_at')) {
            $data['created_at'] = time();
        }
        $keys = $values = [];
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
        $result = DB::insert(static::$table_messages, $keys)->values($values)->execute();
        if (!$result) {
            return false;
        }
        return $result[0];
    }

    /**
     * Загрузка иконки пуша
     *
     * @param type $id ID записи для
     * @param type $name Название поля
     *
     * @return boolean
     */
    public static function uploadIcon($id, $name, $field = 'icon')
    {
        if (!static::$image or !$id) {
            return false;
        }
        $filename = Files::uploadImage(static::$image, $name);
        if (!$filename) {
            return false;
        }
        if (!Common::checkField(static::$table_messages, $field)) {
            return true;
        }
        $path = 'http://kherson.net.ua/Media/images/push/push/' . $filename;
        return DB::update(static::$table_messages)->set([$field => $path])->where(static::$table_messages . '.id', '=', $id)->execute();
    }

    /**
     * Обновление информации пользователя
     *
     * @param integer $reg_id ID пользователя
     * @param integer $group_id ID группы в которую попал пользователь
     *
     * @return true/false
     */
    public static function updateUserInfo($reg_id, $group_id)
    {
        DB::update(static::$table_user)->set(['subscribe' => 1, 'group_user' => $group_id])->where('reg_id', '=', $reg_id)->execute();
    }

    /**
     * Получаем информацию из группы
     *
     * @param integer $id
     *
     * @return array информации группы
     */
    public static function dataGroup($id)
    {
        $data = DB::select()->from(static::$table_config)->where('id', '=', $id)->find();

        return $data;
    }

    /**
     * Информация последней добавленой группы
     *
     * @return array()
     *
     */
    public static function getlastGroup()
    {
        $data = DB::select()->from(static::$table_config)->where('id', '>', 0)->order_by('id', 'DESC')->find();

        return $data;
    }

    public static function insertGroup($data)
    {
        foreach ($data AS $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
        DB::insert(static::$table_config, $keys)->values($values)->execute();
    }

    /**
     * Кол-во юзверей в последней группе
     *
     * @param type $id ID группы
     * @return integer Кол-во подписавшихся пользователей
     */
    public static function countSubscribers($id)
    {
        $count = DB::select([DB::expr('COUNT(push_users.id)'), 'count'])->from('push_users')->where('group_user', '=', $id)->count_all();

        return $count;
    }

    /**
     * Получаем сообщение для отображения
     *
     * @return array() Сообщения
     *
     */
    public static function getMessage()
    {
        $push = DB::select()->from('push_message')->where('lifetime', '>', time())->order_by('id', 'DESC')->find();

        return $push;
    }

    /**
     * Записываем факт просмотра данного пуша данным пользователем
     *
     * @param integer $reg_id
     * @param integer $push_id
     */
    public static function addView($reg_id, $push_id)
    {
        DB::insert('push_sent', ['user_id', 'message_id'])->values([$reg_id, $push_id])->execute();
    }

}
