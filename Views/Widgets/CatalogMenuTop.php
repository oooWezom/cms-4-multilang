<?php
use Core\Route;
use Core\HTML;

$controller = Route::controller();
$action = Route::action();

?>
<ul>
    <li <?php echo ($controller == 'catalog' and in_array($action, ['index', 'list', 'groups', 'item'])) ? 'class="active_li_top"' : ''; ?>>
        <a href="<?php echo HTML::link('products'); ?>">
            <div>каталог</div>
        </a>
        <div class="list top_list">
            <ul>
                <?php foreach ($result[0] as $main): ?>
                    <li>
                        <a href="<?php echo HTML::link('products/' . $main->alias); ?>"
                           class="title_li"><?php echo $main->name; ?></a>
                        <?php if (isset($result[$main->id])): ?>
                            <ul>
                                <?php foreach ($result[$main->id] as $obj): ?>
                                    <li>
                                        <a href="<?php echo HTML::link('products/' . $obj->alias); ?>"><?php echo $obj->name; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </li>
    <li <?php echo ($controller == 'catalog' and $action == 'new') ? 'class="active_li_top"' : ''; ?>><a
                href="<?php echo HTML::link('new'); ?>">
            <div><?php echo __('новинки'); ?></div>
        </a>
    </li>
    <li <?php echo ($controller == 'catalog' and $action == 'popular') ? 'class="active_li_top"' : ''; ?>><a
                href="<?php echo HTML::link('popular'); ?>">
            <div><?php echo __('популярные'); ?></div>
        </a>
    </li>
    <li <?php echo ($controller == 'catalog' and $action == 'sale') ? 'class="active_li_top"' : ''; ?>><a
                href="<?php echo HTML::link('sale'); ?>">
            <div><?php echo __('акции'); ?></div>
        </a>
    </li>
    <li <?php echo $controller == 'brands' ? 'class="active_li_top"' : ''; ?>><a
                href="<?php echo HTML::link('brands'); ?>">
            <div><?php echo __('бренды'); ?></div>
        </a>
    </li>
</ul>