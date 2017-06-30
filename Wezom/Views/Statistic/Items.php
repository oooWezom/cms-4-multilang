<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader" style="padding-bottom: 10px;">
                <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/statistic/items']); ?>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'artikul',
                            'value' => Core\Arr::get($_GET, 'artikul', NULL),
                        ], __('Артикул')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::select('<option value="">' . __('Все') . '</option>'.$tree,
                            NULL, [
                                'name' => 'parent_id',
                            ], __('Группа')); ?>
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
                                <a href="/wezom/statistic/items">
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
                            <th><?php echo __('Артикул'); ?></th>
                            <th><?php echo __('Наименование'); ?></th>
                            <th><?php echo __('Стоимость'); ?></th>
                            <th><?php echo __('Просмотров'); ?></th>
                            <th><?php echo __('Заказов'); ?></th>
                            <th><?php echo __('Поледний заказ'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $result as $obj ): ?>
                            <tr data-id="<?php echo $obj->id; ?>">
                                <td><a href="<?php echo \Core\HTML::link('wezom/items/edit/'.$obj->id); ?>"><?php echo $obj->artikul; ?></a></td>
                                <td><a href="<?php echo \Core\HTML::link('wezom/items/edit/'.$obj->id); ?>"><?php echo $obj->name; ?></a></td>
                                <td><?php echo (int) $obj->cost; ?> грн</td>
                                <td><?php echo (int) $obj->views; ?></td>
                                <td><?php echo (int) $obj->orders_count; ?> / <?php echo (int) $obj->orders_count_items; ?> шт</td>
                                <td>
                                    <?php if($obj->orders_last_id): ?>
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