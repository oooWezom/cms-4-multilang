<div class="widgetHeader" style="padding-bottom: 10px;">
    <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/seo_redirects/index']); ?>
        <div class="col-md-2">
            <?php echo \Forms\Builder::input([
                'name' => 'link_from',
                'value' => Core\Arr::get($_GET, 'link_from', NULL),
            ], __('URL с')); ?>
        </div>
        <div class="col-md-2">
            <?php echo \Forms\Builder::input([
                'name' => 'link_to',
                'value' => Core\Arr::get($_GET, 'link_to', NULL),
            ], __('URL на')); ?>
        </div>
        <div class="col-md-2">
            <?php $options = [
                '' => 'Все',
                300 => __('Multiple Choices («множество выборов»)'),
                301 => __('Moved Permanently («перемещено навсегда»)'),
                302 => __('Moved Temporarily («перемещено временно»)'),
                303 => __('See Other (смотреть другое)'),
                304 => __('Not Modified (не изменялось)'),
                305 => __('Use Proxy («использовать прокси»)'),
                307 => __('Temporary Redirect («временное перенаправление»)'),
            ]; ?>
            <?php echo \Forms\Builder::select($options, Core\Arr::get($_GET, 'type'), [
                'name' => 'type',
            ], __('Тип')); ?>
        </div>
        <div class="col-md-2">
            <?php $options = ['' => __('Все'), 0 => __('Неопубликованы'), 1 => __('Опубликованы')]; ?>
            <?php echo \Forms\Builder::select($options, Core\Arr::get($_GET, 'status', 2), [
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
        <div class="col-md-1">
            <label class="control-label" style="height:22px;"></label>
            <div class="">
                <div class="controls">
                    <a href="/wezom/seo_redirects/index">
                        <i class="fa fa-refresh"></i>
                        <span class="hidden-xx"><?php echo __('Сбросить'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    <?php echo \Forms\Form::close(); ?>
</div>
<div class="dd pageList">
    <ol class="dd-list">
        <?php foreach ($result as $obj): ?>
            <li class="dd-item dd3-item" data-id="<?php echo $obj->id; ?>">
                <div class="dd3-content" style="padding-left: 0;">
                    <table>
                        <tr>
                            <td width="1%" class="checkbox-column">
                                <label><input type="checkbox" /></label>
                            </td>
                            <td class="hidden-xs" valign="middle">
                                <div><a href="<?php echo $obj->link_from; ?>" target="_blank"><?php echo $obj->link_from; ?></a></div>
                            </td>
                            <td class="hidden-xs" valign="middle">
                                <div><a href="<?php echo $obj->link_to; ?>" target="_blank"><?php echo $obj->link_to; ?></a></div>
                            </td>
                            <td><?php echo $obj->type; ?></td>
                            <td width="45" valign="top" class="icon-column status-column">
                                <?php echo Core\View::widget(['status' => $obj->status, 'id' => $obj->id], 'StatusList'); ?>
                            </td>
                            <td class="nav-column icon-column" valign="top">
                                <ul class="table-controls">
                                    <li>
                                        <a title="<?php echo __('Управление перенаправлением'); ?>" href="javascript:void(0);" class="bs-tooltip dropdownToggle"><i class="fa fa-cog"></i> </a>
                                        <ul class="dropdownMenu pull-right">
                                            <li>
                                                <a title="<?php echo __('Редактировать'); ?>" href="<?php echo '/wezom/seo_redirects/edit/'.$obj->id; ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a title="<?php echo __('Удалить'); ?>" onclick="return confirm('Это действие необратимо. Продолжить?');" href="<?php echo '/wezom/seo_redirects/delete/'.$obj->id; ?>"><i class="fa fa-trash-o text-danger"></i> <?php echo __('Удалить'); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
    <?php echo $pager; ?>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>