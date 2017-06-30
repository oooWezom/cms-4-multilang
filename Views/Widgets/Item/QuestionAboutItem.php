<?php use Core\Route; ?>
<div class="question_block">
    <div form="true" class="wForm" data-ajax="question">
        <div class="title"><?php echo __('задать вопрос по товару'); ?></div>
        <div class="wFormRow">
            <input type="text" data-name="name" name="name" data-rule-bykvu="true" placeholder="<?php echo __('Имя'); ?>"
                   data-rule-minlength="2" data-rule-required="true">
            <label><?php echo __('Имя'); ?></label>
        </div>
        <div class="wFormRow">
            <input type="email" data-name="email" name="email" data-rule-email="true" placeholder="E-mail"
                   data-rule-minlength="2" data-rule-required="true">
            <label>E-mail</label>
        </div>
        <div class="wFormRow">
            <textarea name="text" data-name="text" placeholder="<?php echo __('Ваш вопрос'); ?>" data-rule-required="true"></textarea>
            <label><?php echo __('Ваш вопрос'); ?></label>
        </div>
        <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
        <?php if (array_key_exists('token', $_SESSION)): ?>
            <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
        <?php endif; ?>
        <input type="hidden" data-name="id" name="id" value="<?php echo Route::param('id'); ?>"/>
        <div class="tal">
            <button class="wSubmit enterReg_btn"><?php echo __('спросить'); ?></button>
        </div>
    </div>
</div>