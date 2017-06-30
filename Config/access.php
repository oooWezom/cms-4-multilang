<?php

return [
    [
        'name' => 'Панель управления',
        'module' => 'index',
        'controller' => 'index',
        'edit' => 0,
    ],
    [
        'name' => 'Текстовые страницы',
        'module' => 'content',
        'controller' => 'content',
    ],
    [
        'name' => 'Системные страницы',
        'module' => 'content',
        'controller' => 'control',
    ],
    [
        'name' => 'Новости',
        'module' => 'content',
        'controller' => 'news',
    ],
    [
        'name' => 'Статьи',
        'module' => 'content',
        'controller' => 'articles',
    ],
    [
        'name' => 'Меню',
        'module' => 'menu',
        'controller' => 'menu',
    ],
    [
        'name' => 'Слайдшоу',
        'module' => 'multimedia',
        'controller' => 'slider',
    ],
    [
        'name' => 'Галерея',
        'module' => 'multimedia',
        'controller' => 'gallery',
    ],
    [
        'name' => 'Банерная система',
        'module' => 'multimedia',
        'controller' => 'banners',
    ],
    [
        'name' => 'Вопросы к товарам',
        'module' => 'catalog',
        'controller' => 'questions',
        'view' => false,
    ],
    [
        'name' => 'Отзывы о товарах',
        'module' => 'catalog',
        'controller' => 'comments',
    ],
    [
        'name' => 'Группы товаров',
        'module' => 'catalog',
        'controller' => 'groups',
    ],
    [
        'name' => 'Товары',
        'module' => 'catalog',
        'controller' => 'items',
    ],
    [
        'name' => 'Производители',
        'module' => 'catalog',
        'controller' => 'brands',
    ],
    [
        'name' => 'Модели',
        'module' => 'catalog',
        'controller' => 'models',
    ],
    [
        'name' => 'Спецификации',
        'module' => 'catalog',
        'controller' => 'specifications',
    ],
    [
        'name' => 'Заказы',
        'module' => 'orders',
        'controller' => 'orders',
        'view' => false,
    ],
    [
        'name' => 'Заказы в один клик',
        'module' => 'orders',
        'controller' => 'simple',
    ],
    [
        'name' => 'Пользователи сайта',
        'module' => 'user',
        'controller' => 'users',
    ],
    [
        'name' => 'Подписчики на рассылку писем',
        'module' => 'subscribe',
        'controller' => 'subscribers',
    ],
    [
        'name' => 'Рассылка писем',
        'module' => 'subscribe',
        'controller' => 'subscribe',
    ],
    [
        'name' => 'Сообщения из контактной формы',
        'module' => 'contacts',
        'controller' => 'contacts',
        'view' => false,
    ],
    [
        'name' => 'Заказы звонка',
        'module' => 'contacts',
        'controller' => 'callback',
        'view' => false,
    ],
    [
        'name' => 'Шаблоны писем',
        'module' => 'mailTemplates',
        'controller' => 'mailTemplates',
        'view' => false,
    ],
    [
        'name' => 'Настройки сайта',
        'module' => 'config',
        'controller' => 'config',
    ],
    [
        'name' => 'СЕО. Шаблоны',
        'module' => 'seo',
        'controller' => 'templates',
    ],
    [
        'name' => 'СЕО. Теги для конкретных ссылок',
        'controller' => 'links',
    ],
    [
        'name' => 'СЕО. Метрика и счетчики',
        'controller' => 'scripts',
    ],
    [
        'name' => 'СЕО. Перенаправления',
        'controller' => 'redirects',
    ],
];