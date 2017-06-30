<?php

namespace Modules;

use Core\Arr;

class Ajax extends Base
{
    protected $post;
    protected $get;
    protected $files;

    public function before()
    {
        parent::before();
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
        \I18n::lang(!Arr::get($_POST, 'lang') ? \I18n::lang() : Arr::get($_POST, 'lang'));
    }

    /**
     * generate Ajax answer
     *
     * @param $data
     * @return string
     */
    public function answer($data)
    {
        echo json_encode($data);
        die;
    }


    /**
     * Generate Ajax success answer
     *
     * @param $data []
     * @return string
     */
    public function success($data = [])
    {
        if (!is_array($data)) {
            $data = [
                'response' => $data,
            ];
        }
        $data['success'] = true;
        $this->answer($data);
    }


    /**
     * generate Ajax answer with error
     *
     * @param $data []
     * @return string
     */
    public function error($data = [])
    {
        if (!is_array($data)) {
            $data = [
                'response' => $data,
            ];
        }
        $data['success'] = false;
        $this->answer($data);
    }
}