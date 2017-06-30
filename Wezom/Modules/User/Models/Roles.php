<?php
    namespace Wezom\Modules\User\Models;

    use Core\Common;
    use Core\QB\DB;

    class Roles extends \Core\Common {

        public static $table = 'users_roles';

        public static $rules = [];


        public static function getBackendUsersRoles() {
            $result = DB::select()
                ->from(static::$table)
                ->where('alias', '=', 'admin')
                ->order_by('name', 'ASC')
                ->find_all();
            return $result;
        }


        public static function setAccess($access, $role_id) {
            Common::factory('access')->delete($role_id, 'role_id');
            foreach( $access AS $controller => $type ) {
                $data = [];
                $data['role_id'] = $role_id;
                $data['controller'] = $controller;
                if( $type == 'view' ) {
                    $data['view'] = 1;
                    $data['edit'] = 0;
                } else if( $type == 'edit' ) {
                    $data['view'] = 1;
                    $data['edit'] = 1;
                } else {
                    $data['view'] = 0;
                    $data['edit'] = 0;
                }
                Common::factory('access')->insert($data);
            }
        }


        public static function getAccess($role_id) {
            $result = DB::select()->from('access')->where('role_id', '=', $role_id)->find_all();
            $arr = [];
            foreach( $result AS $obj ) {
                if( $obj->edit && $obj->view ) {
                    $arr[$obj->controller] = 'edit';
                } else if( $obj->view ) {
                    $arr[$obj->controller] = 'view';
                } else {
                    $arr[$obj->controller] = 'no';
                }
            }
            return $arr;
        }

        public static function valid($data = [])
        {
            static::$rules = [
                'name' => [
                    [
                        'error' => __('Поле "Название" не может быть пустым!'),
                        'key' => 'not_empty',
                    ],
                ],
            ];
            return parent::valid($data);
        }

    }