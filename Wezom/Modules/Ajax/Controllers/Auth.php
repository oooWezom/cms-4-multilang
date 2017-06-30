<?php
    namespace Wezom\Modules\Ajax\Controllers;

    use Core\Arr;
    use Core\User;

    class Auth extends \Wezom\Modules\Ajax {
        
        /**
         * Authorization to admin panel
         * $this->post['login'] => user login
         * $this->post['password'] => user password
         * $this->post['remember'] => does user want to remember his password
         */
        public function loginAction() {
            $login = Arr::get( $this->post, 'login' );
            $password = Arr::get( $this->post, 'password' );
            $remember = Arr::get( $this->post, 'remember' );
            $u = User::factory();
            $user = $u->get_user_if_isset( $login, $password, 1 );
            if( !$user OR $user->role == 'user' ) {
                $this->error([
                    'msg' => __('Логин или пароль введены неверно!'),
                ]);
            }
            $u->auth( $user, $remember );
            $this->success();
        }

    }