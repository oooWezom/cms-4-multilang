<div class="rowSection clearFix row-bg">
    <div class="col-sm-6 col-md-4">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual green"><i class="fa fa-calendar"></i></div>
                <div class="title"><?php echo __('Заказ создан'); ?></div>
                <div class="value"><?php echo date('d.m.Y H:i', $obj->created_at); ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual cyan"><i class="fa fa-money"></i></div>
                <div class="title"><?php echo __('Сумма заказа'); ?></div>
                <div class="value"><?php echo (int) $obj->amount; ?> грн</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual yellow"><i class="fa fa-dropbox"></i></div>
                <div class="title"><?php echo __('Товаров в заказе'); ?></div>
                <div class="value"><?php echo (int) $obj->count; ?></div>
            </div>
        </div>
    </div>
</div>
<div class="rowSection column-2">
    <div class="col-md-7">
        <div class="widget">
            <div class="widgetHeader"><div class="widgetTitle"><i class="fa fa-credit-card"></i><?php echo __('Заказ'); ?> <span class="label label-primary">№ <?php echo $obj->id; ?></span> <a href="/wezom/<?php echo Core\Route::controller(); ?>/print/<?php echo $obj->id; ?>" target="_blank"><?php echo __('Распечатать'); ?></a></div></div>
            <div class="widgetContent">
                <div class="widget box">
                    <div class="widgetHeader"><div class="widgetTitle"><i class="fa fa-clock-o"></i><?php echo __('Статус'); ?></div></div>
                    <div class="widgetContent changeStatusForm" style="padding-top: 0;" data-ajax="orders/orderStatus">
                        <div class="rowSection">
                            <div class="table-footer clearFix">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <?php echo \Forms\Builder::select($statuses, $obj->status, ['name' => 'status']); ?>
                                        <span class="input-group-btn">
                                            <div class="col-md-12">
                                                <?php echo \Forms\Form::button('Обновить', ['type' => 'button', 'class' => 'btn btn-primary']); ?>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget box">
                    <div class="widgetHeader"><div class="widgetTitle"><i class="fa fa-user"></i><?php echo __('Заказ на имя'); ?></div></div>
                    <div class="widgetContent" data-ajax="orders/orderUser">
                        <?php echo \Forms\Form::open(['class' => 'form-vertical row-border']); ?>
                            <div class="form-group">
                                <label class="control-label col-md-2"><?php echo __('Фамилия'); ?></label>
                                <div class="col-md-10">
                                    <?php echo \Forms\Builder::input([
                                        'name' => 'last_name',
                                        'value' => $obj->last_name,
                                    ]); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2"><?php echo __('Имя'); ?></label>
                                <div class="col-md-10">
                                    <?php echo \Forms\Builder::input([
                                        'name' => 'name',
                                        'value' => $obj->name,
                                    ]); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2"><?php echo __('Отчество'); ?></label>
                                <div class="col-md-10">
                                    <?php echo \Forms\Builder::input([
                                        'name' => 'middle_name',
                                        'value' => $obj->middle_name,
                                    ]); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2"><?php echo __('Телефон'); ?></label>
                                <div class="col-md-10">
                                    <?php echo \Forms\Builder::input([
                                        'name' => 'phone',
                                        'value' => $obj->phone,
                                    ]); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2"><?php echo __('E-Mail'); ?></label>
                                <div class="col-md-10">
                                    <?php echo \Forms\Builder::input([
                                        'name' => 'email',
                                        'value' => $obj->email,
                                    ]); ?>
                                </div>
                            </div>
                            <div class="form-actions textright">
                                <?php echo \Forms\Form::button(__('Обновить'), ['type' => 'button', 'class' => 'btn btn-primary']); ?>
                            </div>
                        <?php echo \Forms\Form::close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <?php if( $user->id ): ?>
            <div class="widget">
                <div class="widgetHeader">
                    <div class="widgetTitle">
                        <i class="fa fa-user"></i><?php echo __('Пользователь'); ?>
                        <?php if( $user->name || $user->last_name || $user->middle_name ): ?>
                            <a href="<?php echo \Core\HTML::link('wezom/users/edit/'.$user->id); ?>" class="label label-primary" target="_blank"><?php echo $user->last_name.' '.$user->name.' '.$user->middle_name; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="widgetContent">
                    <div class="widget box">
                        <div class="widgetContent no-padding">
                            <div class="pageInfo alert alert-info">
                                <?php if( $user->phone ): ?>
                                    <div class="rowSection">
                                        <div class="col-md-6"><strong><i class="fa fa-envelope"></i> <?php echo __('Номер телефона'); ?></strong></div>
                                        <div class="col-md-6"><a href="tel:<?php echo preg_replace('/[^0-9]*/', '', $user->phone); ?>"><?php echo $user->phone; ?></a></div>
                                    </div>
                                <?php endif; ?>
                                <?php if( $user->email ): ?>
                                    <div class="rowSection">
                                        <div class="col-md-6"><strong><i class="fa fa-envelope"></i> <?php echo __('E-Mail'); ?></strong></div>
                                        <div class="col-md-6"><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></div>
                                    </div>
                                <?php endif; ?>
                                <?php if( $user->created_at ): ?>
                                    <div class="rowSection">
                                        <div class="col-md-6"><strong><i class="fa fa-calendar"></i> <?php echo __('Аккаунт зарегистрирован'); ?></strong></div>
                                        <div class="col-md-6"><?php echo date('d.m.Y H:i', $user->created_at); ?></div>
                                    </div>
                                <?php endif; ?>
                                <div class="rowSection">
                                    <div class="col-md-6"><strong><i class="fa fa-tags"></i> <?php echo __('Выполненых заказов'); ?></strong></div>
                                    <div class="col-md-6">
                                        <span class="label label-primary"><?php echo (int) $user->orders; ?></span>
                                    </div>
                                </div>
                                <div class="rowSection">
                                    <div class="col-md-6"><strong><i class="fa fa-tags"></i> <?php echo __('Количество товаров в выполненых заказах'); ?></strong></div>
                                    <div class="col-md-6">
                                        <span class="label label-primary"><?php echo (int) $user->count_items; ?></span>
                                    </div>
                                </div>
                                <div class="rowSection">
                                    <div class="col-md-6"><strong><i class="fa fa-money"></i> <?php echo __('Сумма выполненых заказов'); ?></strong></div>
                                    <div class="col-md-6">
                                        <span class="label label-success"><?php echo (int) $user->amount; ?> грн.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="widget box">
            <div class="widgetHeader"><div class="widgetTitle"><i class="fa fa-clock-o"></i><?php echo __('Доставка'); ?></div></div>
            <div class="widgetContent" style="padding-top: 0;" data-ajax="orders/orderDelivery">
                <div class="rowSection">
                    <div class="table-footer clearFix">
                        <div class="col-md-12">
                            <div class="input-group">
                                <?php echo \Forms\Builder::select($delivery, $obj->delivery, ['name' => 'delivery']); ?>
                                <span class="input-group-btn">
                                    <div class="col-md-12">
                                        <?php echo \Forms\Form::button('Обновить', ['type' => 'button', 'class' => 'btn btn-primary']); ?>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget box">
            <div class="widgetHeader"><div class="widgetTitle"><i class="fa fa-clock-o"></i><?php echo __('Оплата'); ?></div></div>
            <div class="widgetContent" style="padding-top: 0;" data-ajax="orders/orderPayment">
                <div class="rowSection">
                    <div class="table-footer clearFix">
                        <div class="col-md-12">
                            <div class="input-group">
                                <?php echo \Forms\Builder::select($payment, $obj->payment, ['name' => 'payment']); ?>
                                <span class="input-group-btn">
                                    <div class="col-md-12">
                                        <?php echo \Forms\Form::button('Обновить', ['type' => 'button', 'class' => 'btn btn-primary']); ?>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="rowSection">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle"><i class="fa fa-shopping-cart"></i><?php echo __('Позиции'); ?> <span class="label label-primary" id="orderPositionsCount"><?php echo sizeof($cart); ?></span></div>
            </div>
            <div class="widgetContent">
                <table class="table tableOrderItems" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="1%" class="hidden-xs"></th>
                            <th><?php echo __('Товар'); ?></th>
                            <th><?php echo __('Цена'); ?></th>
                            <th><?php echo __('Кол-во'); ?></th>
                            <th><?php echo __('Итого'); ?></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsList">
                        <?php $amount = 0; ?>
                        <?php foreach ($cart as $item): ?>
                            <?php $amount += $item->price * $item->count; ?>
                            <tr data-order-item-id="<?php echo $item->order_item_id; ?>">
                                <td class="hidden-xs">
                                    <?php if (is_file(HOST . Core\HTML::media('images/catalog/small/' . $item->image, false))): ?>
                                        <a href="/wezom/items/edit/<?php echo $item->id; ?>" class="tableOrderItemsThumb imageThumb" target="_blank">
                                            <img src="<?php echo Core\HTML::media('images/catalog/small/' . $item->image); ?>" class="w100">
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($item->id) : ?>
                                        <a href="/wezom/items/edit/<?php echo $item->id; ?>" target="_blank">
                                            <?php echo $item->name . ( $item->size_name ? ', ' . $item->size_name : '' ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="italic gray"><?php echo __('Товар удален')?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="tableOrderItemsCost middle"><?php echo $item->price; ?></div>
                                    <div class="tableOrderItemsType middle">грн</div>
                                </td>
                                <td>
                                    <div class="input-width-mini">
                                        <?php
                                        echo \Forms\Builder::input([
                                            'value' => $item->count,
                                            'class' => 'spinner count',
                                            'step' => 1,
                                            'min' => 0,
                                            'max' => '',
                                        ]);
                                        ?>
                                        <?php
                                        echo \Forms\Builder::hidden([
                                            'value' => $item->id,
                                            'class' => 'catalog_id',
                                        ]);
                                        ?>
                                        <?php
                                        echo \Forms\Builder::hidden([
                                            'value' => (int) $item->size_id,
                                            'class' => 'size_id',
                                        ]);
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="tableOrderItemsSumm middle"></div>
                                    <div class="tableOrderItemsType middle">грн</div>
                                </td>
                                <td class="nav-column">
                                    <ul class="table-controls">
                                        <li>
                                            <a class="bs-tooltip dropdownToggle liTipLink" href="javascript:void(0);" title="<?php echo __('Управление'); ?>"><i class="fa fa-cog size14"></i></a>
                                            <ul class="dropdownMenu pull-right">
                                                <li>
                                                    <a title="<?php echo __('Редактировать товар'); ?>" href="/wezom/items/edit/<?php echo $item->id; ?>" target="_blank"><i class="fa fa-pencil"></i> <?php echo __('Редактировать товар'); ?></a>
                                                </li>
                                                <li>
                                                    <a title="<?php echo __('Посмотреть товар'); ?>" href="<?php echo \Core\HTML::link($item->alias . '/p' . $item->id); ?>" target="_blank"><i class="fa fa-fixed-width">&#xf06e;</i> <?php echo __('Посмотреть товар'); ?></a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" title="<?php echo __('Удалить позицию'); ?>" class="orderPositionDelete"><i class="fa fa-trash-o text-danger"></i> <?php echo __('Удалить позицию'); ?></a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="rowSection">
                    <div class="table-footer clearFix">
                        <div class="col-md-12 textright">
                            <button class="btn btn-info <?php echo!$obj->changed ? 'hide' : NULL; ?>" type="button" id="sendEmail"><?php echo __('Отправить уведомление об изменении заказа'); ?></button>
                            <button class="btn btn-default" type="button" href="/wezom/orders/add_position/<?php echo $obj->id; ?>"><?php echo __('Добавить товар'); ?></button>
                            <button class="btn btn-primary" type="button" id="orderItems"><?php echo __('Сохранить'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="rowSection">
    <div class="col-md-7"></div>
    <div class="col-md-5">
        <div class="widget">
            <div class="widgetContent no-padding">
                <table class="table table-hover ">
                    <tbody>
                        <tr>
                            <td align="right"><strong><?php echo __('Итого'); ?></strong></td>
                            <td align="right" id="orderAmount"><span><?php echo $amount; ?></span> грн</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<span id="orderParameters" data-id="<?php echo $obj->id; ?>"></span>

<script>
    var checkboxStart = function( el ) {
        var parent = el.parent();
        if(parent.is('label')){
            if(el.prop('checked')){
                parent.addClass('checked');
            } else {
                parent.removeClass('checked');
            }
        }
    };;
    var currentStatus = $('select[name="status"]').val();
    $('select[name="status"]').on('change', function () {
        var val = $(this).val();
        if((val == 1 || val == 3) && currentStatus != val) {
            $(this).closest('div.col-md-12').find('.checkedToRemove').remove();
            var checked = '<label class="checkerWrap checkedToRemove ckbxWrap checked"><input value="1" name="sendEmail" type="checkbox" checked><span class=""><?php echo __('Отправить уведомление на почту заказчика'); ?></span></label>';
            $(checked).appendTo($(this).closest('div.col-md-12'));
            var ckd = $(this).closest('div.col-md-12').find('.checkerWrap input[type="checkbox"]');
            checkboxStart(ckd);
            ckd.on('change',function(){ checkboxStart($(this)); });
        } else {
            $(this).closest('div.col-md-12').find('.checkerWrap').remove();
        }
    });
    $('.changeStatusForm button').on('click', function() {
        currentStatus = $('select[name="status"]').val();
    });
</script>