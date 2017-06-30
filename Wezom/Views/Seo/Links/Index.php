<div class="widgetHeader" style="padding-bottom: 10px;">
    <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/seo_links/index']); ?>
        <div class="col-md-2">
            <?php echo \Forms\Builder::input([
                'name' => 'name',
                'value' => Core\Arr::get($_GET, 'name', NULL),
            ], __('Название')); ?>
        </div>
        <div class="col-md-2">
            <?php echo \Forms\Builder::input([
                'name' => 'link',
                'value' => Core\Arr::get($_GET, 'link', NULL),
            ], __('URL')); ?>
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
                    <a href="/wezom/seo_links/index">
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
                            <td valign="middle" class="pagename-column">
                                <div class="clearFix">
                                    <div class="pull-left">
                                        <div class="pull-left">
                                            <div><a class="pageLinkEdit" href="<?php echo '/wezom/seo_links/edit/'.$obj->id; ?>"><?php echo $obj->name; ?></a></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden-xs" valign="middle">
                                <div><a href="<?php echo $obj->link; ?>" target="_blank"><?php echo $obj->link; ?></a></div>
                            </td>
                            <td width="45" valign="top" class="icon-column status-column">
                                <?php echo Core\View::widget(['status' => $obj->status, 'id' => $obj->id], 'StatusList'); ?>
                            </td> 
                            <td class="nav-column icon-column" valign="top">
                                <ul class="table-controls">
                                    <li>
                                        <a title="<?php echo __('Управление ссылкой'); ?>" href="javascript:void(0);" class="bs-tooltip dropdownToggle"><i class="fa fa-cog"></i> </a>
                                        <ul class="dropdownMenu pull-right">
                                            <li>
                                                <a title="<?php echo __('Редактировать'); ?>" href="<?php echo '/wezom/seo_links/edit/'.$obj->id; ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a title="<?php echo __('Удалить'); ?>" onclick="return confirm('Это действие необратимо. Продолжить?');" href="<?php echo '/wezom/seo_links/delete/'.$obj->id; ?>"><i class="fa fa-trash-o text-danger"></i> <?php echo __('Удалить'); ?></a>
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