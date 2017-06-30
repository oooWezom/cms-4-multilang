<?php
    namespace Wezom\Modules\MailTemplates\Models;
    
    use Core\QB\DB;

    class MailTemplates extends \Core\CommonI18n {

        public static $table = 'mail_templates';
        public static $tableI18n = 'mail_templates_i18n';
        
        public static function getRowSimple($value, $field = 'id', $status = NULL, $lang = null) {
            if (!$lang) {
                $lang = \I18n::$lang;
            }
            
            if (APPLICATION == 'backend') {
                $lang = \I18n::$default_lang_backend;
            }
            static::$tableI18n = static::$table . '_i18n';
            $row = DB::select(static::$tableI18n . '.*', static::$table . '.*')
                    ->from(static::$table)
                    ->join(static::$tableI18n, 'LEFT')->on(static::$tableI18n . '.row_id', '=', static::$table . '.id')
                    ->where(static::$table . '.' . $field, '=', $value);
            if ($status <> NULL) {
                $row->where(static::$table . '.status', '=', $status);
            }
            return $row->where(static::$tableI18n . '.language', '=', $lang)->find();
        }


    }