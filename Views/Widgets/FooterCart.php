<?php
use Core\HTML;
use Core\Config;

?>
<footer class="wFooter basket_page">
    <div class="wSize">
        <div class="foot_bot clearFix">
            <div class="fll">
                <div class="logo_foot">
                    <img src="<?php echo HTML::media('pic/logo_foot.png'); ?>" alt="">
                    <p><?php echo __('2014 © Интернет магазин спортивной обуви и одежды');?></p>
                </div>
            </div>
            <div class="flr">
                <a href="http://wezom.com.ua" target="_blank" class="weZom"><span><?php echo __('Разработка сайта — студия');?></span></a>
            </div>
            <div class="flc">
                <?php if (Config::get('static.subscribe_text')): ?>
                    <p><?php echo Config::get('static.subscribe_text'); ?></p>
                <?php endif ?>
                <div class="foot_podp">
                    <div form="true" class="wForm regBlock" data-ajax="subscribe">
                        <div class="tar">
                            <button class="wSubmit enterReg_btn">подписаться</button>
                        </div>
                        <div class="wFormRow">
                            <input data-name="email" type="email" name="email" placeholder="E-mail" required="">
                            <label>E-mail</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach (Config::get('counters') as $counter): ?>
            <?php echo $counter; ?>
        <?php endforeach ?>
    </div>
</footer>