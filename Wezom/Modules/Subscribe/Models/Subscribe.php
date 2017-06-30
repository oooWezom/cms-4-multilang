<?php
    namespace Wezom\Modules\Subscribe\Models;

    use Core\Arr;
    use Core\Message;
    use Core\QB\DB;

    class Subscribe extends \Core\Common {

        public static $table = 'subscribe_mails';
        public static $rules = [];

        public static function valid($post, $emails = []) {
            static::$rules = [
                'subject' => [
                    [
                        'error' => __('Заголовок письма не может быть пустой!'),
                        'key' => 'not_empty',
                    ],
                ],
                'text' => [
                    [
                        'error' => __('Тело письмо не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                ],
            ];
            if( empty( $emails ) ) {
                Message::GetMessage(0, __('Список выбраных E-Mail для рассылки пуст!'));
                return FALSE;
            }
            return parent::valid($post);
        }

        public static function getRows($status = NULL, $date_s = NULL, $date_po = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL) {
            $result = DB::select()->from(static::$table);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $date_s ) {
                $result->where(static::$table . '.created_at', '>=', $date_s);
            }
            if( $date_po ) {
                $result->where(static::$table.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1);
            }
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
            return $result->count_all();
        }

    }