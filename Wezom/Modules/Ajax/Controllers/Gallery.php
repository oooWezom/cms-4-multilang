<?php
    namespace Wezom\Modules\Ajax\Controllers;

    use Core\Arr;
    use Core\HTML;
    use Core\View;
    use Core\Config;
    use Core\Files;
    use Wezom\Modules\Multimedia\Models\GalleryImages;

    class Gallery extends \Wezom\Modules\Ajax {

        /**
         * Upload photo
         * $this->files['file'] => incoming image
         */
        public function uploadPhotoAction() {
            if (empty($this->files['file'])) die('No File!');
            $arr = explode('/', Arr::get($_SERVER, 'HTTP_REFERER'));
            $parent_id = (int) end($arr);
            $headers = HTML::emu_getallheaders();
            if(array_key_exists('Upload-Filename', $headers)) {
                $name = $headers['Upload-Filename'];
            } else {
                $name = $this->files['file']['name'];
            }
            $name = explode('.', $name);
            $ext = strtolower(end($name));
            if (!in_array($ext, Config::get('images.types'))) die('Not image!');
            $filename = Files::uploadImage(GalleryImages::$image);
            GalleryImages::insert([
                'gallery_id' => $parent_id,
                'image' => $filename,
                'sort' => GalleryImages::getSortForThisParent($parent_id),
            ]);
            $this->success([
                'confirm' => true,
            ]);
        }


        /**
         * Get album photos list
         */
        public function getUploadedPhotosAction() {
            $arr = explode('/', Arr::get($_SERVER, 'HTTP_REFERER'));
            $parent_id = (int) end($arr);
            if( !$parent_id ) die('Error!');
            $images = GalleryImages::getRows($parent_id);
            $show_images = View::tpl(['images' => $images], 'Multimedia/Gallery/UploadedImages');
            $this->success([
                'images' => $show_images,
                'count' => count($images),
            ]);
        }


        /**
         * Delete uploaded photo from album
         * $this->post['id'] => ID from gallery images table in DB
         */
        public function deleteUploadedPhotosAction() {
            $id = (int) Arr::get($this->post, 'id');
            if (!$id) die('Error!');
            $image = GalleryImages::getRow($id)->image;
            GalleryImages::deleteImage($image);
            GalleryImages::delete($id);
            $this->success();
        }


        /**
         * Switch flag between 0 & 1 (field "main" in this case)
         * $this->post['id'] => photo ID to work with
         */
        public function setPhotoAsMainAction() {
            $id = (int) Arr::get($this->post, 'id');
            if (!$id) die('Error!');
            $obj = GalleryImages::getRow($id);
            if( $obj->main ) {
                $main = 0;
            } else {
                $main = 1;
            }
            GalleryImages::update(['main' => $main], $id);
            $this->success();
        }


        /**
         * Sort photos in current album
         * $this->post['order'] => array with photos IDs in right order
         */
        public function sortPhotosAction() {
            $order = Arr::get($this->post, 'order');
            if (!is_array($order)) die('Error!');
            $updated = 0;
            foreach($order as $key => $value) {
                $value = (int) $value;
                $order = $key + 1;
                GalleryImages::update(['sort' => $order], $value);
                $updated++;
            }
            $this->success([
                'updated' => $updated,
            ]);
        }

    }