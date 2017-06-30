<?php
namespace Minify;

    class Core {

        protected $type;
        protected $file;
        protected $filepath;
        protected $input = '';
        protected $inputLength = 0;
		protected $speedMinify = false;
		protected $speedMinifyAlways = false;
		protected $host;
        protected $root = '/Media/cache';

        public function __construct($type) {
			$this->host = $_SERVER['DOCUMENT_ROOT'];
            $this->type = $type;
			if (\Core\Config::get('speed.minify') !== null) {
				$this->speedMinify = \Core\Config::get('speed.minify');
				
				if (!\Core\Config::get('speed.minify')) {
					Core::clearCache();
				}
			}
        }
		
		public static function factory($type) {
			$class = ucfirst($type);
			require_once HOST . '/Plugins/Minify/' . ucfirst($type) . '.php';
			$class = ucfirst($type);
            if($type == 'css') {
                return new Css($type);
            } else {
                return new Js($type);
            }
		}

        public function minify($files, $build = true) {
            if ($this->speedMinify) {
                $name = 'minify_' . substr(md5(json_encode($files)),0,16);
                $outfile = $this->root . '/' . $name . '.' . strtolower($this->type);
				if (!is_dir($this->host.$this->root)) {
					@mkdir($this->host.$this->root, 0777);
				}
                if (!is_file($this->host . $outfile) || $this->speedMinifyAlways) {
                    if (!is_array($files))
                        $files = array($files);
					
                    $files = array_unique($files);

                    $output = '';
                    foreach ($files as $file) {
                        if (stripos($file, '/') === 0) {
                            $this->filepath = $this->host . '/' . mb_substr($file, 1);
                            //$this->file = $file;
                        } else {
                            //$this->file = $this->host . '/' . $file;
                            $this->filepath = $this->host . '/' . $file;
                        }

                        if (strpos($this->filepath, '?') != 0)
                            $this->filepath = substr($this->filepath, 0, strpos($this->filepath, '?'));

                        if (is_file($this->filepath)) {
							$this->set(file_get_contents($this->filepath));
							$output .= "/*" . $file . "*/\r\n" . $this->min() . "\r\n";
                        }
                    }


                    $f = fopen($this->host . '/' . trim($outfile, '/'), 'w');
                    if ($f) {
                        fwrite($f, $output);
                        fclose($f);
                    }
                }
				if ($build) {
					return array($outfile);
				} else {
					return file_get_contents($this->host . '/' . trim($outfile, '/'));
				}
            } else {
				if ($build) {
					return $files;
				} else {
					$output = '';
					foreach ($files as $file) {
                        if (stripos($file, '/') === 0) {
                            $this->filepath = $this->host . '/' . mb_substr($file, 1);
                            //$this->file = mb_substr($file, 1);
                        } else {
                            //$this->file = $this->host . '/' . $file;
                            $this->filepath = $this->host . '/' . $file;
                        }

                        if (strpos($this->filepath, '?') != 0)
                            $this->filepath = substr($this->filepath, 0, strpos($this->filepath, '?'));

                        if (is_file($this->filepath)) {
							$this->set(file_get_contents($this->filepath));
							$output .= "/*" . $file . "*/\r\n" . $this->input . "\r\n";
                        }
                    }
					return $output;
				}
			}
        }

        public function set($input) {
            $this->input = str_replace("\r\n", "\n", $input);
            $this->inputLength = strlen($this->input);
            return $this;
        }

        public function min() {
            return $this->input;
        }

        public static function clearCache() {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/Media/cache/';
            array_map("unlink", glob($path . "minify_*.css"));
            array_map("unlink", glob($path . "minify_*.js"));
        }

}
?>