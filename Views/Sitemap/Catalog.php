<?php 
	use Core\HTML;
	use Core\Arr;
	use Core\View;
?>
<li><a href="<?php echo HTML::link('products'); ?>"><?php echo __('Каталог'); ?></a>
	<ul>
	<?php foreach ($result[$obj->id] as $item): ?>
		<?php if ($item->alias == 'catalog_groups' and sizeof(Arr::get($links,'catalog_groups'))): ?>
			<?php echo View::tpl(['result' => $links['catalog_groups'], 'cur' => 0, 'add' => 'products', 'items' => Arr::get($links,'catalog_items')], 'Sitemap/Recursive'); ?>
		<?php elseif ($item->alias == 'catalog_brands'): ?>
			<li><a href="<?php echo HTML::link('brands'); ?>"><?php echo __('Производители'); ?></a>
				<?php if (isset($links['brands_list']) and sizeof($links['brands_list'])): ?>
				<ul>
					<?php foreach ($links['brands_list'] as $brand): ?>
					<li><a href="<?php echo HTML::link('brands/'.$brand->alias); ?>"><?php echo $brand->name; ?></a></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</li>
		<?php elseif($item->alias == 'catalog_sale'): ?>
			<li><a href="<?php echo HTML::link('sale'); ?>"><?php echo __('Распродажа'); ?></a></li>
		<?php elseif($item->alias == 'catalog_new'): ?>
			<li><a href="<?php echo HTML::link('new'); ?>"><?php echo __('Новинки'); ?></a></li>
		<?php elseif($item->alias == 'catalog_popular'): ?>
			<li><a href="<?php echo HTML::link('popular'); ?>"><?php echo __('Популярное'); ?></a></li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
</li>