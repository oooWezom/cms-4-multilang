<?php
namespace Core;

use Core\QB\DB;

class Cron
{

    /**
     *  Check cron table if need and send some letters
     */
    public function check()
    {
        if (!Config::get('main.cron') or !Config::get('main.selfCron')) {
            return false;
        }
        $ids = [];
        $result = DB::select()->from(Config::get('main.tableCron'))->limit(Config::get('main.selfCron'))->as_object()->execute();
        foreach ($result as $obj) {
            $ids[] = $obj->id;
            Email::send($obj->subject, $obj->text, $obj->email);
        }
        if (count($ids)) {
            DB::delete(Config::get('main.tableCron'))->where('id', 'IN', $ids)->execute();
        }
    }

}