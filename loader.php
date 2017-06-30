<?php
    Loader::instance()->register();
    // Add system folders, controllers, models and plugins
    Loader::instance()->addNamespace('Core', 'Core');
    Loader::instance()->addNamespace('Forms', 'Core/Forms');
    Loader::instance()->addNamespace('Plugins', 'Plugins');
    Loader::instance()->addNamespace('Modules', 'Modules');
    Loader::instance()->addNamespace('Wezom\\Modules', 'Wezom/Modules');
    Loader::instance()->addNamespace('Minify', 'Plugins/Minify');
    Loader::instance()->addNamespace('Mailer', 'Plugins/phpMailer');
    Loader::instance()->simple('Plugins/PHPExcel');
    if(MULTI_LANGUAGE OR APPLICATION == 'backend') {
        Loader::instance()->simple('Plugins/I18n/I18n.php');
    }
    Loader::instance()->simple('Plugins/Profiler/Profiler.php');
    foreach(\Core\Config::get('modules.frontend') AS $moduleName) {
        Loader::instance()->addNamespace('Module\\'.ucfirst($moduleName).'\\Controllers', 'Modules/'.ucfirst($moduleName).'/Controllers');
        Loader::instance()->addNamespace('Module\\'.ucfirst($moduleName).'\\Models', 'Modules/'.ucfirst($moduleName).'/Models');
    }
    foreach(\Core\Config::get('modules.backend') AS $moduleName) {
        Loader::instance()->addNamespace('Wezom\\Modules\\'.ucfirst($moduleName).'\\Controllers', 'Wezom/Modules/'.ucfirst($moduleName).'/Controllers');
        Loader::instance()->addNamespace('Wezom\\Modules\\'.ucfirst($moduleName).'\\Models', 'Wezom/Modules/'.ucfirst($moduleName).'/Models');
    }


    /**
     * Class Loader
     * Autoload classes in the project
     */
    class Loader
    {
        /**
         * An associative array where the key is a namespace prefix and the value
         * is an array of base directories for classes in that namespace.
         * @var array
         */
        protected $prefixes = array();

        /**
         * List of simple folders and files to include
         * @var array
         */
        protected $simple = array();

        /**
         * Instance of Loader class
         * @var
         */
        static $instance;

        /**
         * Get an instance of Loader class
         * @return Loader
         */
        static function instance() {
            if(static::$instance instanceof Loader) {
                return static::$instance;
            }
            return static::$instance = new Loader();
        }

        /**
         * Register loader with SPL autoloader stack.
         * @return void
         */
        public function register() {
            spl_autoload_register(array($this, 'loadClass'));
        }

        /**
         * Adds a base directory for a namespace prefix.
         *
         * @param string $prefix The namespace prefix.
         * @param string $base_dir A base directory for class files in the
         * namespace.
         * @param bool $prepend If true, prepend the base directory to the stack
         * instead of appending it; this causes it to be searched first rather
         * than last.
         * @return void
         */
        public function addNamespace($prefix, $base_dir, $prepend = false) {
            // normalize namespace prefix
            $prefix = trim($prefix, '\\') . '\\';
            // normalize the base directory with a trailing separator
            $base_dir = rtrim($base_dir, DS) . '/';
            // initialize the namespace prefix array
            if (isset($this->prefixes[$prefix]) === false) {
                $this->prefixes[$prefix] = array();
            }
            // retain the base directory for the namespace prefix
            if ($prepend) {
                array_unshift($this->prefixes[$prefix], $base_dir);
            } else {
                array_push($this->prefixes[$prefix], $base_dir);
            }
        }

        /**
         * Loads the class file for a given class name.
         *
         * @param string $class The fully-qualified class name.
         * @return mixed The mapped file name on success, or boolean false on
         * failure.
         */
        public function loadClass($class) {
            // the current namespace prefix
            $prefix = str_replace('_', '\\', $class);
            // work backwards through the namespace names of the fully-qualified
            // class name to find a mapped file name
            while (false !== $pos = strrpos($prefix, '\\')) {
                // retain the trailing namespace separator in the prefix
                $prefix = substr($class, 0, $pos + 1);
                // the rest is the relative class name
                $relative_class = substr($class, $pos + 1);
                // try to load a mapped file for the prefix and relative class
                $mapped_file = $this->loadMappedFile($prefix, $relative_class);
                if ($mapped_file) {
                    return $mapped_file;
                }
                // remove the trailing namespace separator for the next iteration of strrpos()
                $prefix = rtrim($prefix, '\\');
            }
            // If no namespace in list, try another list
            return $this->tryToLoadSimpleFile($class);
        }

        /**
         * Load the mapped file for a namespace prefix and relative class.
         *
         * @param string $prefix The namespace prefix.
         * @param string $relative_class The relative class name.
         * @return mixed Boolean false if no mapped file can be loaded, or the
         * name of the mapped file that was loaded.
         */
        protected function loadMappedFile($prefix, $relative_class) {
            // are there any base directories for this namespace prefix?
            if (isset($this->prefixes[$prefix]) === false) {
                return false;
            }
            // look through base directories for this namespace prefix
            foreach ($this->prefixes[$prefix] as $base_dir) {
                // replace the namespace prefix with the base directory,
                // replace namespace separators with directory separators
                // in the relative class name, append with .php
                $file = $base_dir.str_replace(array('\\', '_'), '/', $relative_class).'.php';
                // if the mapped file exists, require it
                if ($this->requireFile($file)) {
                    return $file;
                }
            }
            // never found it
            return false;
        }

        /**
         * If a file exists, require it from the file system.
         *
         * @param string $file The file to require.
         * @return bool True if the file exists, false if not.
         */
        protected function requireFile($file) {
            $file = HOST.DS.ltrim(str_replace(HOST, '', $file), DS);
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        }

        /**
         * If a file or folder exists, add it to the $simple list.
         *
         * @param string $baseDir file|dir to require.
         * @return bool True if the file exists, false if not.
         */
        public function simple($baseDir) {
            $baseDir = HOST.DS.ltrim(str_replace(HOST, '', $baseDir), DS);
            if(is_file($baseDir)) {
                require $baseDir;
                return TRUE;
            }
            $arr = explode(DS, $baseDir);
            $name = end($arr);
            $this->simple[$name] = $baseDir;
            return TRUE;
        }

        /**
         * Require folder
         * @param $class
         * @return bool
         */
        protected function tryToLoadSimpleFile($class) {
            $path = str_replace(['\\', '_', '/'], DS, $class);
            $arr = explode(DS, $path);
            if(empty($arr)) {
                return FALSE;
            }
            $name = $arr[0];
            if(!$name) {
                return FALSE;
            }
            if(!isset($this->simple[$name])) {
                return FALSE;
            }
            $fullPath = $this->simple[$name].DS.implode(DS, $arr).'.php';
            if(!is_file($fullPath)) {
                return FALSE;
            }
            return $this->requireFile($fullPath);
        }

    }