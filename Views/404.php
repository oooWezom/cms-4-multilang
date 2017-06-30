<?php
use Core\Widgets;
use Core\HTML;
?>
<!DOCTYPE html>
<html lang="ru-ru" dir="ltr">
<!-- (c) студия Wezom | www.wezom.com.ua -->
<head>
    <?php echo Widgets::get('Head', $_seo); ?>
    <?php foreach ($_seo['scripts']['head'] as $script): ?>
        <?php echo $script; ?>
    <?php endforeach ?>
    <?php echo $GLOBAL_MESSAGE; ?>
</head>
<body>
<?php foreach ($_seo['scripts']['body'] as $script): ?>
    <?php echo $script; ?>
<?php endforeach ?>
<div class="wWrapper">
    <div class="page_404">
        <p>404</p>
        <p><?php echo __('Страница не найдена. Начните с <a href="/">Главной страницы</a>.'); ?></p>
        <a href="<?php echo HTML::link('sitemap'); ?>"><?php echo __('Карта сайта'); ?></a>
    </div>
</div>
</body>
</html>