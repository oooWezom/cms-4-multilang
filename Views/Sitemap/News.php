<?php 
	use Core\HTML;
	use Core\Arr;
?>
<li><a href="<?php echo HTML::link('news'); ?>"><?php echo __('Новости'); ?></a>
	<?php if (sizeof(Arr::get($links,'news_list'))): ?>
	<ul>
		<?php foreach ($links['news_list'] as $obj): ?>
		<li><a href="<?php echo HTML::link('news/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</li>