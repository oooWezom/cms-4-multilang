<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <?php if(count(\Core\Arr::get($groups, 'left', []))): ?>
        <div class="col-md-<?php echo count(\Core\Arr::get($groups, 'right', [])) ? 7 : 12; ?>">
            <div class="widget">
                <div class="widgetContent">
                    <div class="form-vertical row-border">
                        <ul class="liTabs t_wrap">
                            <?php foreach($groups['left'] AS $group): ?>
                                <?php if(\Core\Arr::get($result, $group->alias)): ?>
                                    <li class="t_item">
                                        <a class="t_link" href="#"><?php echo __($group->name); ?></a>
                                        <div class="t_content <?php echo $group->alias?>Content">
                                            <?php foreach ($result[$group->alias] as $obj): ?>
                                                <?php echo \Core\View::tpl(['obj' => $obj], 'Config/Row'); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if(count(\Core\Arr::get($groups, 'right', []))): ?>
        <div class="col-md-<?php echo count(\Core\Arr::get($groups, 'left', [])) ? 5 : 12; ?>">
            <div class="widget">
                <div class="widgetContent">
                    <div class="form-vertical row-border">
                        <ul class="liTabs t_wrap">
                            <?php foreach($groups['right'] AS $group): ?>
                                <?php if(\Core\Arr::get($result, $group->alias)): ?>
                                    <li class="t_item">
                                        <a class="t_link" href="#"><?php echo __($group->name); ?></a>
                                        <div class="t_content <?php echo $group->alias?>Content">
                                            <?php foreach ($result[$group->alias] as $obj): ?>
                                                <?php echo \Core\View::tpl(['obj' => $obj], 'Config/Row'); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <li class="t_item">
                                <a class="t_link" href="#"><?php echo __('Тестовое письмо'); ?></a>
                                <div class="t_content">
                                    <div class="sendTestEmail" style="padding-top: 0;" data-ajax="config/testEmail">
                                        <div class="red hide mailNotice"><?php echo __('Пожалуйста, сохраните настройки почты перед отправкой тестового письма'); ?></div>
                                        <div class="form-group">
                                            <?php echo \Forms\Builder::input([
                                                'name' => 'title'
                                            ], __('Заголовок письма')); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo \Forms\Builder::input([
                                                'name' => 'body'
                                            ], __('Тело письма')); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo \Forms\Builder::input([
                                                'name' => 'email'
                                            ], __('E-mail получателя')); ?>
                                        </div>
                                        <div class="textright">
                                            <?php echo \Forms\Form::button(__('Отправить'), ['type' => 'button', 'class' => 'btn btn-primary']); ?>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php echo \Forms\Form::close(); ?>

<script>
    $(function(){
        var input;
        $('input[type="password"]').closest('div').addClass('input-group');
        $('.showPassword').on('click', function(){
            input = $(this).closest('div.input-group').find('input');
            if(input.attr('type') == 'password') {
                input.attr('type', 'text');
                $(this).text('Скрыть');
            } else {
                input.attr('type', 'password');
                $(this).text('Показать');
            }
        });

        $('.sendTestEmail button').on('click', function () {
            preloader();
            var it = $(this);
            var form = it.closest('.sendTestEmail');
            var action = form.data('ajax');
            if (action) {
                $.ajax({
                    url: '/wezom/ajax/' + action,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        title: form.find('input[name=title]').val(),
                        body: form.find('input[name=body]').val(),
                        email: form.find('input[name=email]').val(),
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.success) {
                            if (data.response) {
                                generate(data.response, 'success');
                            }
                            if (data.reload) {
                                window.location.reload();
                            } else {
                                preloader();
                            }
                        } else {
                            if (data.response) {
                                generate(data.response, 'warning');
                            }
                            preloader();
                        }
                    }
                });
            }
        });

        $('.mailContent').on('change', 'input', function(){
            $('.mailNotice').show();
        });
    });
</script>