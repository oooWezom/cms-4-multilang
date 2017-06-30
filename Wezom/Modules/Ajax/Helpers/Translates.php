<?php
namespace Wezom\Modules\Ajax\Helpers;

use Core\Files;
use Core\QB\DB;

/**
 * Class Translates
 * @package Wezom\Modules\Ajax\Helpers
 */
class Translates
{
    /**
     * Файл со стандартными переводами для перезаписи
     * @var string
     */
    private $commonLanguageFile = 'general.php';

    /**
     * Яхык по умолчанию для сбора переводов
     * В этом файле будут по умолчанию использоваться ключи в качестве перевода
     * @var string
     */
    private $defaultLanguage = 'ru';

    /**
     * Допустимые расширения файлов для сбора переводов
     * @var array
     */
    private $includedExtensions = ['php'];

    /**
     * Исключенные папки для поиска
     * @var array
     */
    private $excludedFolders = [
        'Media', 'Plugins', 'Cache', '__SQL', '__HTML'
    ];

    /**
     * Исключенные файлы для записи
     * @var array
     */
    private $excludedFiles = [
        'ItemsMail.php', 'Api.php'
    ];

    /**
     * Центральное регулярное выражение для поиска записей
     * @var string
     */
    private $regExp = '/
# match __(...(...)...) message lines (having arbitrary nesting depth).
__\(                     # Outermost opening bracket (with leading __().
(                        # Group $1: Bracket contents (subroutine).
  (?:                    # Group of bracket contents alternatives.
    [^()"\']++           # Either one or more non-brackets, non-quotes,
  | "[^"\\\\]*(?:\\\\[\S\s][^"\\\\]*)*"      # or a double quoted string,
  | \'[^\'\\\\]*(?:\\\\[\S\s][^\'\\\\]*)*\'  # or a single quoted string,
  | \( (?1) \)          # or a nested bracket (repeat group 1 here!).
  )*                    # Zero or more bracket contents alternatives.
)                       # End $1: recursed subroutine.
\)                      # Outermost closing bracket.
/mx';

    /**
     * Корень в файловой системе, от куда начинаем сканирование
     * @var string
     */
    private $folder;

    /**
     * Название папки в переводах для записи туда
     * @var string
     */
    private $i18nFolder;

    /**
     * Финальный список переводов
     * @var array
     */
    private $translates;

    /**
     * Есть ли необходимость сканировать БД
     * @var bool
     */
    public $database = false;

    /**
     * Translates constructor.
     * @param $rootFolder
     * @param $i18nFolder
     * @param array $additionalExcludedFolders
     */
    public function __construct($rootFolder, $i18nFolder, array $additionalExcludedFolders = [])
    {
        if ($additionalExcludedFolders) {
            $this->excludedFolders = array_merge($this->excludedFolders, $additionalExcludedFolders);
        }
        $this->folder = $rootFolder;
        $this->i18nFolder = $i18nFolder;
    }

    /**
     * Подготавливаем переводы. Собираем их по ФС и БД
     */
    public function prepareTranslates()
    {
        set_time_limit(0);
        $this->fileSystem();
        $this->database();
        $this->translates = array_unique($this->translates);
    }

    /**
     * Возвращаем все найденные переводы пользователю
     * @return array
     */
    public function getTranslates()
    {
        if ($this->translates !== null) {
            return $this->translates;
        }
        $this->prepareTranslates();
        return $this->translates;
    }

    /**
     * Текущая корневая папка для поиска
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Формируем и записываем в файл переводы для выбранного языка
     * @param $language
     */
    public function generateTranslatesFor($language)
    {
        $text = "<?php";
        $text .= "\n\t return [";
        foreach ($this->filterTranslates($language) AS $key => $value) {
            $text .= "\n\t\t'" . str_replace("'", "\'", $key) . "' => '" . str_replace("'", "\'", $value) . "',";
        }
        $text .= "\n\t];";
        file_put_contents(HOST . '/Plugins/I18n/' . $this->i18nFolder . '/' . $language . '/' . $this->commonLanguageFile, $text);
    }

    /**
     * Файл ли это
     * @param $file
     * @return bool
     */
    private function isFile($file)
    {
        return is_file($file);
    }

    /**
     * Папка ли это
     * @param $folder
     * @return bool
     */
    private function isFolder($folder)
    {
        return is_dir($folder);
    }

    /**
     * Поседний элемент в переданном пути
     * @param $path
     * @return mixed
     */
    private function lastElement($path)
    {
        $partsOfPath = explode('/', trim($path, '/'));
        return end($partsOfPath);
    }

    /**
     * Расширение переданного файла
     * @param $file
     * @return mixed
     */
    private function extension($file)
    {
        $element = $this->lastElement($file);
        $partsOfFile = explode('.', $element);
        return end($partsOfFile);
    }

    /**
     * Находится ли файл в исключениях
     * @param $file
     * @return bool
     */
    private function isExcludedFile($file)
    {
        return in_array($this->lastElement($file), $this->excludedFiles) && in_array($this->extension($file), $this->includedExtensions);
    }

    /**
     * Находится ли папка в исключениях
     * @param $folder
     * @return bool
     */
    private function isExcludedFolder($folder)
    {
        return in_array($this->lastElement($folder), $this->excludedFolders);
    }

    /**
     * Пропустить ли этот файл / папку? Это означает что мы не сканируем его
     * @param $folder
     * @return bool
     */
    private function skip($folder)
    {
        if ($this->isFile($folder)) {
            return $this->isExcludedFile($folder);
        }
        if ($this->isFolder($folder)) {
            return $this->isExcludedFolder($folder);
        }
        return true;
    }

    /**
     * Подготавливаем папку для записи туда переводов
     * @param $currentDefaultFolder
     */
    private function prepareFolders($currentDefaultFolder)
    {
        if ($this->isFolder($currentDefaultFolder) === false) {
            Files::createFolder($currentDefaultFolder, 0777);
        }
        if (is_writable($currentDefaultFolder) === false) {
            @chmod($currentDefaultFolder, 0777);
        }
    }

    /**
     * Формируем окончательный список переводов для записи в дефолтный файл
     * @param $language
     * @return array
     */
    private function filterTranslates($language)
    {
        $currentDefaultFolder = HOST . '/Plugins/I18n/' . $this->i18nFolder . '/' . $language;
        $this->prepareFolders($currentDefaultFolder);
        $currentDefaultFilePath = $currentDefaultFolder . '/' . $this->commonLanguageFile;
        if ($this->isFile($currentDefaultFilePath) === false) {
            return [];
        }
        $translates = $this->translates;
        $commonTranslates = include $currentDefaultFilePath;

        $translationsInOtherFiles = [];
        foreach (scandir($currentDefaultFolder) as $file) {
            if ($file != '.' && $file != '..' && $file !== $this->commonLanguageFile) {
                $translationsInOtherFiles += include $currentDefaultFolder . '/' . $file;
            }
        }
        foreach ($translates as $key => $value) {
            if (array_key_exists($key, $translationsInOtherFiles)) {
                unset($translates[$key]);
            }
        }
        foreach ($commonTranslates as $key => $value) {
            if (array_key_exists($key, $translates) === false) {
                unset($commonTranslates[$key]);
            }
        }

        $result = array_merge($translates, $commonTranslates);
        if ($this->defaultLanguage !== $language) {
            foreach ($result AS $key => $value) {
                $result[$key] = '';
            }
        }
        return $result;
    }

    /**
     * Достаем записи для которых нужны переводы из БД
     */
    private function database()
    {
        if ($this->database === false) {
            return null;
        }
        $_result = DB::select('name')->from('config')->find_all();
        foreach ($_result as $obj) {
            $this->translates[$obj->name] = '';
        }
        $_result = DB::select('name')->from('config_groups')->find_all();
        foreach ($_result as $obj) {
            $this->translates[$obj->name] = '';
        }
        $_result = DB::select('name')->from('menu')->find_all();
        foreach ($_result as $obj) {
            $this->translates[$obj->name] = '';
        }
        $_result = DB::select('name')->from('mail_templates')->find_all();
        foreach ($_result as $obj) {
            $this->translates[$obj->name] = '';
        }
    }

    /**
     * Сканируем файловую систему
     * @param null $folder
     */
    private function fileSystem($folder = null)
    {
        if ($this->translates === null || is_array($this->translates) === false) {
            $this->translates = [];
        }
        $folder = $folder ?: $this->folder;
        foreach (scandir($folder) as $element) {
            if ($element === '.' || $element === '..' || $this->skip($folder . '/' . $element) === true) {
                continue;
            }
            if ($this->isFolder($folder . '/' . $element)) {
                $this->fileSystem($folder . '/' . $element);
            } else {
                $string = file_get_contents($folder . '/' . $element);
                preg_match_all($this->regExp, $string, $matches);
                if (array_key_exists(1, $matches) && is_array($matches[1])) {
                    foreach ($matches[1] as $el) {
                        if ($el && !preg_match('/array\(/', $el) && !preg_match('/,( ){0,}\[.*\]/', $el) && preg_match('/\'.*\'/', $el)) {
                            $e = explode("'),", $el);
                            $key = $this->_substr($e[0], 1, $this->_strlen($e[0]) - 2);
                            if ($key) {
                                $this->translates[$key] = $key;
                            }
                        }
                    }
                }
            }
        }
    }

    private function _substr($str, $offset, $length = null)
    {
        // Normalize params
        $str = (string)$str;
        $strlen = $this->_strlen($str);
        $offset = (int)($offset < 0) ? max(0, $strlen + $offset) : $offset; // Normalize to positive offset
        $length = ($length === null) ? null : (int)$length;
        // Impossible
        if ($length === 0 or $offset >= $strlen or ($length < 0 and $length <= $offset - $strlen))
            return '';
        // Whole string
        if ($offset == 0 and ($length === null or $length >= $strlen))
            return $str;
        // Build regex
        $regex = '^';
        // Create an offset expression
        if ($offset > 0) {
            // PCRE repeating quantifiers must be less than 65536, so repeat when necessary
            $x = (int)($offset / 65535);
            $y = (int)($offset % 65535);
            $regex .= ($x == 0) ? '' : '(?:.{65535}){' . $x . '}';
            $regex .= ($y == 0) ? '' : '.{' . $y . '}';
        }
        // Create a length expression
        if ($length === null) {
            $regex .= '(.*)'; // No length set, grab it all
        } // Find length from the left (positive length)
        elseif ($length > 0) {
            // Reduce length so that it can't go beyond the end of the string
            $length = min($strlen - $offset, $length);
            $x = (int)($length / 65535);
            $y = (int)($length % 65535);
            $regex .= '(';
            $regex .= ($x == 0) ? '' : '(?:.{65535}){' . $x . '}';
            $regex .= '.{' . $y . '})';
        } // Find length from the right (negative length)
        else {
            $x = (int)(-$length / 65535);
            $y = (int)(-$length % 65535);
            $regex .= '(.*)';
            $regex .= ($x == 0) ? '' : '(?:.{65535}){' . $x . '}';
            $regex .= '.{' . $y . '}';
        }
        preg_match('/' . $regex . '/us', $str, $matches);
        return $matches[1];
    }

    private function _strlen($str)
    {
        return strlen(utf8_decode($str));
    }

    public static function getFrontendLanguages()
    {
        return DB::select()->from('i18n')->execute()->as_array('alias');
    }

}