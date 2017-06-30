<?php
namespace Core;

class View
{

    public static function tpl($array, $tpl)
    {
        return View::show_tpl($array, $tpl . '.php');
    }


    public static function widget($array, $tpl)
    {
        return View::show_tpl($array, 'Widgets' . DS . $tpl . '.php');
    }


    static function show_tpl($tpl_data, $name_file)
    {
        ob_start();
        extract($tpl_data, EXTR_SKIP);
        $filepath = HOST . DS . 'Views' . DS . $name_file;
        if (!Config::get('error') && APPLICATION == 'backend') {
            $filepath = HOST . DS . 'Wezom' . DS . 'Views' . DS . $name_file;
        }
        include $filepath;
        return ob_get_clean();
    }


    static function core($data, $name_file)
    {
        ob_start();
        extract($data, EXTR_SKIP);
        include(HOST . DS . 'Core' . DS . $name_file . '.php');
        return ob_get_clean();
    }

}