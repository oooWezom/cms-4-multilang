<?php use Core\Widgets; ?>
<!DOCTYPE html>
<html lang="<?php echo \I18n::$lang;?>" dir="ltr">
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
        <?php echo $_content; ?>
    </div>
</div>
<div class="wWrapper">
    <?php echo Widgets::get('Header', ['config' => $_config]); ?>
    <div class="wConteiner">
        <div class="wSize">
            <?php echo Widgets::get('Index_Slider'); ?>
            <?php echo Widgets::get('Index_Banners'); ?>
            <?php echo Widgets::get('Index_ItemsNew'); ?>
            <?php echo Widgets::get('News'); ?>
            <?php echo Widgets::get('Articles'); ?>
            <div class="clear"></div>
            <?php echo Widgets::get('Index_ItemsPopular'); ?>
            <div id="clonSeo"></div>
        </div>
    </div>
</div>
<?php echo Widgets::get('HiddenData'); ?>
<?php echo Widgets::get('Footer', ['counters' => Core\Arr::get($_seo, 'scripts.counter'), 'config' => $_config]); ?>
</body>
</html>