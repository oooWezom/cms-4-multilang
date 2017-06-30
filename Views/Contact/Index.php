<div class="contact_block clearFix">
    <div class="fll">
        <div form="true" class="regBlock wForm" data-ajax="contacts" style="float: left; width: 100%;">
            <div class="wBasketHead">
                <div class="wBasketTTL"><?php echo __('Отправить сообщение'); ?></div>
            </div>
            <div class="wFormRow">
                <input data-name="name" type="text" name="name" placeholder="<?php echo __('Имя'); ?>" data-rule-minlength="2" required="">
                <label><?php echo __('Имя'); ?></label>
            </div>
            <div class="wFormRow">
                <input data-name="email" type="email" name="email" placeholder="E-mail" required="">
                <label>E-mail</label>
            </div>
            <div class="wFormRow">
                <textarea data-name="text" placeholder="<?php echo __('Сообщение'); ?>" name="text" required=""></textarea>
                <label><?php echo __('Сообщение'); ?></label>
            </div>
            <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
            <?php if (array_key_exists('token', $_SESSION)): ?>
                <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
            <?php endif; ?>
            <div class="butt">
                <button class="wSubmit enterReg_btn" id="contactForm"><?php echo __('Отправить сообщение'); ?></button>
            </div>
        </div>
    </div>
    <div class="flr">
        <?php echo $text; ?>
    </div>
</div>