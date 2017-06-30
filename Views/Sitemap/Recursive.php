<?php
use Core\HTML;
use Core\View;

?>
<?php if (isset($result[$cur]) and count($result[$cur])): ?>
    <ul>
        <?php foreach ($result[$cur] as $obj): ?>
            <li><a href="<?php echo HTML::link($add . '/' . $obj->alias); ?>"><?php echo $obj->name; ?></a>
                <?php echo View::tpl(['result' => $result, 'cur' => $obj->id, 'add' => $add, 'items' => $items], 'Sitemap/Recursive'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php elseif (isset($items[$cur]) and count($items[$cur])): ?>
	<ul>
	<?php foreach ($items[$cur] as $obj): ?>
		<li><a href="<?php echo HTML::link($obj->alias.'/p'.$obj->id); ?>"><?php echo $obj->name; ?></a></li>
	<?php endforeach ;?>
	</ul>
<?php endif; ?>