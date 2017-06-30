<?php 
	use Core\HTML;
	use Core\Arr;
?>
<li><a href="<?php echo HTML::link('blog'); ?>"><?php echo __('Блог'); ?></a>
	<?php if (sizeof(Arr::get($links,'blog_rubrics'))): ?>
	<ul>
		<?php foreach ($links['blog_rubrics'] as $obj): ?>
		<li><a href="<?php echo HTML::link('blog/rubrica/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	<?php if (sizeof(Arr::get($links,'blog_list'))): ?>
	<ul>
		<?php foreach ($links['blog_list'] as $obj): ?>
		<li><a href="<?php echo HTML::link('blog/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</li>