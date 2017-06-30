<?php
namespace Core;

class HTTP
{

    /**
     * Redirect user to other URI
     * @param string $url - URI we need to relocate user
     * @param integer $type - number of redirect:
     *     300 Multiple Choices («множество выборов»)
     *     301 Moved Permanently («перемещено навсегда»)
     *     302 Moved Temporarily («перемещено временно»)
     *     303 See Other (смотреть другое)
     *     304 Not Modified (не изменялось)
     *     305 Use Proxy («использовать прокси»)
     *     307 Temporary Redirect («временное перенаправление»)
     */
    public static function redirect($url = '', $type = null)
    {
        if ($type !== null && ((int)$type < 300 || (int)$type > 307 || (int)$type == 306)) {
            $type = 300;
        }
        if (APPLICATION == 'backend') {
            header('Location: /' . trim($url, '/'), null, $type);
        } else {
            header('Location: ' . HTML::link($url), null, $type);
        }
        exit(0);
    }

}