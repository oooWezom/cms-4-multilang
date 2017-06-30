<?php
use Core\Route;
use Core\HTML;

?>
<div class="lk_menu">
    <div class="menuElement <?php echo Route::action() == 'index' ? 'current' : ''; ?>">
        <a href="<?php echo HTML::link('account'); ?>"><?php echo __('Личный кабинет'); ?></a>
    </div>
    <div class="menuElement <?php echo Route::action() == 'orders' ? 'current' : ''; ?>">
        <a href="<?php echo HTML::link('account/orders'); ?>"><?php echo __('Мои заказы'); ?></a>
    </div>
    <div class="menuElement <?php echo Route::action() == 'profile' ? 'current' : ''; ?>">
        <a href="<?php echo HTML::link('account/profile'); ?>"><?php echo __('Мои данные'); ?></a>
    </div>
    <div class="menuElement <?php echo Route::action() == 'change_password' ? 'current' : ''; ?>">
        <a href="<?php echo HTML::link('account/change_password'); ?>"><?php echo __('Изменить пароль'); ?></a>
    </div>
</div>