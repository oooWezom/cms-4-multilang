<?php echo \Forms\Builder::open(['data-action' => 'users']); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-6">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div id="itemPlace" class="row-border">
                        <?php if( $item ): ?>
                            <div class="someBlock">
                                <a target="_blank" href="/wezom/users/edit/<?php echo $item->id; ?>"><?php echo $item->last_name.' '.$item->name.' '.$item->middle_name; ?></a>
                            </div>
                            <div class="someBlock"><b><?php echo __('E-Mail'); ?>:</b> <a href="mailto:<?php echo $item->email; ?>"><?php echo $item->email; ?></a></div>
                            <div class="someBlock"><b><?php echo __('Номер телефона'); ?>:</b> <?php echo $item->phone; ?></div>
                        <?php else: ?>
                            <p class="relatedMessage"><?php echo __('Если нужно привязать конкретного пользователя под заказ, найдите и выберите его в списке справа!'); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="clear"></div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'last_name',
                            'value' => $obj->last_name,
                            'class' => 'valid',
                        ], __('Фамилия')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'name',
                            'value' => $obj->name,
                            'class' => 'valid',
                        ], __('Имя')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'middle_name',
                            'value' => $obj->middle_name,
                        ], __('Отчество')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'phone',
                            'value' => $obj->phone,
                            'class' => 'valid',
                        ], __('Номер телефона')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'email',
                            'value' => $obj->email,
                            'class' => 'valid email',
                        ], __('E-Mail')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::select($payment,
                            $obj->payment, [
                                'name' => 'payment',
                            ], __('Способ оплаты')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::select($delivery,
                            $obj->delivery, [
                                'name' => 'delivery',
                            ], __('Способ доставки')); ?>
                    </div>
                    <?php echo \Forms\Builder::hidden([
                        'name' => 'user_id',
                        'value' => $item->id,
                        'id' => 'orderItemId',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="widget box loadedBox" id="orderItemsBlock">
            <div class="widgetHeader myWidgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-file"></i>
                    <?php echo __('Указать пользователя'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div id="orderItemsBlock" class="usersSearchBlock">
                        <div class="form-group" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <?php echo \Forms\Builder::input([
                                    'name' => 'search',
                                    'placeholder' => __('Начните вводить название ФИО или ID нужного пользователя'),
                                ]); ?>
                            </div>
                        </div>
                        <div class="widgetContent" style="min-height: 150px;">
                            <div id="orderItemsList" class="form-vertical row-border" data-id="<?php echo \Core\Route::param('id'); ?>" data-limit="16">
                                <p class="relatedMessage"><?php echo __('Начните писать ФИО или ID пользователя в поле для ввода расположенном выше. После чего на этом месте появится список отфильтрованных записей'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>
