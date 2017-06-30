<?php
    namespace Wezom\Modules\Crop\Controllers;

    use Core\Config;
    use Core\Encrypt;
    use Core\HTML;
    use Core\Image\Image;
    use Core\Route;
    use Core\SimpleImage;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\Pager\Pager;

    use Wezom\Modules\Content\Models\Articles AS Model;

    class Crop extends \Wezom\Modules\Base {

        public $tpl_folder = 'Crop';

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Обрезать фото');
            $this->_seo['title'] = __('Обрезать фото');
        }

        function indexAction () {
            $str = Encrypt::instance()->decode($_GET['hash']);
            $arr = json_decode($str, true);
            if(count($arr) != 4) {
                return Config::error();
            }
            $_images = Config::get('images.'.$arr[0]);
            $images = [];
            $current = [];
            foreach($_images AS $key => $value) {
                if($value['crop'] && $value['path'] != 'original' && $value['width'] && $value['height']) {
                    $images[] = $value;
                    if($value['path'] == $arr[3]) {
                        $current = $value;
                    }
                }
            }
            if(!count($images) || !$current) {
                return Config::error();
            }

            $this->setBreadcrumbs(__('Вернуться'), $arr[2]);
            $this->setBreadcrumbs(__('Обрезать фото'));

            $filename = HTML::media('images'.DS.$arr[0].DS.'original'.DS.$arr[1], false);
            if(!is_file(HOST.$filename)) {
                die('3');
                return Config::error();
            }

            $crop = HTML::media('images'.DS.$arr[0].DS.$arr[3].DS.$arr[1], false);
            if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $json = Arr::get($_POST, 'json', '[]');
                $data = json_decode($json, true);
                $image = SimpleImage::factory(HOST.$filename);
                $image->thumb($data['cropBox']['width'], $data['cropBox']['height'], $data['cropBox']['x'], $data['cropBox']['y']);
                $image->best_fit($current['width'], $current['height']);
                if( Arr::get($current, 'watermark') ) {
                    $watermark = SimpleImage::factory(HOST.HTML::media(str_replace(HOST, '', Config::get('images.watermark')), false));
                    $watermark->fit_to_width($image->get_width() * 0.4);
                    $image->overlay($watermark, 'bottom right', 1, 20, 20);
                }
                $image->save(HOST.$crop, Arr::get($data, 'quality', 80));
                die(json_encode([
                    'success' => true,
                ]));
            }

            $this->_content = View::tpl(
                [
                    'arr' => $arr,
                    'images' => $images,
                    'json' => json_encode($current),
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'pageName' => $this->_seo['h1'],
                    'image' => $filename,
                    'current' => $current,
                ], $this->tpl_folder.DS.'Index');
        }

    }