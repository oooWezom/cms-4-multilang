<?php

return [
    'account' => 'user/user/index',
    'account/login-by-social-network' => 'user/user/socials',
    'account/add-social-network' => 'user/user/addSocials',
    'account/<action>' => 'user/user/<action>',
    'account/orders/<id:[0-9]*>' => 'user/user/order',
    'account/print/<id:[0-9]*>' => 'user/user/print',
    'account/confirm/hash/<hash>' => 'user/user/confirm',
    'auth-like-regular-user/<hash>' => 'user/user/fastAuth',
];