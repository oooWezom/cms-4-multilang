<?php
    namespace Wezom\Modules\Orders\Controllers;

    use Core\Common;
    use Core\CommonI18n;
    use Core\HTML;
    use Wezom\Modules\User\Models\Users;
    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\Support;
    use Core\Pager\Pager;

    use Wezom\Modules\Orders\Models\Orders AS Model;
    use Wezom\Modules\Orders\Models\OrdersItems AS Items;
    use Wezom\Modules\Catalog\Models\Items AS Catalog;

    class Orders extends \Wezom\Modules\Base {

        public $tpl_folder = 'Orders/General';
        public $statuses;
        public $st_classes;
        public $delivery;
        public $payment;
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Заказы');
            $this->_seo['title'] = __('Заказы');
            $this->setBreadcrumbs(__('Заказы'), 'wezom/'.Route::controller().'/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = Config::get('basic.limit_backend');
            $this->limit = (int) Arr::get($_GET, 'limit') > 0 ? (int) Arr::get($_GET, 'limit') : Config::get('basic.limit_backend');
            $this->offset = ($this->page - 1) * $this->limit;
            $this->delivery = Config::get('order.delivery');
            $this->payment = Config::get('order.payment');
            $this->statuses = Config::get('order.statuses');
            $this->st_classes = Config::get('order.st_classes');
        }


        function indexAction () {
            if(Arr::get($_GET, 'uid')) {
                $user = Common::factory('users')->getRow(Arr::get($_GET, 'uid'));
                if($user->id) {
                    $name = trim($user->last_name.' '.$user->name);
                    $name = $name ?: '#'.$user->id;
                    $this->setBreadcrumbs(__('Заказы пользователя').$name);
                }
            }
            $date_s = NULL; $date_po = NULL; $status = NULL;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }
            if ( isset($_GET['status']) ) { $status = Arr::get($_GET, 'status', 1); }
            $count = Model::countRows($status, $date_s, $date_po);
            $result = Model::getRows($status, $date_s, $date_po, 'id', 'DESC', $this->limit, $this->offset);
            $amount = Model::getAmount($status, $date_s, $date_po);
            $pager = Pager::factory( $this->page, $count, $this->limit )->create();

            $statuses = [
                '' => 'Все'
            ];
            $statuses += $this->statuses;

            $this->_toolbar = Widgets::get( 'Toolbar_ListOrders', ['add' => 1, 'delete' => 0]);
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                    'statuses' => $statuses,
                    'st_classes' => $this->st_classes,
                    'amount' => $amount,
                ], $this->tpl_folder.'/Index');
        }

        function editAction() {
            $result = Model::getRow(Route::param('id'));
            $cart = Items::getRows(Route::param('id'));
            $user = Users::getForOrder($result->user_id);

            if($user->id) {
                $name = trim($user->last_name.' '.$user->name);
                $name = $name ?: '#'.$user->id;
                $this->setBreadcrumbs(__('Заказы пользователя') . ' ' . $name, HTML::link('wezom/orders/index?uid='.$user->id));
            }
            $this->_seo['h1'] = __('Заказ') . ' №' . Route::param('id');
            $this->_seo['title'] = __('Заказ') . ' №' . Route::param('id');
            $this->setBreadcrumbs(__('Заказ') . ' №' . Route::param('id'), 'wezom/'.Route::controller().'/edit/'.(int) Route::param('id'));

            $this->_content = View::tpl(
                [
                    'user' => $user,
                    'obj' => $result,
                    'cart' => $cart,
                    'statuses' => $this->statuses,
                    'payment' => $this->payment,
                    'delivery' => $this->delivery,
                    'tpl_folder' => $this->tpl_folder,
                ], $this->tpl_folder.'/Inner');
        }

        function addAction(){
            $result = [];
            $post = $_POST;
            if( $_POST ) {
                if( Model::valid($post) ) {
                    $data = [
                        'user_id' => Arr::get($post, 'user_id') ?: NULL,
                        'payment' => Arr::get($post, 'payment'),
                        'delivery' => Arr::get($post, 'delivery'),
                        'name' => Arr::get($post, 'name'),
                        'last_name' => Arr::get($post, 'last_name'),
                        'middle_name' => Arr::get($post, 'middle_name'),
                        'phone' => Arr::get($post, 'phone'),
                        'email' => Arr::get($post, 'email'),
                    ];
                    $res = Model::insert($data);
                    if ($res) {
                        HTTP::redirect('wezom/orders/edit/'.$res);
                    } else {
                        HTTP::redirect('wezom/orders/add');
                    }                    
                }
                $result = Arr::to_object($post);
            }
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['noAdd' => true, 'noClose' => true]);
            $this->_seo['h1'] = __('Добавление');
            $this->_seo['title'] = __('Добавление');
            $this->setBreadcrumbs(__('Добавление'), 'wezom/'.Route::controller().'/add');
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'statuses' => $this->statuses,
                    'payment' => $this->payment,
                    'delivery' => $this->delivery,
                    'tpl_folder' => $this->tpl_folder,
                    'item' => CommonI18n::factory('users')->getRow(Arr::get($post, 'user_id')),
                ], $this->tpl_folder.'/Add');
        }

        function addPositionAction(){
            $result = [];
            if( $_POST ) {
                $post = $_POST;
                if( Items::valid($post) ) {
                    $item = Catalog::getRowSimple(Arr::get($post, 'catalog_id'));
                    if(!$item) {
                        Message::GetMessage(0, __('Нужно выбрать существующий товар для добавления!'));
                    } else {
                        $row = Items::getSame(Route::param('id'), Arr::get($post, 'catalog_id'));
                        if( $row ) {
                            $res = Items::update(['count' => $row->count + Arr::get($post, 'count')], $row->id);
                        } else {
                            $data = [
                                'order_id' => Route::param('id'),
                                'catalog_id' => Arr::get($post, 'catalog_id'),
                                'count' => Arr::get($post, 'count'),
                                'cost' => (int) $item->cost,
                            ];
                            $res = Items::insert($data);
                        }
                        if( !$res ) {
                            Message::GetMessage(0, __('Позиция не добавлена!'));
                        } else {
                            Common::factory('orders')->update(['changed' => 1], Route::param('id'));
                            Message::GetMessage(1, __('Позиция добавлена!'));

                            if(Arr::get($_POST, 'button', 'save') == 'save-close') {
                                HTTP::redirect('wezom/orders/edit/' . Route::param('id'));
                            } else if(Arr::get($_POST, 'button', 'save') == 'save-add') {
                                HTTP::redirect('wezom/orders/add_position/' . Route::param('id'));
                            } else {
                                HTTP::redirect('wezom/orders/edit/' . Route::param('id'));
                            }
                        }
                    }
                }
                $result = Arr::to_object($post);
            }
            $back_link = '/wezom/'.Route::controller().'/edit/'.(int) Route::param('id');
            $this->_toolbar = Widgets::get( 'Toolbar_Edit', ['list_link' => $back_link]);
            $this->_seo['h1'] = __('Добавление позиции в заказ') . ' №' . Route::param('id');
            $this->_seo['title'] = __('Добавление позиции в заказ') . ' №' . Route::param('id');
            $this->setBreadcrumbs(__('Заказ') . ' №' . (int) Route::param('id'), $back_link);
            $this->setBreadcrumbs(__('Добавление позиции в заказ') . ' №' . Route::param('id'), 'wezom/'.Route::controller().'/add_position/'.(int) Route::param('id'));
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'statuses' => $this->statuses,
                    'payment' => $this->payment,
                    'delivery' => $this->delivery,
                    'tpl_folder' => $this->tpl_folder,
                    'tree' => Support::getSelectOptions('Catalog/Items/Select', 'catalog_tree', $result->parent_id),
                ], $this->tpl_folder.'/AddPosition');
        }

        function deleteAction() {
            $id = (int) Route::param('id');
            $page = Model::getRow($id);
            if(!$page) {
                Message::GetMessage(0, __('Данные не существуют!'));
                HTTP::redirect('wezom/'.Route::controller().'/index');
            }
            Model::delete($id);
            Message::GetMessage(1, __('Данные удалены!'));
            HTTP::redirect('wezom/'.Route::controller().'/index');
        }


        function printAction() {
            $result = Model::getRow(Route::param('id'));
            $cart = Items::getRows(Route::param('id'));
            echo View::tpl( [
                'order' => $result,
                'list' => $cart,
                'payment' => Config::get('order.payment'),
                'delivery' => Config::get('order.delivery'),
                'statuses' => Config::get('order.statuses'),
            ], $this->tpl_folder.'/Print' );
            die;
        }

    }