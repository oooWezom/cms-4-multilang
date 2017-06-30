<?php
    namespace Wezom\Modules\Statistic\Models;

    use Core\HTML;
    use Core\QB\DB;

    class Users extends \Core\Common {

        public static $table = 'users';
        public static $filters = [
            'name' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
            'last_name' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
            'middle_name' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
            'email' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
            'phone' => [
                'table' => NULL,
                'action' => 'LIKE',
            ],
        ];


        public static function getRows($status = NULL, $limit = NULL, $offset = NULL, $filter = true) {
            $result = DB::select(
                'users.*',
                [DB::expr('"0"'), 'orders_amount'],
                [DB::expr('"0"'), 'orders_count'],
                [DB::expr('""'), 'orders_last'],
                [DB::expr('""'), 'orders_last_id']
            )
                ->from(static::$table)
                ->where('role_id', '=', 1);
            if( $status !== NULL ) {
                $result->where('status', '=', $status);
            }
            if( $filter ) {
                $result = static::setFilter($result);
            }
            $result->group_by('users.id');
            $result->order_by('users.last_login', 'DESC');
            $result->order_by('users.logins', 'DESC');
            $result->order_by('users.created_at', 'DESC');
            if( $limit !== NULL ) {
                $result->limit($limit);
                if( $offset !== NULL ) {
                    $result->offset($offset);
                }
            }
            $result = $result->find_all();
            $res = [];
            foreach($result AS $key => $obj) {
                $stat = static::getInfo($obj->id);
                $res[$key] = $obj;
                $res[$key]->orders_amount = $stat->orders_amount;
                $res[$key]->orders_count = $stat->orders_count;
                $res[$key]->orders_last = $stat->orders_last;
                $res[$key]->orders_last_id = $stat->orders_last_id;
            }
            return $res;
        }

        public static function getInfo($user_id, $compile = false) {
            $result = DB::select(
                [DB::expr('SUM(orders_items.count * orders_items.cost)'), 'order_amount'],
                'orders.created_at',
                'orders.id'
            )
                ->from('orders')
                ->join('orders_items', 'LEFT')->on('orders.id', '=', 'orders_items.order_id')
                ->where('orders.user_id', '=', $user_id)
                ->group_by('orders.id')
                ->find_all();
            $obj = new \stdClass();
            $obj->orders_amount = 0;
            $obj->orders_count = 0;
            $obj->orders_last = NULL;
            $obj->orders_last_id = NULL;
            foreach($result AS $key => $value) {
                $obj->orders_amount += $value->order_amount;
                $obj->orders_count += 1;
                if($value->id > $obj->orders_last_id || $obj->orders_last_id === NULL) {
                    $obj->orders_last_id = $value->id;
                    $obj->orders_last = $value->created_at;
                }
            }
            return $obj;
        }

    }