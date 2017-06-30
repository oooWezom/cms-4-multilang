<?php
return [
    'cron' => true, // true, false
    'selfCron' => 1, // Send message after refresh page by users ( false or count of letters for one refresh )
    'tableCron' => 'cron', // Name of the cron table
    'image' => 'GD', // GD, Magic
    'password_min_length' => 4, // Min password length
    'visitor' => false, // save user information to the database?
    'token' => 'KjsafkjAdglLIG:g7p89:OHID@)p', // defense from CSRF attacks
    'specifications_types' => [
        1 => __('color_type'),
        2 => __('simple_type'),
        3 => __('multi_select_type'),
    ],
];