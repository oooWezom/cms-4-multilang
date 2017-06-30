<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv="imagetoolbar" content="no">
    <title><?php echo Core\Arr::get($_seo, 'title'); ?></title>
    <link rel="stylesheet" href="<?php echo Core\HTML::bmedia('css/login.css'); ?>">
    <link rel="stylesheet" href="<?php echo Core\HTML::bmedia('css/magnific-popup.css'); ?>">
    <link rel="stylesheet" href="<?php echo Core\HTML::bmedia('css/responsiveness.css'); ?>">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="target-densitydpi=device-dpi">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="<?php echo Core\HTML::bmedia('pic/apple-touch-fa fa-57x57.png'); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo Core\HTML::bmedia('pic/apple-touch-fa fa-72x72.png'); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo Core\HTML::bmedia('apple-touch-fa fa-114x114.png'); ?>">

    <script src="<?php echo Core\HTML::bmedia('js/modernizr.js'); ?>"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>-->
    <script>window.jQuery || document.write('<script src="<?php echo Core\HTML::bmedia('js/jquery-1.9.0.min.js'); ?>">\x3C/script>')</script>
    <script src="<?php echo Core\HTML::bmedia('js/jquery-ui-1.10.1.min.js'); ?>"></script>
    <script src="<?php echo Core\HTML::bmedia('js/jquery.magnific-popup.min.js'); ?>"></script>
    <script>
        $(function(){
            var radio = $('[type=radio]:not(".switch")');
            var ckbx = $('[type=checkbox]:not(".switch")');
            radio.each(function(){
                var el = $(this);
                var par = el.parent();
                if(par.is('label')){
                    par.addClass('radioWrap');
                    if(el.prop('disabled')){
                        par.addClass('disabled')
                    }
                }
            });;
            ckbx.each(function(){
                var el = $(this);
                var par = el.parent();
                if(par.is('label')){
                    par.addClass('ckbxWrap');;
                    if(el.prop('disabled')){
                        par.addClass('disabled')
                    }
                }
            });;
            $('.form-block select').closest('.controls').addClass('selectWrap');;
            radio.on('change',function(){
                var el = $(this);
                var name = el.attr('name');;
                var parent = el.parent();
                if(parent.is('label')){
                    if(el.prop('checked')){
                        $('[name='+name+']').parent('label').removeClass('checked');;
                        parent.addClass('checked')
                    }
                }
            });;



            ckbx.on('change',function(){
                var el = $(this);
                var parent = el.parent();
                if(parent.is('label')){
                    if(el.prop('checked')){
                        parent.addClass('checked')
                    }else{
                        parent.removeClass('checked')
                    }
                }
            });;

            radio.add(ckbx).trigger('change')
        })
    </script>
    <link rel="shortcut icon" href="/Wezom/favicon.ico">
    <link rel="image_src" href="<?php echo Core\HTML::media('pic/expres_icon.jpg'); ?>">
    <!--[if gte IE 9]><style type="text/css">.gradient {filter: none;}</style><![endif]-->
</head>
<body>
    
    <div class="auBg auWrap">
        <div class="auLogoWrap">
            <div class="auTopSize"></div>
            <div class="auLogoInner">
                <span class="fontLogo">
                    <span class="top fontLogoName">wezom</span>
                    <span class="top fontLogoTitle">
                        <span class="fontLogoLabel">cms</span>
                        <span class="fontLogoVersion">4.0</span>
                    </span>
                </span>
            </div>
        </div>
        <div class="auContentWrap">
            <div class="auTopSize"></div>
            <div class="auContentInner">
                <a href="/" class="siteLink"><?php echo __('Перейти на главную страницу сайта'); ?></a>
                <div class="auSubTitle"><?php echo __('Вход в панель управления'); ?></div>
                <div class="auLabel"><?php echo __('Введите логин и пароль доступа к панели управления'); ?></div>
                <?php echo $_content; ?>
            </div>
        </div>  
    </div>
    
</body>
</html>