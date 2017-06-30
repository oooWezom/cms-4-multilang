<?php
use Core\Widgets;
use Core\Arr;
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
            <div class="item_block clearFix">
                <?php echo $_content; ?>
            </div>
            <?php echo Widgets::get('Item_ItemsSame'); ?>
        </div>
    </div>
</div>
<?php echo Widgets::get('HiddenData'); ?>
<?php echo Widgets::get('Footer', ['counters' => Arr::get($_seo, 'scripts.counter'), 'config' => $_config]); ?>
</body>
</html>