<?php

return [
    'news' => 'news/news/index',
    'news/page/<page:[0-9]*>' => 'news/news/index',
    'news/<alias>' => 'news/news/inner',
];