<?php
use Core\HTML;
use Core\Arr;

?>
<table align="left" border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th><?php echo __('Наименование')?></th>
        <th><?php echo __('Цена')?></th>
        <th><?php echo __('Количество')?></th>
        <th><?php echo __('Итог')?></th>
    </tr>
    </thead>
    <tbody>
    <?php $amount = 0; ?>
    <?php foreach ($cart as $item): ?>
        <?php $obj = Arr::get($item, 'obj'); ?>
        <?php $amt = (int)Arr::get($item, 'count', 1) * (int)$obj->cost; ?>
        <?php $amount += $amt; ?>
        <tr>
            <td>
                <?php if (is_file(HOST . HTML::media('images/catalog/medium/' . $obj->image, false))): ?>
                    <img src="<?php echo HTML::media('images/catalog/medium/' . $obj->image, true); ?>"
                         width="80"/>
                <?php else: ?>
                    <img src="<?php echo HTML::media('pic/no-photo.png', true); ?>" width="80"/>
                <?php endif; ?>
            </td>
            <td><?php echo $obj->name; ?></td>
            <td><?php echo (int)$obj->cost; ?> <?php echo __('грн.')?></td>
            <td><?php echo (int)Arr::get($item, 'count', 1); ?></td>
            <td><?php echo $amt; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4"><b><?php echo __('ВСЕГО:')?></b></th>
        <th><?php echo $amount; ?> <?php echo __('грн.')?></th>
    </tr>
    </tfoot>
</table>