<?php
    namespace Wezom\Modules\Orders\Models;

    use Core\QB\DB;
    use Core\Arr;
    use Core\Message;
    use Core\Validation\Valid;

    class Orders extends \Core\Common {

        public static $table = 'orders';
        public static $filters = [
            'uid' => [
                'action' => '=',
                'field' => 'user_id',
            ],
        ];
        public static $rules = [];


        public static function getRow($value, $field = 'id', $status = NULL) {
            $result = DB::select(
                static::$table.'.*',
                [DB::expr('SUM(orders_items.count)'), 'count'],
                [DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount']
            )
                ->from(static::$table)
                ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', static::$table.'.id')
                ->where(static::$table.'.id', '=', $value);
            return $result->find();
        }


        public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL) {
            $result = DB::select(
                static::$table.'.*',
                [DB::expr('SUM(orders_items.count)'), 'count'],
                [DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount']
            )
                ->from(static::$table)
                ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', static::$table.'.id');
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.created_at', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            $result = parent::setFilter($result);
            $result->group_by(static::$table.'.id');
            if( $sort !== NULL ) {
                if( $type !== NULL ) {
                    $result->order_by($sort, $type);
                } else {
                    $result->order_by($sort);
                }
            }
            if( $limit !== NULL ) {
                $result->limit($limit);
            }
            if( $offset !== NULL ) {
                $result->offset($offset);
            }
            return $result->find_all();
        }


        public static function countRows($status = NULL, $date_s = NULL, $date_po = NULL) {
            $result = DB::select([DB::expr('COUNT('.static::$table.'.id)'), 'count'])->from(static::$table);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.created_at', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            $result = parent::setFilter($result);
            return $result->count_all();
        }


        public static function getAmount($status = NULL, $date_s = NULL, $date_po = NULL) {
            $result = DB::select([DB::expr('SUM(orders_items.count * orders_items.cost)'), 'amount'])
                ->from(static::$table)
                ->join('orders_items')->on('orders_items.order_id', '=', 'orders.id');
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.created_at', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1);
            }
            $result = parent::setFilter($result);
            return (int) $result->find()->amount;
        }

        public static function valid($data = [])
        {
            static::$rules = [
                'name' => [
                    [
                        'error' => __('Имя не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                ],
                'last_name' => [
                    [
                        'error' => __('Фамилия не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                ],
                'email' => [
                    [
                        'error' => __('E-Mail не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                    [
                        'error' => __('E-Mail указан некорректно!'),
                        'key' => 'email',
                    ],
                ],
                'phone' => [
                    [
                        'error' => __('Укажите верный номер телефона!'),
                        'key' => 'not_empty',
                    ],
                ],
                'delivery' => [
                    [
                        'error' => __('Укажите корректный способ доставки!'),
                        'key' => 'regexp',
                        'value' => '/1|2/',
                    ],
                ],
                'payment' => [
                    [
                        'error' => __('Укажите корректный способ оплаты!'),
                        'key' => 'regexp',
                        'value' => '/1|2/',
                    ],
                ],
            ];
            return parent::valid($data);
        }

    }