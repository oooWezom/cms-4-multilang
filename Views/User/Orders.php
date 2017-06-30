<?php use Core\HTML; ?>
<?php if (!count($orders)): ?>
    <p><?php echo __('У Вас еще нет заказов!'); ?></p>
<?php else: ?>
    <div class="history wTxt">
        <table class="table-zebra myStyles">
            <tr>
                <th></th>
                <th><?php echo __('Дата'); ?></th>
                <th><?php echo __('Адресат'); ?></th>
                <th><?php echo __('Сумма заказа'); ?></th>
                <th><?php echo __('Статус'); ?></th>
                <th></th>
            </tr>
            <?php foreach ($orders as $obj): ?>
                <tr>
                    <td>
                        <a href="<?php echo HTML::link('account/orders/' . $obj->id); ?>">№ <?php echo $obj->id ?></a>
                    </td>
                    <td><?php echo date('d.m.Y', $obj->created_at); ?></td>
                    <td><?php echo $obj->name; ?></td>
                    <td><?php echo $obj->amount; ?> <span>грн</span></td>
                    <td><?php echo $statuses[$obj->status]; ?></td>
                    <td><a href="<?php echo HTML::link('account/print/' . $obj->id); ?>" target="_blank"><?php echo __('Печать'); ?></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
<?php endif ?>