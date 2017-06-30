<?php
namespace Core;

use Core\QB\DB;

use Wezom\Modules\Contacts\Models\Contacts;
use Wezom\Modules\Orders\Models\Orders;

/**
 *  Class that helps with widgets on the site
 */
class WidgetsBackend
{

    static $_instance; // Constant that consists self class

    public $_data = []; // Array of called widgets
    public $_tree = []; // Only for catalog menus on footer and header. Minus one query

    // Instance method
    static function factory()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *  Common widget method. Uses when we have no widgets called $name
     * @param  string $viewpath [Name of template file]
     * @param  array $array [Array with data -> go to template]
     * @return string              [Widget HTML or NULL if template doesn't exist]
     */
    public function common($viewpath, $array)
    {
        if (file_exists(HOST . DS . 'Wezom' . DS . 'Views' . DS . 'Widgets' . DS . $viewpath . '.php')) {
            return View::widget($array, $viewpath);
        }
        return null;
    }


    public function Sidebar()
    {
        $result = DB::select()->from('menu')->where('status', '=', 1)->order_by('sort')->find_all();
        if (User::god()) {
            foreach ($result AS $obj) {
                $arr[$obj->id_parent][] = $obj;
            }
        } else {
            $access = User::access();
            $_arr = [];
            foreach ($result AS $obj) {
                $r = explode('/', trim($obj->link, '/'));
                if (!$obj->link || Arr::get($access, $r[0], 'no') == 'edit' || (Arr::get($access, $r[0]) == 'view' && Arr::get($r, 1) == 'index')) {
                    $_arr[$obj->id_parent][] = $obj;
                } else if (!$obj->link || Arr::get($access, str_replace('seo_', '', $r[0]), 'no') == 'edit' || (Arr::get($access, str_replace('seo_', '', $r[0])) == 'view' && Arr::get($r, 1) == 'index')) {
                    $_arr[$obj->id_parent][] = $obj;
                }
            }
            $arr = [];
            foreach ($_arr[0] AS $el) {
                if (($el->link || count(Arr::get($_arr, $el->id, [])))) {
                    $arr[0][] = $el;
                }
            }
            foreach ($_arr AS $key => $el) {
                if ($key != 0) {
                    $arr[$key] = $el;
                }
            }
        }

        $counts = [];
        $counts['contacts'] = (int)DB::select([DB::expr('COUNT(id)'), 'count'])->from('contacts')->where('status', '=', 0)->count_all();
        $counts['callbacks'] = (int)DB::select([DB::expr('COUNT(id)'), 'count'])->from('callback')->where('status', '=', 0)->count_all();
        $counts['all_emails'] = $counts['contacts'] + $counts['callbacks'];
        $counts['blog'] = (int)DB::select([DB::expr('COUNT(id)'), 'count'])->from('blog')->where('status', '=', 0)->count_all();
        $counts['blog_comments'] = (int)DB::select([DB::expr('COUNT(id)'), 'count'])->from('blog_comments')->where('status', '=', 0)->count_all();
        $counts['all_blog'] = $counts['blog'] + $counts['blog_comments'];
        $counts['orders'] = (int)Common::factory('orders')->countRows(0);
        $counts['simple_orders'] = (int)Common::factory('orders_simple')->countRows(0);
        $counts['all_orders'] = $counts['orders'] + $counts['simple_orders'];
        $counts['reviews'] = (int)DB::select([DB::expr('COUNT(id)'), 'count'])->from('reviews')->where('status', '=', 0)->count_all();

        return ['result' => $arr, 'counts' => $counts];
    }


    public function Crumbs()
    {
        $count_orders = 0;
        $result = DB::select([DB::expr('COUNT(id)'), 'count'])->from('orders')->where('status', '=', 0)->as_object()->execute()->current();
        if ($result) {
            $count_orders = $result->count;
        }
        $count_comments = 0;
        $result = DB::select([DB::expr('COUNT(id)'), 'count'])->from('catalog_comments')->where('status', '=', 0)->as_object()->execute()->current();
        if ($result) {
            $count_comments = $result->count;
        }
        return ['cc' => $count_comments, 'co' => $count_orders];
    }


    public function HeaderContacts()
    {
        $contacts = Contacts::getRows(0, null, null, 'id', 'DESC', 5);
        $cContacts = Contacts::countRows(0);
        return [
            'contacts' => $contacts,
            'cContacts' => $cContacts,
        ];
    }


    public function HeaderNew()
    {
        $count = DB::select([DB::expr('COUNT(id)'), 'count'])->from('log')->where('deleted', '=', 0)->count_all();
        $result = DB::select()->from('log')->where('deleted', '=', 0)->order_by('id', 'DESC')->limit(7)->find_all();
        return [
            'count' => $count,
            'result' => $result,
        ];
    }


    public function Index_Visitors()
    {
        if (!Config::get('main.visitor')) {
            return null;
        }
        return [];
    }


    public function Index_Readme()
    {
        if (!is_file(HOST . '/README.md')) {
            return null;
        }
        return [
            'readme' => Parsedown::instance()->text(file_get_contents(HOST . '/README.md')),
        ];
    }


    public function Index_News()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://wezom.com.ua/api/get_news');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $html = curl_exec($ch);
        curl_close($ch);
        $news = json_decode($html, true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://wezom.com.ua/api/get_blog');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $html = curl_exec($ch);
        curl_close($ch);
        $blog = json_decode($html, true);
        return [
            'news' => $news,
            'blog' => $blog,
        ];
    }


    public function Index_Log()
    {
        $log = DB::select()->from('log')->where('deleted', '=', 0)->order_by('id', 'DESC')->limit(20)->find_all();
        return [
            'log' => $log,
        ];
    }


    public function Index_Orders()
    {
        $orders = Orders::getRows(null, null, null, 'id', 'DESC', 7);
        return [
            'orders' => $orders,
            'statuses' => Config::get('order.statuses'),
        ];
    }

}