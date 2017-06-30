<?php

return [
    'articles' => 'articles/articles/index',
    'articles/page/<page:[0-9]*>' => 'articles/articles/index',
    'articles/<alias>' => 'articles/articles/inner',
];