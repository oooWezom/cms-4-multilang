<?php echo \Forms\Builder::open(['data-id' => $obj->id]); ?>
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
                        <div class="rowSection">
                            <div class="col-md-4 form-group">
                                <?php echo \Forms\Builder::input([
                                    'name' => 'FORM[last_name]',
                                    'value' => $obj->last_name,
                                ], __('Фамилия')); ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <?php echo \Forms\Builder::input([
                                    'name' => 'FORM[name]',
                                    'value' => $obj->name,
                                    'class' => 'valid',
                                ], __('Имя')); ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <?php echo \Forms\Builder::input([
                                    'name' => 'FORM[middle_name]',
                                    'value' => $obj->middle_name,
                                ], __('Отчество')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="rowSection">
                            <div class="col-md-6 form-group">
                                <?php echo \Forms\Builder::input([
                                    'name' => 'FORM[email]',
                                    'value' => $obj->email,
                                    'class' => 'valid',
                                ], __('E-Mail')); ?>
                            </div>
                            <?php if( \Core\Route::param('id') ): ?>
                                <div class="col-md-6 form-group">
                                    <?php echo \Forms\Builder::password([
                                        'name' => 'password',
                                    ], [
                                        'text' => __('Пароль'),
                                        'tooltip' => __('Если нет необходимости менять пароль, просто оставьте это поле пустым, тогда он не изменится'),
                                    ]); ?>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6 form-group">
                                    <?php echo \Forms\Builder::password([
                                        'name' => 'password',
                                        'class' => 'valid',
                                    ], __('Пароль')); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[phone]',
                            'value' => $obj->phone,
                        ], __('Номер телефона')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if( $obj->id ): ?>
        <div class="col-md-5">
            <div class="widget">
                <div class="widgetContent">
                    <ul class="anyClass accordion harmonica">
                        <li><a href="#" class="btn harFull harClose"><?php echo __('Социальные сети'); ?></a>
                            <ul class="">
                                <div>
                                    <table class="table table-hover table-condensed">
                                        <thead>
                                        <tr>
                                            <th><?php echo __('Сеть'); ?></th>
                                            <th>UID</th>
                                            <th><?php echo __('Имя'); ?></th>
                                            <th><?php echo __('Фамилия'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if( !sizeof($socials) ): ?>
                                            <tr style="background-color: #F3F3C3;">
                                                <td colspan="4"><?php echo __('Пользователь не прикрепил ни одной социальной сети'); ?></td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach( $socials AS $key => $value ): ?>
                                                <tr>
                                                    <td><?php echo $value->network; ?></td>
                                                    <td><a href="<?php echo $value->profile; ?>" target="_blank"><?php echo $value->uid; ?></a></td>
                                                    <td><?php echo $value->first_name ?: '----'; ?></td>
                                                    <td><?php echo $value->last_name ?: '----'; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="widget withButtons">
                <a href="<?php echo \Core\HTML::link('wezom/orders/index?uid='.\Core\Route::param('id')); ?>" class="btn bs-tooltip" title="<?php echo __('Посмотреть список заказов этого пользователя'); ?>"><?php echo __('Заказы'); ?> (<?php echo $count_orders; ?>)</a>
                <a href="<?php echo \Core\HTML::link('auth-like-regular-user/'.\Core\Encrypt::instance()->encode(\Core\Route::param('id'))); ?>" target="_blank" class="btn btn-warning bs-tooltip" title="<?php echo __('Авторизоваться на сайте как этот пользователь'); ?>"><?php echo __('Авторизоваться'); ?></a>
                <a id="sendThePassword" data-id="<?php echo \Core\Route::param('id'); ?>" class="btn btn-info bs-tooltip" title="<?php echo __('Сгенерировать и выслать новый пароль на почту'); ?>"><?php echo __('Выслать пароль'); ?></a>
            </div>

            <div class="widget">
                <div class="pageInfo alert alert-info">
                    <div class="rowSection">
                        <div class="col-md-6"><strong>IP</strong></div>
                        <div class="col-md-6"><?php echo $obj->ip; ?></div>
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
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Общее количество заказов'); ?></strong></div>
                        <div class="col-md-6"><?php echo $count_orders; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Количество выполненых заказов'); ?></strong></div>
                        <div class="col-md-6"><?php echo $count_good_orders; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Стоимость выполненых заказов'); ?></strong></div>
                        <div class="col-md-6"><?php echo $amount_good_orders; ?> грн</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php echo \Forms\Form::close(); ?>

<div id="sendThePasswordForm" style="display: none;">
    <div class="form-group">
        <label class="control-label"><?php echo __('Пароль'); ?></label>
        <div class="">
            <div class="input-group">
                <input class="form-control" id="f_code" name="password" type="text" />
                <span class="input-group-btn">
                    <button class="btn codeAction" type="button"><?php echo __('Сгенерировать автоматически'); ?></button>
                </span>
            </div>
            <p class="red"><?php echo __('Если оставить поле пустым, на E-Mail пользователя отправится случайно сгенерированнывй пароль и узнать его будет возможно только лишь от самого пользователя!'); ?></p>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('body').on('click', '.codeAction', function(){
            var it = $(this);
            $.ajax({
                url: '/wezom/ajax/generateCode',
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    if(data.success) {
                        $('#f_code').val(data.code);
                    }
                }
            });
        });
        var pickerInit = function( selector ) {
            var date = $(selector).val();
            $(selector).datepicker({
                showOtherMonths: true,
                selectOtherMonths: false
            });
            $(selector).datepicker('option', $.datepicker.regional['ru']);
            var dateFormat = $(selector).datepicker( "option", "dateFormat" );
            $(selector).datepicker( "option", "dateFormat", 'dd.mm.yy' );
            $(selector).val(date);
        };
        pickerInit('.myPicker');
    });
</script>