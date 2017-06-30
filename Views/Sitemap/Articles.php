<?php 
	use Core\HTML;
	use Core\Arr;
?>
<li><a href="<?php echo HTML::link('articles'); ?>"><?php echo __('Статьи'); ?></a>
	<?php if (sizeof(Arr::get($links,'articles_list'))): ?>
	<ul>
		<?php foreach ($links['articles_list'] as $obj): ?>
		<li><a href="<?php echo HTML::link('articles/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</li>