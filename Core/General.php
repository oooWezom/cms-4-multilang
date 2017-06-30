<?php
namespace Core;

class General
{

    public static function crop($type, $folder, $image, $uri = null)
    {
        $uri = $uri ?: $_SERVER['REQUEST_URI'];
        $array = [$type, $image, $uri, $folder];
        $json = json_encode($array);
        $hash = Encrypt::instance()->encode($json);
        return HTML::link('wezom/crop?hash=' . urlencode($hash));
    }

}