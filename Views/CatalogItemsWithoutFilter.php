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
<div class="seoTxt" id="seoTxt">
    <div class="wSize wTxt">
        <?php echo trim(strip_tags(Config::get('seo_text'))) ? Config::get('seo_text') : ''; ?>
    </div>
</div>
<div class="wWrapper">
    <?php echo Widgets::get('Header'); ?>
    <div class="wConteiner">
        <div class="wSize">
            <?php echo $_breadcrumbs; ?>
            <div class="folt_cat_block no_filt clearFix">
                <div class="flr">
                    <h1><?php echo Arr::get($_seo, 'h1'); ?></h1>
                    <?php echo Config::get('brand_description'); ?>
                    <?php echo Widgets::get('CatalogSort'); ?>
                    <?php echo $_content; ?>
                </div>
            </div>
            <div class="novelty clearFix">
                <?php echo Widgets::get('CatalogViewed'); ?>
            </div>
            <div id="clonSeo"></div>
        </div>
    </div>
</div>
<?php echo Widgets::get('HiddenData'); ?>
<?php echo Widgets::get('Footer', ['counters' => Arr::get($_seo, 'scripts.counter'), 'config' => $_config]); ?>
</body>
</html>