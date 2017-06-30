<?php
namespace Core;

use Modules\Catalog\Models\Groups;
use Modules\Catalog\Models\Items;
use Modules\News\Models\News;
use Core\QB\DB;
use Modules\Cart\Models\Cart;
use Modules\Catalog\Models\Filter;

/**
 *  Class that helps with widgets on the site
 */
class Widgets
{

    static $_instance; // Constant that consists self class

    public $_data = []; // Array of called widgets
    public $_contentMenu = []; // Array of called widgets
    public $_tree = []; // Only for catalog menus on footer and header. Minus one query

    // Instance method
    static function factory()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *  Get widget
     * @param  string $name [Name of template file]
     * @param  array $array [Array with data -> go to template]
     * @return string        [Widget HTML]
     */
    public static function get($name, $array = [], $save = true, $cache = false)
    {
        $arr = explode('_', $name);
        $viewpath = implode('/', $arr);

        if (APPLICATION == 'backend' && !Config::get('error')) {
            $w = WidgetsBackend::factory();
        } else {
            $w = Widgets::factory();
        }

        $_cache = Cache::instance();
        if ($cache) {
            if (!$_cache->get($name)) {
                $data = NULL;
                if ($save && isset($w->_data[$name])) {
                    $data = $w->_data[$name];
                } else {
                    if ($save && isset($w->_data[$name])) {
                        $data = $w->_data[$name];
                    } else if (method_exists($w, $name)) {
                        $result = $w->$name($array);
                        if ($result !== null && $result !== false) {
                            $array = array_merge($array, $result);
                            $data = View::widget($array, $viewpath);
                        } else {
                            $data = null;
                        }
                    } else {
                        $data = $w->common($viewpath, $array);
                    }
                }
                $_cache->set($name, HTML::compress($data, true));
                return $w->_data[$name] = $data;
            } else {
                return $_cache->get($name);
            }
        }
        if ($_cache->get($name)) {
            $_cache->delete($name);
        }
        if ($save && isset($w->_data[$name])) {
            return $w->_data[$name];
        }
        if (method_exists($w, $name)) {
            $result = $w->$name($array);
            if ($result !== null && $result !== false) {
                if (is_array($result)) {
                    $array = array_merge($array, $result);
                }
                return $w->_data[$name] = View::widget($array, $viewpath);
            } else {
                return $w->_data[$name] = null;
            }
        }
        return $w->_data[$name] = $w->common($viewpath, $array);
    }

    /**
     *  Common widget method. Uses when we have no widgets called $name
     * @param  string $viewpath [Name of template file]
     * @param  array $array [Array with data -> go to template]
     * @return string            [Widget HTML or NULL if template doesn't exist]
     */
    public function common($viewpath, $array)
    {
        if (file_exists(HOST . '/Views/Widgets/' . $viewpath . '.php')) {
            return View::widget($array, $viewpath);
        }
        return null;
    }

    public function HiddenData()
    {
        $cart = Cart::factory()->get_list_for_basket();
        return ['cart' => $cart];
    }

    public function Item_Comments()
    {
        $id = Route::param('id');
        if (!$id) {
            return $this->_data['comments'] = '';
        }
        $result = DB::select()->from('catalog_comments')->where('status', '=', 1)->where('catalog_id', '=', $id)->order_by('date', 'DESC')->find_all();
        return ['result' => $result];
    }

    public function CatalogFilter()
    {
        $array = Filter::getClickableFilterElements();
        $brands = Filter::getBrandsWidget();
        $models = [];
        if (Arr::get(Config::get('filter_array'), 'brand')) {
            $models = Filter::getModelsWidget();
        }
        $specifications = Filter::getSpecificationsWidget();
        return [
            'brands' => $brands,
            'models' => $models,
            'specifications' => $specifications,
            'filter' => $array['filter'],
            'min' => $array['min'],
            'max' => $array['max'],
			'filter_list' => Widgets::get('Catalog_FilterList'),
        ];
    }

