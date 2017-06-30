<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-<?php echo $obj->id ? 7 : 12; ?>">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Личные данные'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <?php echo \Forms\Builder::bool($obj ? $obj->status : 1); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::select($roles,
                            $obj->role_id, [
                                'name' => 'FORM[role_id]',
                                'class' => 'valid',
                            ], __('Роль')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[name]',
                            'value' => $obj->name,
                            'class' => 'valid',
                        ], __('Имя')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[login]',
                            'value' => $obj->login,
                            'class' => 'valid',
                        ], __('Логин')); ?>
                    </div>
                    <div class="form-group">
                        <?php if(\Core\Route::param('id')): ?>
                            <?php echo \Forms\Builder::password([
                                'name' => 'password',
                            ], [
                                'text' => __('Пароль'),
                                'tooltip' => __('Если нет необходимости менять пароль, просто оставьте это поле пустым, тогда он не изменится'),
                            ]); ?>
                        <?php else: ?>
                            <?php echo \Forms\Builder::password([
                                'name' => 'password',
                                'class' => 'valid',
                            ], __('Пароль')); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if( $obj->id ): ?>
        <div class="col-md-5">
            <div class="widget">
                <div class="widgetHeader myWidgetHeader">
                    <div class="widgetTitle">
                        <i class="fa fa-reorder"></i>
                        <?php echo __('Статистика'); ?>
                    </div>
                </div>
                <div class="pageInfo alert alert-info">
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Опубликован'); ?></strong></div>
                        <div class="col-md-6"><?php echo $obj->status == 1 ? 'Да' : 'Нет'; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Дата создания аккаунта'); ?></strong></div>
                        <div class="col-md-6"><?php echo $obj->created_at ? date('d.m.Y H:i:s', $obj->created_at) : '---'; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Дата последней авторизации'); ?></strong></div>
                        <div class="col-md-6"><?php echo $obj->last_login ? date('d.m.Y H:i:s', $obj->last_login) : '---'; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Количество авторизаций на сайте'); ?></strong></div>
                        <div class="col-md-6"><?php echo (int) $obj->logins; ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php echo \Forms\Form::close(); ?>