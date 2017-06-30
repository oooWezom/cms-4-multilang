<div form="true" class="wForm regBlock lkForm" methos="post" action="" data-ajax="change_password">
    <div class="wFormRow">
        <input data-name="old_password" type="password" minlength="4" placeholder="<?php echo __('Старый пароль'); ?>"
               name="old_password" data-rule-required="true">
        <label><?php echo __('Старый пароль'); ?></label>
    </div>
    <div class="wFormRow">
        <input data-name="password" type="password" minlength="4" id="password"
               placeholder="<?php echo __('Новый пароль'); ?>" name="password" data-rule-required="true">
        <label><?php echo __('Новый пароль'); ?></label>
    </div>
    <div class="wFormRow">
        <input data-name="confirm" type="password" minlength="4"
               placeholder="<?php echo __('Повторите новый пароль'); ?>" name="confirm" data-rule-required="true">
        <label><?php echo __('Повторите новый пароль'); ?></label>
    </div>
    <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
    <?php if (array_key_exists('token', $_SESSION)): ?>
        <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
    <?php endif; ?>
    <div class="tar">
        <button class="wSubmit enterReg_btn"><?php echo __('подтвердить'); ?></button>
    </div>
</div>