    public function Item_InfoItemPage()
    {
        $pages = [5, 6, 7, 8];
        $result = DB::select('content_i18n.*')
            ->from('content')
            ->join('content_i18n')->on('content.id','=','content_i18n.row_id')
            ->where('content.status', '=', 1)
            ->where('content_i18n.row_id', 'IN', $pages)
            ->where('content_i18n.language', '=', \I18n::$lang)
            ->order_by('content.sort')
            ->find_all();
        return ['result' => $result];
    }

    public function ItemsViewed()
    {
        $ids = Items::getViewedIDs();
        if (!$ids) {
            return $this->_data['itemsViewed'] = '';
        }
        $result = DB::select('catalog.*', 'catalog_i18n.name')
            ->from('catalog')
            ->join('catalog_i18n')->on('catalog.id','=','catalog_i18n.row_id')
            ->where('catalog.id', 'IN', $ids)
            ->where('catalog.status', '=', 1)
            ->where('catalog_i18n.language', '=', \I18n::$lang)
            ->limit(5)
            ->find_all();
        if (!sizeof($result)) {
            return false;
        }
        return ['result' => $result];
    }

    public function Index_ItemsPopular()
    {
        $result = DB::select('catalog.*', 'catalog_i18n.name')
            ->from('catalog')
            ->join('catalog_i18n')->on('catalog.id','=','catalog_i18n.row_id')
            ->where('catalog.top', '=', 1)
            ->where('catalog.status', '=', 1)
            ->where('catalog_i18n.language', '=', \I18n::$lang)
            ->order_by(DB::expr('rand()'))
            ->limit(5)
            ->find_all();
        if (!sizeof($result)) {
            return false;
        }
        return ['result' => $result];
    }

    public function Index_ItemsNew()
    {
        $result = DB::select('catalog.*', 'catalog_i18n.name')
            ->from('catalog')
            ->join('catalog_i18n')->on('catalog.id','=','catalog_i18n.row_id')
            ->where('catalog.new', '=', 1)
            ->where('catalog.status', '=', 1)
            ->where('catalog_i18n.language', '=', \I18n::$lang)
            ->order_by(DB::expr('rand()'))
            ->limit(5)
            ->find_all();
        if (!sizeof($result)) {
            return false;
        }
        return ['result' => $result];
    }

    public function Item_ItemsSame()
    {
        
        $result = DB::select('catalog.*', 'catalog_i18n.name')
            ->from('catalog')
            ->join('catalog_i18n')
                ->on('catalog_i18n.row_id', '=', 'catalog.id')
            ->join('catalog_related')
                ->on('catalog_related.with_id', '=', 'catalog.id')
            ->where('catalog_related.who_id', '=', Route::param('id'))
            ->where('catalog_i18n.language', '=', \I18n::$lang)
            ->find_all();
        
       
        if (!sizeof($result))   {
            $result = DB::select('catalog.*', 'catalog_i18n.name')
                ->from('catalog')
                ->join('catalog_i18n')->on('catalog.id','=','catalog_i18n.row_id')
                ->where('catalog.parent_id', '=', Route::param('group'))
                ->where('catalog.status', '=', 1)
                ->where('catalog.id', '!=', Route::param('id'))
                ->where('catalog_i18n.language', '=', \I18n::$lang)
                ->order_by(DB::expr('rand()'))
                ->limit(5)
                ->find_all();
        }
        
        if (!sizeof($result)) {
            return false;
        }
        $alias = Groups::getRow(Route::param('group'))->alias;
        return ['result' => $result, 'alias' => $alias];
    }

    public function Groups_CatalogMenuLeft()
    {
        if (!empty($this->_tree)) {
            $result = $this->_tree;
        } else {
            $result = Groups::getRows(1, 'sort');
            $this->_tree = $result;
        }
        $arr = [];
        foreach ($result as $obj) {
            $arr[$obj->parent_id][] = $obj;
        }
        $rootParent = Support::getRootParent($result, Route::param('group'));
        return ['result' => $arr, 'root' => $rootParent];
    }

