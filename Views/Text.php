<?php
use Core\Widgets;
use Core\Config;
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
<?php if (trim(strip_tags(Arr::get($_seo, 'seo_text')))): ?>
    <div class="seoTxt" id="seoTxt">
        <div class="wSize wTxt">
            <?php echo Arr::get($_seo, 'seo_text'); ?>
        </div>
    </div>
<?php endif ?>
<div class="wWrapper">
    <?php echo Widgets::get('Header', ['config' => $_config]); ?>
    <div class="wConteiner">
        <div class="wSize">
            <?php echo $_breadcrumbs; ?>
            <div class="<?php echo Config::get('content_class'); ?>">
                <h1 class="title"><?php echo Arr::get($_seo, 'h1'); ?></h1>
                <?php echo $_content; ?>
            </div>
            <div id="clonSeo"></div>
        </div>
    </div>
</div>
<?php echo Widgets::get('HiddenData'); ?>
<?php echo Widgets::get('Footer', ['counters' => Arr::get($_seo, 'scripts.counter'), 'config' => $_config]); ?>
</body>
</html>