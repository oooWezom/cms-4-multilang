<?php use Core\View; ?>
<?php if (!count($result)): ?>
    <p><?php echo __('По заданым параметрам товаров нет'); ?></p>
<?php else: ?>
    <ul>
        <?php foreach ($result as $obj): ?>
            <?php echo View::tpl(['obj' => $obj], 'Catalog/ListItemTemplate'); ?>
        <?php endforeach; ?>
    </ul>
    <?php echo $pager; ?>
<?php endif; ?>