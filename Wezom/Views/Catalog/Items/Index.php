<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader filterForm" style="padding-bottom: 10px;">
                <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/'.Core\Route::controller().'/index']); ?>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'artikul',
                            'value' => Core\Arr::get($_GET, 'artikul', NULL),
                        ], __('Артикул')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'name',
                            'value' => Core\Arr::get($_GET, 'name', NULL),
                        ], __('Наименование')); ?>
                    </div>
                    <div class="col-md-2">
                        <?php $options = ['' => __('Все'), 0 => __('Неопубликованы'), 1 => __('Опубликованы')]; ?>
                        <?php echo \Forms\Builder::select('<option value="">' . __('Все') . '</option>'.$tree,
                            NULL, [
                            'name' => 'parent_id',
                            ], __('Группа')); ?>
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
                                <a href="/wezom/<?php echo Core\Route::controller(); ?>/index">
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
                    <table class="table table-striped table-hover checkbox-wrap">
                        <thead>
                            <tr>
                                <th class="checkbox-head">
                                    <label><input type="checkbox"></label>
                                </th>
                                <th><?php echo __('Изображение'); ?></th>
                                <th><?php echo __('Название'); ?></th>
                                <th><?php echo __('Группа'); ?></th>
                                <th><?php echo __('Позиция'); ?></th>
                                <th><?php echo __('Статус'); ?></th>
                                <th class="nav-column textcenter">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $result as $obj ): ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td class="checkbox-column">
                                        <label><input type="checkbox"></label>
                                    </td>
                                    <td>
                                        <?php if (is_file(HOST.Core\HTML::media('images/catalog/small/'.$obj->image, false))): ?>
                                            <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>">
                                                <img src="<?php echo Core\HTML::media('images/catalog/small/'.$obj->image); ?>" alt="<?php echo $obj->name; ?>" width="50">
                                            </a>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>">
                                            <?php echo $obj->name; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if($obj->parent_id): ?>
                                            <a href="/wezom/groups/edit/<?php echo $obj->parent_id; ?>" target="_blank"><?php echo $obj->catalog_tree_name; ?></a>
                                        <?php else: ?>
                                            <?php echo __('Удалена'); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="width: 100px;">
                                        <input style="width: 50px; display: inline-block;" type="text" class="form-control" value="<?php echo (int) $obj->sort; ?>" />
                                        <button style="display: inline-block;" class="setPosition btn btn-primary">OK</button>
                                    </td>
                                    <td width="45" valign="top" class="icon-column status-column">
                                        <?php echo Core\View::widget(['status' => $obj->status, 'id' => $obj->id], 'StatusList'); ?>
                                    </td>
                                    <td class="nav-column">
                                        <ul class="table-controls">
                                            <li>
                                                <a title="<?php echo __('Управление'); ?>" class="bs-tooltip dropdownToggle" href="javascript:void(0);"><i class="fa fa-cog size14"></i></a>
                                                <ul class="dropdownMenu pull-right">
                                                    <li>
                                                        <a title="<?php echo __('Перейти'); ?>" href="<?php echo \Core\HTML::link($obj->alias.'/p'.$obj->id); ?>" target="_blank"><i class="fa fa-share-alt"></i> <?php echo __('Перейти'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a title="<?php echo __('Редактировать'); ?>" href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a title="<?php echo __('Удалить'); ?>" onclick="return confirm('<?php echo __('Это действие необратимо. Продолжить?'); ?>');" href="/wezom/<?php echo Core\Route::controller(); ?>/delete/<?php echo $obj->id; ?>"><i class="fa fa-trash-o text-danger"></i> <?php echo __('Удалить'); ?></a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
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