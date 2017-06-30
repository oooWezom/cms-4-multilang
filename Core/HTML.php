<?php
namespace Core;

use Forms\Form;
use I18n;

class HTML
{

    /**
     * Generate good link. Useful in multi language sites
     * @param  string $link - link
     * @param bool $http
     * @return string - good link
     */
    public static function link($link = '', $http = true, $useLang = false)
    {
        if (strpos($link, '//') !== false) {
            return $link;
        }

        $link = trim($link, '/');
        if ($link == 'index') {
            $link = '';
        }

        if (MULTI_LANGUAGE && I18n::$linkType == I18n::LINK_TYPE_SUB_DOMAIN) {
            return '//' . I18n::createDomain() . '/' . trim($link, '/');
        } elseif (MULTI_LANGUAGE and !$useLang) {
            if (\I18n::$lang != \I18n::$default_lang) {
                $link = \I18n::$lang.'/'.trim($link, '/');
            }
        }
        
        if ($http) {
            return '//' . $_SERVER['HTTP_HOST'] . '/' . trim($link, '/');
        }

        return '/' . trim($link, '/');
    }


    /**
     *  Generate breadcrumbs from array
     * @param  array $bread - array with names and links
     * @return string        - breadcrumbs HTML
     */
    public static function breadcrumbs($bread)
    {
        if (count($bread) <= 1) {
            return '';
        }
        $last = $bread[count($bread) - 1];
        unset($bread[count($bread) - 1]);
        $html = '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
        foreach ($bread as $value) {
            $html .= '<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="' . HTML::link($value['link']) . '">' . $value['name'] . '</a></span>';
        }
        $html .= '<span>' . $last['name'] . '</span>';
        $html .= '</div>';
        return $html;
    }


    /**
     *  Generate breadcrumbs from array for wezom
     * @param  array $bread - array with names and links
     * @return string        - breadcrumbs HTML
     */
    public static function backendBreadcrumbs($bread, $help = false)
    {
        if (count($bread) <= 1) {
            return '';
        }
        $last = $bread[count($bread) - 1];
        unset($bread[count($bread) - 1]);
        if (!count($bread)) {
            return '';
        }
        $first = $bread[0];
        unset($bread[0]);
        $html = '<div class="crumbs"><ul class="breadcrumb">';
        $html .= '<li><i class="fa fa-home"></i><a href="' . HTML::link($first['link']) . '">&nbsp;' . $first['name'] . '</a></li>';
        foreach ($bread as $value) {
            $html .= '<li><a href="' . HTML::link($value['link']) . '">&nbsp;' . $value['name'] . '</a></li>';
        }
        $html .= '<li class="current" style="color: #949494 !important;">&nbsp;' . $last['name'] . '</li>';
        $html .= '</ul>';

        if ($help != false) {
            $html .= '<ul class="crumb-buttons">
						<li class="first">
							<a title="" href="' . $help . '" class="help-video"><i class="fa fa-question"></i><span>Помощь</span></a>
						</li></ul>';
        }
        $html .= '</div>';
        return $html;
    }


    /**
     * Create path to media in frontend
     * @param  string $file - path to file
     * @param  bool $absolute - use absolute path
     * @return string
     */
    public static function media($file, $absolute = true, $version = false)
    {
        if ($version) {
            $v = '?v=' . filemtime('Media/' . trim($file, '/'));
        }
        if ($absolute) {
            return '//' . $_SERVER['HTTP_HOST'] . '/Media/' . trim($file, '/') . $v;
        }
        return '/Media/' . trim($file, '/') . $v;
    }

    /**
     * Create path to media in wezom
     * @param  string $file - path to file
     * @return string
     */
    public static function bmedia($file)
    {
        return DS . 'Wezom' . DS . 'Media' . DS . trim($file, DS);
    }


    /**
     * Put die after <pre>
     * @param mixed $object - what we want to <pre>
     */
    public static function preDie($object)
    {
        echo '<pre>';
        print_r($object);
        echo '</pre>';
        die;
    }


    /**
     * Emulation of php function getallheaders()
     */
    public static function emu_getallheaders()
    {
        foreach ($_SERVER as $h => $v)
            if (preg_match('/HTTP_(.+)/', $h, $hp))
                $headers[$hp[1]] = $v;
        return $headers;
    }


    /**
     * Convert special characters to HTML entities. All untrusted content
     * should be passed through this method to prevent XSS injections.
     *
     *     echo HTML::chars($username);
     *
     * @param   string $value string to convert
     * @param   boolean $double_encode encode existing entities
     * @return  string
     */
    public static function chars($value, $double_encode = true)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8', $double_encode);
    }


    /**
     * Creates a style sheet link element.
     *
     *     echo HTML::style('media/css/screen.css');
     *
     * @param   string $file file name
     * @param   array $attributes default attributes
     * @param   mixed $protocol protocol to pass to URL::base()
     * @return  string
     * @uses    URL::base
     * @uses    Form::attributes
     */
    public static function style($file, array $attributes = null)
    {
        if (strpos($file, '//') === false) {
            // Add the base URL
            $file = HTML::link($file, false, true);
        }

        // Set the stylesheet link
        $attributes['href'] = $file;

        // Set the stylesheet rel
        $attributes['rel'] = empty($attributes['rel']) ? 'stylesheet' : $attributes['rel'];

        // Set the stylesheet type
        $attributes['type'] = 'text/css';

        return '<link' . Form::attributes($attributes) . ' />';
    }


    /**
     * Creates a script link.
     *
     *     echo HTML::script('media/js/jquery.min.js');
     *
     * @param   string $file file name
     * @param   array $attributes default attributes
     * @param   mixed $protocol protocol to pass to URL::base()
     * @return  string
     * @uses    URL::base
     * @uses    Form::attributes
     */
    public static function script($file, array $attributes = null)
    {
        if (strpos($file, '//') === false) {
            // Add the base URL
            $file = HTML::link($file, false, true);
        }

        // Set the script link
        $attributes['src'] = $file;

        // Set the script type
        $attributes['type'] = 'text/javascript';

        return '<script' . Form::attributes($attributes) . '></script>';
    }


    /**
     * Compress html page
     * @param $html
     * @param boolean $insolently
     * @return mixed
     */
    public static function compress($html, $insolently = false)
    {
        if ((int)Config::get('speed.compress') || $insolently) {
            $html = preg_replace('/[\r\n\t]+/', ' ', $html);
            $html = preg_replace('/[\s]+/', ' ', $html);
            $html = preg_replace("/\> \</", "><", $html);
            $html = preg_replace("/\<!--[^\[*?\]].*?--\>/", "", $html);
        }
        return $html;
    }

}