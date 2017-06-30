<?php
use Core\Arr;
use Core\Config;
use Core\Widgets;
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
    <?php echo Widgets::get('Header'); ?>
    <div class="wConteiner">
        <div class="wSize">
            <?php echo $_breadcrumbs; ?>
            <?php echo Widgets::get('UserMenu'); ?>
            <div class="lk_content">
                <div class="title"><?php echo Config::get('h1'); ?></div>
                <div class="lkMainContent">
                    <?php echo $_content; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo Widgets::get('HiddenData'); ?>
<?php echo Widgets::get('Footer', ['counters' => Arr::get($_seo, 'scripts.counter'), 'config' => $_config]); ?>
</body>
</html>