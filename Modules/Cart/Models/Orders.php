<?php
namespace Modules\Cart\Models;

use Core\QB\DB;
use Core\Common;

class Orders extends Common
{

    public static $table = 'orders';


    public static function getUserOrders($user_id)
    {
        $orders = DB::select(static::$table . '.*', [DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'])
            ->from(static::$table)
            ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', static::$table . '.id')
            ->where(static::$table . '.user_id', '=', $user_id)
            ->group_by(static::$table . '.id')
            ->order_by(static::$table . '.created_at', 'DESC');
        return $orders->find_all();
    }


    public static function getOrder($order_id)
    {
        $result = DB::select(
            static::$table . '.*',
            [DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'],
            [DB::expr('SUM(orders_items.count)'), 'count']
        )
            ->from(static::$table)
            ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', static::$table . '.id')
            ->where(static::$table . '.id', '=', $order_id);
        return $result->find();
    }


    public static function getOrderItems($order_id)
    {
        $items = DB::select('catalog.alias', 'catalog.id', 'catalog_i18n.name', 'catalog.image', 'orders_items.count', ['orders_items.cost', 'price'])
            ->from('orders_items')
            ->join('catalog', 'LEFT')->on('orders_items.catalog_id', '=', 'catalog.id')
            ->join('catalog_i18n')->on('catalog.id', '=', 'catalog_i18n.row_id')
            ->where('catalog_i18n.language', '=', \I18n::$default_lang)
            ->where('orders_items.order_id', '=', $order_id);
        return $items->find_all();
    }

}