<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader" style="padding-bottom: 10px;">
                <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/statistic/users']); ?>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'name',
                            'value' => Core\Arr::get($_GET, 'name', NULL),
                        ], __('Имя')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'last_name',
                            'value' => Core\Arr::get($_GET, 'last_name', NULL),
                        ], __('Фамилия')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'middle_name',
                            'value' => Core\Arr::get($_GET, 'middle_name', NULL),
                        ], __('Отчество')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'email',
                            'value' => Core\Arr::get($_GET, 'email', NULL),
                        ], __('E-Mail')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'phone',
                            'value' => Core\Arr::get($_GET, 'phone', NULL),
                        ], __('Номер телефона')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php $options = []; ?>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php $number = $i * Core\Config::get('basic.limit_backend'); ?>
                            <?php $options[$number] = $number; ?>
                        <?php endfor; ?>
                        <?php echo \Forms\Builder::select($options, Core\Arr::get($_GET, 'limit', Core\Config::get('basic.limit_backend')), [
                            'name' => 'limit',
                        ], __('Выводить по')); ?>
                    </div>
                    <div class="col-md-1">
                        <label class="control-label" style="height:16px;">&nbsp</label>
                        <?php echo \Forms\Form::submit([
                            'class' => 'btn btn-primary',
                            'value' => __('Подобрать'),
                        ]); ?>
                    </div>
                    <div class="col-md-1 stable105">
                        <label class="control-label" style="height:22px;"></label>
                        <div class="">
                            <div class="controls">
                                <a href="/wezom/statistic/users">
                                    <i class="fa fa-refresh"></i>
                                    <span class="hidden-xx"><?php echo __('Сбросить'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php echo \Forms\Form::close(); ?>
            </div>

            <div class="widget">
                <div class="widgetContent">
                    <table class="table table-striped table-hover checkbox-wrap ">
                        <thead>
                        <tr>
                            <th><?php echo __('Клиент'); ?></th>
                            <th><?php echo __('E-Mail'); ?></th>
                            <th><?php echo __('Номер телефона'); ?></th>
                            <th><?php echo __('Дата регистрации'); ?></th>
                            <th><?php echo __('Кол-во заказов'); ?></th>
                            <th><?php echo __('Сумма по заказам'); ?></th>
                            <th><?php echo __('Поледний заказ'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $result as $obj ): ?>
                            <tr data-id="<?php echo $obj->id; ?>">
                                <?php $name = trim($obj->last_name.' '.$obj->name.' '.$obj->middle_name); ?>
                                <?php $name = $name ?: '#'.$obj->id; ?>
                                <td>
                                    <?php if($obj->partner): ?>
                                        <a href="/wezom/partners/edit/<?php echo $obj->id; ?>"><?php echo $name.' ('.$obj->agreement_number.')'; ?></a>
                                    <?php else: ?>
                                        <a href="/wezom/users/edit/<?php echo $obj->id; ?>"><?php echo $name; ?></a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if( $obj->email ): ?>
                                        <a href="mailto:<?php echo $obj->email; ?>" target="_blank"><?php echo $obj->email; ?></a>
                                    <?php else: ?>
                                        ----
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if( $obj->phone ): ?>
                                        <a href="tel:<?php echo preg_replace('/[^0-9]*/', '', $obj->phone); ?>" target="_blank"><?php echo $obj->phone; ?></a>
                                    <?php else: ?>
                                        ----
                                    <?php endif ?>
                                </td>
                                <td><?php echo $obj->created_at ? date( 'd.m.Y', $obj->created_at ) : __('Неизвестно'); ?></td>
                                <td>
                                    <?php if($obj->orders_count): ?>
                                        <a href="<?php echo \Core\HTML::link('wezom/orders/index?uid='.$obj->id); ?>"><?php echo (int) $obj->orders_count; ?></a>
                                    <?php else: ?>
                                        <?php echo (int) $obj->orders_count; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo (int) $obj->orders_amount; ?> грн</td>
                                <td>
                                    <?php if($obj->orders_last_id && $obj->orders_last): ?>
                                        <a href="<?php echo \Core\HTML::link('wezom/orders/edit/'.$obj->orders_last_id); ?>"><?php echo date('d.m.Y, H:i', $obj->orders_last); ?></a>
                                    <?php else: ?>
                                        ----
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>