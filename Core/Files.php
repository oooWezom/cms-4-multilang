<?php
namespace Core;

class Files
{

    /**
     *  Upload image
     * @param string $mainFolder - name of th block in Config/images.php
     * @param string $name - input tag 'name'
     * @return string            - filename
     */
    public static function uploadImage($mainFolder, $name = 'file')
    {
        if (!isset($_FILES[$name]) || !Arr::get($_FILES[$name], 'name')) {
            return false;
        }
        $need = Config::get('images.' . $mainFolder);
        if (!$need) {
            return false;
        }
        $ext = end(explode('.', $_FILES[$name]['name']));
        $filename = md5($_FILES[$name]['name'] . '_' . $mainFolder . time()) . '.' . $ext;
        foreach ($need as $one) {
            $image = SimpleImage::factory($_FILES[$name]['tmp_name'])->auto_orient();
            $path = HOST . HTML::media(DS . 'images' . DS . $mainFolder . DS . Arr::get($one, 'path'), false);
            Files::createFolder($path, 0777);
            $file = $path . DS . $filename;
            $new_width = Arr::get($one, 'width');
            $new_height = Arr::get($one, 'height');
            if (Arr::get($one, 'crop')) {
                if ($new_width && $new_height) {
                    $image->thumbnail($new_width, $new_height);
                } else if ($new_width) {
                    $image->thumbnail($new_width);
                } else if ($new_height) {
                    $image->thumbnail($new_height);
                }
            } else if (Arr::get($one, 'resize')) {
                if ($new_width && $new_height) {
                    $image->best_fit($new_width, $new_height);
                } else if ($new_width) {
                    $image->fit_to_width($new_width);
                } else if ($new_height) {
                    $image->fit_to_height($new_height);
                }
            }
            if (Arr::get($one, 'watermark')) {
                $watermark = SimpleImage::factory(HOST . HTML::media(str_replace(HOST, '', Config::get('images.watermark')), false));
                $watermark->fit_to_width($image->get_width() * 0.4);
                $image->overlay($watermark, 'bottom right', 1, 20, 20);
            }
            $image->save($file, Arr::get($one, 'quality', 80));
        }
        return $filename;
    }

    /**
     * Upload image from base64 string
     * @param $mainFolder
     * @param $base64string
     * @return bool|string
     * @throws \Exception
     */
    public static function uploadBase64Image($mainFolder, $base64string)
    {

        if (!$base64string) {
            return false;
        }
        $need = Config::get('images.' . $mainFolder);

        if (!$need) {
            return false;
        }
        $name = md5(time() . '_' . $mainFolder . time());
        $filename = '';
        foreach ($need as $one) {
            $image = SimpleImage::factory()->load_base64($base64string)->auto_orient();
            if (!$image) {
                return false;
            }
            $filename = $name . '.' . $image->getImageFormat();

            $path = HOST . HTML::media(DS . 'images' . DS . $mainFolder . DS . Arr::get($one, 'path'), false);
            Files::createFolder($path, 0777);
            $file = $path . DS . $filename;
            $new_width = Arr::get($one, 'width');
            $new_height = Arr::get($one, 'height');
            if (Arr::get($one, 'crop')) {
                if ($new_width && $new_height) {
                    $image->thumbnail($new_width, $new_height);
                } elseif ($new_width) {
                    $image->thumbnail($new_width);
                } elseif ($new_height) {
                    $image->thumbnail($new_height);
                }
            } elseif (Arr::get($one, 'resize')) {
                if ($new_width && $new_height) {
                    $image->best_fit($new_width, $new_height);
                } elseif ($new_width) {
                    $image->fit_to_width($new_width);
                } elseif ($new_height) {
                    $image->fit_to_height($new_height);
                }
            }
            if (Arr::get($one, 'watermark')) {
                $watermark = SimpleImage::factory(HOST . HTML::media(str_replace(HOST, '', Config::get('images.watermark')), false));
                $watermark->fit_to_width($image->get_width() * 0.4);
                $image->overlay($watermark, 'bottom right', 1, 20, 20);
            }
            $image->save($file, Arr::get($one, 'quality', 80));
        }

        return $filename;

    }

    /**
     * @param $folder - папка, в которую будет происходить сохранение файла TODO тут позже дописать в каком формате нужно передавать файл
     * @param string $name - имя файла в глобальной переменной FILES
     * @return string - name of a new file
     */
    public static function uploadFile($folder, $name = "file")
    {
        if (!Arr::get($_FILES[$name], 'name')) {
            return false;
        }
        $old_name = Arr::get($_FILES[$name], 'name');
        $old_name = explode(".", $old_name);
        if (count($old_name) < 2) {
            return false;
        }

        $file_name = md5(rand(1000000, 9999999) . time()) . "." . end($old_name);

        Files::createFolder(HOST . $folder, 0777);

        move_uploaded_file(Arr::get($_FILES[$name], 'tmp_name'), HOST . $folder . DS . $file_name);

        return $file_name;
    }


    /**
     *  Delete image
     * @param string $mainFolder - name of th block in Config/images.php
     * @param string $filename - name of the file we delete
     * @return bool
     */
    public static function deleteImage($mainFolder, $filename)
    {
        $need = Config::get('images.' . $mainFolder);
        if (!$need) {
            return false;
        }
        foreach ($need AS $one) {
            $file = HOST . HTML::media(DS . 'images' . DS . $mainFolder . DS . Arr::get($one, 'path') . DS . $filename, false);
            @unlink($file);
        }
        return true;
    }


    /**
     *  Create folder with some rights (0777 as default)
     * @param string $path - path to the dir
     * @param $mode - rights to the folder
     * @return bool
     */
    public static function createFolder($path, $mode = 0777)
    {
        if (is_dir($path)) {
            return true;
        }
        if (!mkdir($path, $mode, true)) {
            return false;
        }
        @chmod($path, $mode);
        return true;
    }

    /**
     * @param $folder - папка, в которую будет происходить сохранение файла TODO тут позже дописать в каком формате нужно передавать файл
     * @param string $name - имя файла в глобальной переменной FILES
     * @return string - name of a new file
     */
    public static function uploadFileOriginal($folder, $name = "file")
    {
        if (!Arr::get($_FILES[$name], 'name')) {
            return false;
        }

        $file_name = Arr::get($_FILES[$name], 'name');

        Files::createFolder(HOST . $folder, 0777);

        move_uploaded_file(Arr::get($_FILES[$name], 'tmp_name'), HOST . $folder . DS . $file_name);

        return $file_name;
    }

}