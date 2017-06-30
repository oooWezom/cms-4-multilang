<?php
    namespace Wezom\Modules\Ajax\Controllers;

    use Core\Arr;
    use Core\HTML;
    use Core\QB\DB;

    class Users extends \Wezom\Modules\Ajax {

        /**
         * Get one item information
         * $this->post['id'] => item ID
         */
        public function getItemAction() {
            $id = Arr::get($this->post, 'id', 0);
            $item = DB::select()->from('users')->where('id', '=', $id)->find();
            die(json_encode([
                'success' => true,
                'item' => [
                    'id' => $item->id,
//                    'uid' => $item->id,
                    'name' => $item->last_name.' '.$item->name.' '.$item->middle_name,
                    'email' => $item->email,
                    'phone' => $item->phone ?: NULL,
                ],
            ]));
        }


        /**
         * Get items list by some parameters
         * $this->post['id'] => current item ID
         * $this->post['parent_id'] => search item group ID
         * $this->post['search'] => search item artikul or name (or part of it)
         */
        public function searchItemsAction() {
            $search = Arr::get($this->post, 'search');
            $limit = (int) Arr::get($this->post, 'limit', 1);
            $page = (int) Arr::get($this->post, 'page', 1);
            $offset = ($page - 1) * $limit;
            if( !trim($search) ) {
                $this->error([
                    'items' => [],
                ]);
            }

            $result = DB::select()
                ->from('users')
                ->where('role_id', '=', 1)
                ->and_where_open()
                    ->or_where('id', '=', $search)
                    ->or_where(DB::expr('CONCAT(last_name, " ", name, " ", middle_name)'), 'LIKE', '%'.$search.'%')
                ->and_where_close()
                ->order_by('id', 'DESC')
                ->limit($limit)
                ->offset($offset)
                ->find_all();

            $count = DB::select([DB::expr('COUNT(DISTINCT users.id)'), 'count'])
                ->from('users')
                ->where('role_id', '=', 1)
                ->and_where_open()
                    ->or_where('id', '=', $search)
                    ->or_where(DB::expr('CONCAT(last_name, " ", name, " ", middle_name)'), 'LIKE', '%'.$search.'%')
                ->and_where_close()
                ->count_all();

            $items = [];
            foreach( $result AS $obj ) {
                $items[] = [
                    'id' => $obj->id,
                    'name' => $obj->last_name.' '.$obj->name.' '.$obj->middle_name,
                    'email' => $obj->email,
                ];
            }
            $this->success([
                'count' => $count,
                'items' => $items,
            ]);
        }

    }