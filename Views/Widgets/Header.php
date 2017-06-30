<?php
use Core\HTML;
use Core\Config;
use Core\Widgets;

?>
<header class="wHeader">
    <div class="wSize">
        <div class="head_top">
            <div class="fll">
                <ul>
                    <li><a href="<?php echo HTML::link(); ?>">
                            <div class="gl"></div>
                        </a></li>
                    <?php foreach ($contentMenu as $obj): ?>
                        <li><a href="<?php echo HTML::link($obj->url); ?>"><?php echo $obj->name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="flr">
                <div class="block_p">
                    <a href="tel:<?php echo Config::get('static.phone'); ?>?call"
                       class="head_phone"><?php echo Config::get('static.phone'); ?></a>
                    <a href="#enterReg2" class="call_back enterReg2"><span><?php echo __('ОБРАТНЫЙ ЗВОНОК');?></span></a>
                </div>
            </div>
            <div class="flc">
                <!-- <a href="<?php // echo Core\HTML::link(); ?>"><img src="<?php // echo Core\HTML::media('pic/logo.png'); ?>" alt=""></a> -->
            </div>
        </div>
        <div class="head_center">
            <div class="fll">
                <ul class="soc_seti">
                    <li><a href="<?php echo Config::get('socials.vk'); ?>" class="circle_seti"
                           target="_blank"><span class="img_seti"></span>
                            <div class="name_seti"></div>
                            <div class="name_seti"></div>
                        </a></li>
                    <li><a href="<?php echo Config::get('socials.fb'); ?>" class="circle_seti"
                           target="_blank"><span class="img_seti"></span>
                            <div class="name_seti"></div>
                            <div class="name_seti"></div>
                        </a></li>
                    <li><a href="<?php echo Config::get('socials.instagram'); ?>" class="circle_seti"
                           target="_blank"><span class="img_seti"></span>
                            <div class="name_seti"></div>
                            <div class="name_seti"></div>
                        </a></li>
                </ul>
            </div>
            <div class="flr">
                <?php echo Widgets::get('LanguageSwitcher'); ?>
                <?php if (!$user): ?>
                    <a href="#enterReg" class="enter enterReg"><span><?php echo __('Вход');?></span></a>
                <?php else: ?>
                    <a href="<?php echo HTML::link('account'); ?>" class="basket enter"><span><?php echo __('Кабинет');?></span></a>
                    <a href="<?php echo HTML::link('account/logout'); ?>" class="basket"><span><?php echo __('Выход');?></span></a>
                <?php endif ?>
                <a href="<?php echo HTML::link('cart'); ?>" class="basket"><span><?php echo __('Корзина');?></span></a>
                <a href="#orderBasket" class="basket_img wb_edit_init wb_butt">
                    <div class="paket"></div>
                    <span class="paket_in"></span>
                    <span id="topCartCount"><?php echo $countItemsInTheCart; ?></span>
                </a>
            </div>
            <?php echo Widgets::get('Info'); ?>
        </div>
        <div class="head_bot">
            <?php echo Widgets::get('CatalogMenuTop'); ?>
            <div class="lupa"></div>
            <div class="poisk_block">
                <form action="<?php echo HTML::link('search'); ?>" method="GET">
                    <input type="text" name="query" placeholder="<?php echo __('Поиск по сайту');?>">
                    <input type="submit" value="<?php echo __('искать');?>">
                </form>
            </div>
        </div>
    </div>
</header>