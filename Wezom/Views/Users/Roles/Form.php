<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Общая информация'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[name]',
                            'value' => $obj->name,
                            'class' => 'valid',
                        ], __('Название')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[description]',
                            'value' => $obj->description,
                        ], __('Описание')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Доступ к разделам'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <?php foreach( \Core\Config::get('access') AS $el ): ?>
                        <?php $ac = \Core\Arr::get($access, $el['controller'], 'no'); ?>
                        <div class="form-group">
                            <label class="control-label"><?php echo $el['name']; ?></label>
                            <div class="clear"></div>
                            <label class="checkerWrap-inline">
                                <?php echo \Forms\Builder::radio($ac == 'no' ? true : false, [
                                    'name' => $el['controller'],
                                    'value' => 'no',
                                ]); ?>
                                <?php echo __('Нет прав'); ?>
                            </label>
                            <?php if( \Core\Arr::get($el, 'view', 1) ): ?>
                                <label class="checkerWrap-inline">
                                    <?php echo \Forms\Builder::radio($ac == 'view' ? true : false, [
                                        'name' => $el['controller'],
                                        'value' => 'view',
                                    ]); ?>
                                    <?php echo __('Только просмотр'); ?>
                                </label>
                            <?php endif; ?>
                            <?php if( \Core\Arr::get($el, 'edit', 1) ): ?>
                                <label class="checkerWrap-inline">
                                    <?php echo \Forms\Builder::radio($ac == 'edit' ? true : false, [
                                        'name' => $el['controller'],
                                        'value' => 'edit',
                                    ]); ?>
                                    <?php echo __('Просмотр и редактирование'); ?>
                                </label>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>