<?php 
	use Core\HTML;
	use Core\Arr;
?>
<li><a href="<?php echo HTML::link('gallery'); ?>"><?php echo __('Галерея'); ?></a>
	<?php if (sizeof(Arr::get($links,'gallery_list'))): ?>
	<ul>
		<?php foreach ($links['gallery_list'] as $obj): ?>
		<li><a href="<?php echo HTML::link('gallery/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</li>