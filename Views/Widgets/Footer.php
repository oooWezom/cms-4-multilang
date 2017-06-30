<?php
use Core\Widgets;
use Core\HTML;
use Core\Config;

?>
<footer class="wFooter">
    <div class="wSize">
        <div class="foot_top">
            <?php echo Widgets::get('Info'); ?>
        </div>
        <div class="foot_center clearFix">
            <div class="fll">
                <ul>
                    <li><a href="<?php echo HTML::link('new'); ?>"><span><?php echo __('новинки');?></span></a></li>
                    <li><a href="<?php echo HTML::link('popular'); ?>"><span><?php echo __('популярные');?></span></a></li>
                    <li><a href="<?php echo HTML::link('sale'); ?>"><span><?php echo __('акции');?></span></a></li>
                    <li><a href="<?php echo HTML::link('brands'); ?>"><span><?php echo __('бренды');?></span></a></li>
                </ul>
            </div>
            <div class="flr">
                <ul>
                    <?php foreach ($contentMenu as $obj): ?>
                        <li>
                            <a href="<?php echo HTML::link($obj->url); ?>"><span><?php echo $obj->name; ?></span></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="map_site"><a href="<?php echo HTML::link('sitemap'); ?>"><span><?php echo __('карта сайта');?></span></a>
                </div>
            </div>
            <?php echo Widgets::get('CatalogMenuBottom'); ?>
        </div>
        <div class="foot_bot clearFix">
            <div class="fll">
                <div class="logo_foot">
                    <!-- <img src="<?php // echo Core\HTML::media('pic/logo_foot.png'); ?>" alt=""> -->
                    <p><?php echo __('2014 © Интернет магазин спортивной обуви и одежды');?></p>
                </div>
            </div>
            <div class="flr">
                <a href="http://wezom.com.ua" target="_blank" class="weZom"><span><?php echo __('Разработка сайта — студия');?></span></a>
            </div>
            <div class="flc">
                <p><?php echo __('Хочешь быть в числе первых, кому мы сообщим об акциях и новинках?!');?></p>
                <div class="foot_podp">
                    <div form="true" class="wForm regBlock" data-ajax="subscribe">
                        <div class="tar">
                            <button class="wSubmit enterReg_btn"><?php echo __('подписаться');?></button>
                        </div>
                        <div class="wFormRow">
                            <input data-name="email" type="email" name="em" data-rule-email="true" placeholder="E-mail"
                                   required="">
                            <label><?php echo __('E-mail');?></label>
                        </div>
                        <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
                        <?php if (array_key_exists('token', $_SESSION)): ?>
                            <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($counters)): ?>
            <?php foreach ($counters as $counter): ?>
                <?php echo $counter; ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</footer>