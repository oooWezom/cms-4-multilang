<?php use Core\HTML; ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo isset($title) ? $title : NULL; ?></title>
<?php if (!$hide_meta): ?>
<meta name="description" lang="ru-ru" content="<?php echo isset($description) ? $description : NULL; ?>">
<meta name="keywords" lang="ru-ru" content="<?php echo isset($keywords) ? $keywords : NULL; ?>">
<?php endif; ?>

<?php if ($canonical): ?><link rel="canonical" href="<?php echo HTML::link($canonical,true); ?>"/><?php endif; ?>
<?php if ($next): ?><link rel="next" href="<?php echo HTML::link($next,true); ?>"/><?php endif; ?>
<?php if ($prev): ?><link rel="prev" href="<?php echo HTML::link($prev,true); ?>"/><?php endif; ?>

<meta name="author" lang="ru-ru" content="AIRPAC">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=1000">
<meta http-equiv="imagetoolbar" content="no">
<!-- saved from url=(0014)about:internet -->
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">

<link rel="icon" type="image/png" href="<?php echo HTML::media('favicons/favicon-16x16.png'); ?>" sizes="16x16">
<link rel="icon" type="image/png" href="<?php echo HTML::media('favicons/favicon-32x32.png'); ?>" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo HTML::media('favicons/favicon-96x96.png'); ?>" sizes="96x96">
<link rel="icon" type="image/png" href="<?php echo HTML::media('favicons/favicon-160x160.png'); ?>" sizes="160x160">
<link rel="icon" type="image/png" href="<?php echo HTML::media('favicons/favicon-196x196.png'); ?>" sizes="192x192">
<meta name="apple-mobile-web-app-title" content="Title">
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo HTML::media('favicons/apple-touch-icon-57x57.png'); ?>">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo HTML::media('favicons/apple-touch-icon-60x60.png'); ?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo HTML::media('favicons/apple-touch-icon-72x72.png'); ?>">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo HTML::media('favicons/apple-touch-icon-76x76.png'); ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo HTML::media('favicons/apple-touch-icon-114x114.png'); ?>">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo HTML::media('favicons/apple-touch-icon-120x120.png'); ?>">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo HTML::media('favicons/apple-touch-icon-152x152.png'); ?>">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo HTML::media('favicons/apple-touch-icon-144x144.png'); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo HTML::media('favicons/apple-touch-icon-180x180.png'); ?>">
<meta name="application-name" content="Title">
<meta name="msapplication-tooltip" content="Description">
<meta name="msapplication-TileColor" content="#b91d47">
<meta name="msapplication-config" content="/browserconfig.xml">
<link rel="image_src" href="<?php echo HTML::media('favicons/express.jpg'); ?>">

<?php $css = Minify\Core::factory('css')->minify($styles); ?>
<?php foreach ($css as $file_style): ?>
    <?php echo HTML::style($file_style) . "\n"; ?>
<?php endforeach; ?>

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js/media"></script><![endif]-->
<?php $js = Minify\Core::factory('js')->minify($scripts); ?>
<?php foreach ($js as $file_script): ?>
    <?php echo HTML::script($file_script) . "\n"; ?>
<?php endforeach; ?>

<?php foreach ($scripts_no_minify as $file_script): ?>
    <?php echo HTML::script($file_script) . "\n"; ?>
<?php endforeach; ?>
