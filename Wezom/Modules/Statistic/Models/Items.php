<?php
    namespace Wezom\Modules\Statistic\Models;

    use Core\QB\DB;

    class Items extends \Core\Common {

        public static $table = 'catalog';
        public static $filters = [
            'artikul' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
            'parent_id' => [
                'table' => NULL,
                'action' => '=',
            ],
        ];


        public static function getRows($limit = NULL, $offset = NULL, $filter = true) {
            $result = DB::select(
                'catalog.id', 'catalog.views', 'catalog.artikul', 'catalog_i18n.name', 'catalog.cost',
                [DB::expr('COUNT(DISTINCT orders.id)'), 'orders_count'],
                [DB::expr('SUM(orders_items.count)'), 'orders_count_items'],
                [DB::expr('MAX(orders.created_at)'), 'orders_last'],
                [DB::expr('MAX(orders.id)'), 'orders_last_id']
            )
                ->from('catalog')
                ->join('catalog_i18n')->on('catalog_i18n.row_id', '=', 'catalog.id')
                ->join('orders_items', 'LEFT')->on('catalog.id', '=', 'orders_items.catalog_id')
                ->join('orders', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')
                ->where('catalog_i18n.language', '=', \I18n::$lang);;
            if( $filter ) {
                $result = static::setFilter($result);
            }
            $result->group_by('catalog.id');
            $result->order_by('orders_count', 'DESC');
            $result->order_by('orders_count_items', 'DESC');
            $result->order_by('orders_last', 'DESC');
            $result->order_by('catalog.views', 'DESC');
            $result->order_by('catalog.id', 'DESC');
            if( $limit !== NULL ) {
                $result->limit($limit);
                if( $offset !== NULL ) {
                    $result->offset($offset);
                }
            }
            return $result->find_all();
        }

    }