    public function CatalogMenuTop()
    {
        if (!empty($this->_tree)) {
            $result = $this->_tree;
        } else {
            $result = Groups::getRows(1, 'sort');
            $this->_tree = $result;
        }
        $arr = [];
        foreach ($result as $obj) {
            $arr[$obj->parent_id][] = $obj;
        }
        return ['result' => $arr];
    }

    public function CatalogMenuBottom()
    {
        if (!empty($this->_tree)) {
            $result = $this->_tree;
        } else {
            $result = Groups::getRows(1, 'sort');
            $this->_tree = $result;
        }
        $arr = [];
        foreach ($result as $obj) {
            $arr[$obj->parent_id][] = $obj;
        }
        return ['result' => $arr];
    }

    public function Index_Slider()
    {
        $result = CommonI18n::factory('slider')->getRows(1, 'sort');
        if (!sizeof($result)) {
            return false;
        }
        return ['result' => $result];
    }

    public function Index_Banners()
    {
        $result = Common::factory('banners')->getRows(1, DB::expr('rand()'), NULL, 3);
        if (!sizeof($result)) {
            return false;
        }
        return ['result' => $result];
    }

    public function News()
    {
        $result = News::getRows(1, 'date', 'DESC', 1);
        if (!sizeof($result)) {
            return false;
        }
        return ['obj' => $result[0]];
    }

    public function Articles()
    {
        $result = CommonI18n::factory('articles')->getRows(1, 'id', 'DESC', Config::get('basic.limit_articles_main_page'));
        if (!sizeof($result)) {
            return false;
        }
        return ['result' => $result];
    }

    public function Info()
    {
        $result = DB::select('content_i18n.*')
            ->from('content')
            ->join('content_i18n')->on('content.id','=','content_i18n.row_id')
            ->where('content.status', '=', 1)
            ->where('content_i18n.row_id', 'IN', [5, 6, 7, 8])
            ->where('content_i18n.language', '=', \I18n::$lang)
            ->order_by('content.sort')
            ->find_all();
        if (!sizeof($result)) {
            return false;
        }
        return ['result' => $result];
    }

    public function HeaderCart()
    {
        if (!$this->_contentMenu) {
            $this->_contentMenu = CommonI18n::factory('sitemenu')->getRows(1, 'sort');
        }
        return ['contentMenu' => $this->_contentMenu];
    }

    public function Footer()
    {
        if (!$this->_contentMenu) {
            $this->_contentMenu = CommonI18n::factory('sitemenu')->getRows(1, 'sort');
        }
        $array['contentMenu'] = $this->_contentMenu;
        return $array;
    }

    public function Header()
    {
        if (!$this->_contentMenu) {
            $this->_contentMenu = CommonI18n::factory('sitemenu')->getRows(1, 'sort');
        }
        $array['contentMenu'] = $this->_contentMenu;
        $array['user'] = User::info();
        $array['countItemsInTheCart'] = Cart::factory()->_count_goods;
        return $array;
    }

    public function Head()
    {
        $styles = [
            HTML::media('css/plugin.css', false),
            HTML::media('css/style.css', false),
//                HTML::media('css/programmer/magnific.css'),
            HTML::media('css/programmer/fpopup.css', false),
            HTML::media('css/programmer/my.css', false),
            HTML::media('css/responsive.css', false),
            HTML::media('css/wPreloader.css', false),
        ];
        $scripts = [
            HTML::media('js/modernizr.js', false),
            HTML::media('js/jquery-1.11.0.min.js', false),
            HTML::media('js/basket.js', false),
            HTML::media('js/plugins.js', false),
            HTML::media('js/init.js', false),
            HTML::media('js/wPreloader.js', false),
            HTML::media('js/programmer/my.js', false),
        ];
        $scripts_no_minify = [
            HTML::media('js/programmer/ulogin.js', false),
        ];
        return ['scripts' => $scripts, 'styles' => $styles, 'scripts_no_minify' => $scripts_no_minify];
    }

    public function LanguageSwitcher()
    {
        $languages = Config::get('languages');
        $current = \I18n::lang();

        return ['languages' => $languages, 'current' => $current];
    }
}
