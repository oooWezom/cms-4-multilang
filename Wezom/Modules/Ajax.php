<?php
    namespace Wezom\Modules;
    
    class Ajax extends Base {

        protected $post;
        protected $get;
        protected $files;

        public function before() {
            parent::before();
            $this->post = $_POST;
            $this->get = $_GET;
            $this->files = $_FILES;
        }


        // Generate Ajax answer
        public function answer( $data ) {
            echo json_encode( $data );
            die;
        }


        // Generate Ajax success answer
        public function success( $data = []) {
            if( !is_array( $data ) ) {
                $data = [
                    'response' => $data,
                ];
            }
            $data['success'] = true;
            $this->answer( $data );
        }


        // Generate Ajax answer with error
        public function error( $data = []) {
            if( !is_array( $data ) ) {
                $data = [
                    'response' => $data,
                ];
            }
            $data['success'] = false;
            $this->answer( $data );
        }

    }