<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader" style="padding-bottom: 10px;">
                <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/'.Core\Route::controller().'/index']); ?>
                    <?php \Forms\Builder::hidden([
                        'name' => 'uid',
                        'value' => \Core\Arr::get($_GET, 'uid'),
                    ]); ?>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'date_s',
                            'value' => Core\Arr::get($_GET, 'date_s', NULL),
                            'class' => 'fPicker',
                        ], __('Дата от')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'date_po',
                            'value' => Core\Arr::get($_GET, 'date_po', NULL),
                            'class' => 'fPicker',
                        ], __('Дата до')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::select($statuses, Core\Arr::get($_GET, 'status'), [
                            'name' => 'status',
                        ], __('Статус')); ?>
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
                                <a href="<?php echo \Core\Arr::get($_GET, 'uid') ? \Core\HTML::link('wezom/'.Core\Route::controller().'/index?uid='.\Core\Arr::get($_GET, 'uid')) : \Core\HTML::link('wezom/'.Core\Route::controller().'/index'); ?>">
                                    <i class="fa fa-refresh"></i>
                                    <span class="hidden-xx"><?php echo __('Сбросить'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php echo \Forms\Form::close(); ?>
            </div>

            <div class="widgetHeader staticInfo">
                <p><?php echo __('Количество заказов'); ?>: <i><?php echo $count; ?></i></p>
                <p><?php echo __('На сумму'); ?>: <i><?php echo (int) $amount; ?></i> грн</p>
            </div>

            <div class="widgetContent">
                <div class="checkbox-wrap">
                    <table class="table table-striped table-bordered orderList" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="hidden-ss"><?php echo __('Номер заказа'); ?></th>
                                <th><?php echo __('Имя'); ?></th>
                                <th><?php echo __('Телефон'); ?></th>
                                <th><?php echo __('Заказчик'); ?></th>
                                <th><?php echo __('Количество'); ?></th>
                                <th><?php echo __('Сумма'); ?></th>
                                <th><?php echo __('Дата'); ?></th>
                                <th><?php echo __('Статус'); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                     
                        <tbody>
                            <?php foreach ( $result as $obj ): ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td class="hidden-ss"><a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>">#<?php echo $obj->id; ?></a></td>
                                    <td><?php echo $obj->name; ?></td>
                                    <td><a href="tel:<?php echo $obj->phone; ?>"><?php echo $obj->phone; ?></a></td>
                                    <td>
                                        <?php if($obj->user_id): ?>
                                            <?php $user = \Core\Common::factory('users')->getRow($obj->user_id); ?>
                                            <?php if($user): ?>
                                                <a href="<?php echo \Core\HTML::link('wezom/users/edit/'.$user->id); ?>" target="_blank"><?php echo $user->last_name.' '.$user->name; ?></a>
                                            <?php else: ?>
                                                <span style="font-style: italic; color: #ccc;">( <?php echo __('Удален'); ?> )</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span style="font-style: italic; color: #ccc;">( <?php echo __('Администратор'); ?> )</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo (int) $obj->count; ?> <?php echo __('товаров'); ?></td>
                                    <td class="sum-column"><?php echo (int) $obj->amount; ?> грн</td>
                                    <td><?php echo date( 'd.m.Y H:i', $obj->created_at ); ?></td>
                                    <td>
                                        <?php if( $obj->status == 3 ): ?>
                                            <?php $class = 'danger'; ?>
                                        <?php endif; ?>
                                        <?php if( $obj->status == 2 ): ?>
                                            <?php $class = 'info'; ?>
                                        <?php endif; ?>
                                        <?php if( $obj->status == 1 ): ?>
                                            <?php $class = 'success'; ?>
                                        <?php endif; ?>
                                        <?php if( $obj->status == 0 ): ?>
                                            <?php $class = 'default'; ?>
                                        <?php endif; ?>
                                        <span title="<?php echo $statuses[$obj->status]; ?>" class="label label-<?php echo $class; ?> orderLabelStatus bs-tooltip">
                                            <span class="hidden-ss"><?php echo $statuses[$obj->status]; ?></span>
                                        </span>
                                    </td>
                                    <td>
                                        <ul class="table-controls">
                                            <li>
                                                <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="<?php echo __('Управление'); ?>"><i class="fa fa-cog size14"></i></a>
                                                <ul class="dropdownMenu pull-right">
                                                    <li>
                                                        <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>" title="<?php echo __('Редактировать'); ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a href="/wezom/<?php echo Core\Route::controller(); ?>/print/<?php echo $obj->id; ?>" target="_blank" title="<?php echo __('Распечатать'); ?>"><i class="fa fa-print"></i> <?php echo __('Распечатать'); ?></a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a onclick="return confirm('<?php echo __('Это действие необратимо. Продолжить?'); ?>');" href="/wezom/<?php echo Core\Route::controller(); ?>/delete/<?php echo $obj->id; ?>" title="<?php echo __('Удалить'); ?>"><i class="fa fa-trash-o text-danger"></i> <?php echo __('Удалить'); ?></a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php echo $pager; ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.orderLabelStatus').on('click', function(e){
            var it = $(this);
            var id = it.closest('tr').attr('data-id');
            var status = it.attr('data-status');
            var sess = it.attr('data-session');
            $.ajax({
                url: '/wezom/ajax/changeStatusOrder.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    sess: sess,
                    status: status
                },
                success: function(data) {
                    if( data.success ) {
                        it.attr( 'data-status', data.st.id );
                        it.attr( 'title', data.st.name );
                        var cl = it.attr( 'data-class' );
                        it.attr( 'data-class', data.st.class );
                        it.removeClass( cl );
                        it.addClass( data.st.class );
                        it.find('span').html( data.st.name );
                    }
                }
            });
        });

        $('.ranges > ul > li.active').removeClass('active');
    });
</script>