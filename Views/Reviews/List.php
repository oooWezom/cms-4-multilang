<?php
use Core\HTML;
use Core\Text;

?>
<?php foreach ($result as $obj): ?>
    <div class="news clearFix">
		<p><?php echo $obj->name; ?> <?php echo date('d.m.Y', $obj->date); ?></p>
		<p><?php echo $obj->text; ?></p>
		<?php if ($obj->answer) :?>
			<p><?php echo __('Админитратор'); ?> <?php echo date('d.m.Y', $obj->date_answer); ?></p>
			<p><?php echo $obj->answer; ?></p>
		<?php endif; ?>
    </div>
<?php endforeach; ?>
<?php echo $pager; ?>

<div class="leave_otziv_block">
	<div form="true" class="wForm" data-ajax="review">
		<div class="title"><?php echo __('оставь свой отзыв'); ?></div>
		<div class="wFormRow">
			<input type="text" data-name="name" name="name" placeholder="<?php echo __('Имя'); ?>" data-rule-bykvu="true"
				   data-rule-minlength="2" required="">
			<label><?php echo __('Имя'); ?></label>
		</div>
		<div class="wFormRow">
			<input type="email" data-name="email" name="email" data-rule-email="true" placeholder="E-mail"
				   data-rule-minlength="2" data-rule-required="true">
			<label>E-mail</label>
		</div>
		<div class="wFormRow">
			<textarea name="text" data-name="text" placeholder="<?php echo __('Ваш отзыв'); ?>" required=""></textarea>
			<label><?php echo __('Ваш отзыв'); ?></label>
		</div>
        <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
		<?php if (array_key_exists('token', $_SESSION)): ?>
			<input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
		<?php endif; ?>
		<div class="tal">
			<button class="wSubmit enterReg_btn"><?php echo __('Отправить'); ?></button>
		</div>
	</div>
</div>