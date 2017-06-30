<?php
    namespace Wezom\Modules\Visitors\Models;

    use Core\QB\DB;

    class Referers extends \Core\Common {

        public static $table = 'visitors_referers';
        public static $filters = [
            'ip' => [
                'action' => 'LIKE',
            ],
        ];

    }