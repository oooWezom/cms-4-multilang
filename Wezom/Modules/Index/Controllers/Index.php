<?php
    namespace Wezom\Modules\Index\Controllers;

    use Wezom\Modules\Catalog\Models\Items;
    use Wezom\Modules\Catalog\Models\Comments;
    use Wezom\Modules\Orders\Models\Orders;
    use Wezom\Modules\User\Models\Users;
    use Core\View;

    class Index extends \Wezom\Modules\Base {

        function indexAction () {
            $this->_seo['h1'] = __('Панель управления');
            $this->_seo['title'] = __('Панель управления');

            $count_catalog = Items::countRows();
            $count_orders = Orders::countRows();
            $count_comments = Comments::countRows();
            $count_users = Users::countRows();

            $this->_content = View::tpl( [
                'count_catalog' => $count_catalog,
                'count_orders' => $count_orders,
                'count_comments' => $count_comments,
                'count_users' => $count_users,
            ], 'Index/Main');
        }

    }