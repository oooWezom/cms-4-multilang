<?php use Core\HTML; ?>
<div class="wTxt" style="position: relative;">
    <a href="<?php echo HTML::link('account/print/' . $obj->id); ?>" target="_blank"
       class="userOrderPrint"><?php echo __('Распечатать'); ?></a>
    <div class="userOrderInfoBlock">
        <div class="userOrderTitle"><?php echo __('Статус'); ?></div>
        <div class="userOrderValue"><?php echo $statuses[$obj->status]; ?></div>
    </div>
    <div class="userOrderInfoBlock">
        <div class="userOrderTitle"><?php echo __('Итоговая сумма'); ?></div>
        <div class="userOrderValue"><?php echo (int)$obj->amount; ?> <?php echo __('грн'); ?>.</div>
    </div>
    <div class="userOrderInfoBlock">
        <div class="userOrderTitle"><?php echo __('Способ оплаты'); ?></div>
        <div class="userOrderValue">
            <?php echo $payment[$obj->payment]; ?>
        </div>
    </div>
    <div class="userOrderInfoBlock">
        <div class="userOrderTitle"><?php echo __('Доставка'); ?></div>
        <div class="userOrderValue">
            <?php echo $delivery[$obj->delivery] . ($obj->delivery == 2 ? ', ' . $obj->number : ''); ?>
        </div>
    </div>
    <div class="userOrderInfoBlock">
        <div class="userOrderTitle"><?php echo __('Адресат'); ?></div>
        <div class="userOrderValue">
            <?php echo $obj->name; ?>
        </div>
    </div>
    <div class="userOrderInfoBlock">
        <div class="userOrderTitle"><?php echo __('Телефон'); ?></div>
        <div class="userOrderValue">
            <?php echo $obj->phone; ?>
        </div>
    </div>

    <div class="history wTxt onlyOneOrder">
        <table class="table-zebra myStyles">
            <tr>
                <th><?php echo __('Фото'); ?></th>
                <th><?php echo __('Товар'); ?></th>
                <th><?php echo __('Цена'); ?></th>
                <th><?php echo __('Количество'); ?></th>
                <th><?php echo __('Итог'); ?></th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td>
                        <?php if (is_file(HOST . HTML::media('images/catalog/small/' . $item->image, false))): ?>
                            <a href="<?php echo HTML::link($item->alias . '/p' . $item->id); ?>" target="_blank">
                                <img src="<?php echo HTML::media('images/catalog/small/' . $item->image); ?>"/>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="userOrderNotCenterTD">
                        <?php if ($item->id): ?>
                            <a href="<?php echo HTML::link($item->alias . '/p' . $item->id); ?>" target="_blank">
                                <?php echo $item->name; ?>
                            </a>
                        <?php else: ?>
                            <i>( Удален )</i>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php echo (int)$item->price; ?> <?php echo __('грн'); ?>
                    </td>
                    <td>
                        <?php echo (int)$item->count; ?> <?php echo __('шт'); ?>
                    </td>
                    <td>
                        <?php echo (int)$item->count * (int)$item->price; ?> <?php echo __('грн'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>