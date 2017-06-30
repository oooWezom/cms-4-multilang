<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader" style="padding-bottom: 10px;">
                <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/' . Core\Route::controller() . '/index']); ?>
                <?php
                \Forms\Builder::hidden([
                    'name' => 'uid',
                    'value' => \Core\Arr::get($_GET, 'uid'),
                ]);
                ?>
                <div class="col-md-2">
                    <?php
                    echo \Forms\Builder::input([
                        'name' => 'item_name',
                        'value' => Core\Arr::get($_GET, 'item_name', NULL),
                            ], __('Название статьи блога'));
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo \Forms\Builder::input([
                        'name' => 'date_s',
                        'value' => Core\Arr::get($_GET, 'date_s', NULL),
                        'class' => 'fPicker',
                            ], __('Дата от'));
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo \Forms\Builder::input([
                        'name' => 'date_po',
                        'value' => Core\Arr::get($_GET, 'date_po', NULL),
                        'class' => 'fPicker',
                            ], __('Дата до'));
                    ?>
                </div>
                <div class="col-md-2">
                    <?php $options = ['' => __('Все'), 0 => __('Неопубликованы'), 1 => __('Опубликованы')]; ?>
                    <?php
                    echo \Forms\Builder::select($options, Core\Arr::get($_GET, 'status', 2), [
                        'name' => 'status',
                            ], __('Статус'));
                    ?>
                </div>
                <div class="col-md-2">
                    <?php $options = []; ?>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php $number = $i * Core\Config::get('basic.limit_backend'); ?>
                        <?php $options[$number] = $number; ?>
                    <?php endfor; ?>
<?php
echo \Forms\Builder::select($options, Core\Arr::get($_GET, 'limit', Core\Config::get('basic.limit_backend')), [
    'name' => 'limit',
        ], __('Выводить по'));
?>
                </div>
                <div class="col-md-1">
                    <label class="control-label" style="height:16px;">&nbsp</label>
<?php
echo \Forms\Form::submit([
    'class' => 'btn btn-primary',
    'value' => __('Подобрать'),
]);
?>
                </div>
                <div class="col-md-1 stable105">
                    <label class="control-label" style="height:22px;"></label>
                    <div class="">
                        <div class="controls">
                            <a href="<?php echo \Core\Arr::get($_GET, 'uid') ? \Core\HTML::link('wezom/' . Core\Route::controller() . '/index?uid=' . \Core\Arr::get($_GET, 'uid')) : \Core\HTML::link('wezom/' . Core\Route::controller() . '/index'); ?>">
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
                    <table class="table table-striped table-hover checkbox-wrap ">
                        <thead>
                            <tr>
                                <th class="checkbox-head">
                                    <label><input type="checkbox"></label>
                                </th>
                                <th>IP</th>
                                <th><?php echo __('Запись в блоге'); ?></th>
                                <th><?php echo __('Автор'); ?></th>
                                <th><?php echo __('Краткое содержание'); ?></th>
                                <th><?php echo __('Дата'); ?></th>
                                <th><?php echo __('Опубликовано?'); ?></th>
                                <th class="nav-column textcenter">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                                    <?php foreach ($result as $obj): ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td class="checkbox-column">
                                        <label><input type="checkbox"></label>
                                    </td>
                                    <td><?php echo $obj->ip ? $obj->ip : '<i class="color: #ccc;">( ' . __('Администратор') . ' )</i>'; ?></td>
                                    <td>
                                        <?php if ($obj->blog_name): ?>
                                            <a href="/wezom/blog/edit/<?php echo $obj->blog_id ?>" target="_blank"><?php echo $obj->blog_name; ?></a>
    <?php else: ?>
                                            <i style="color: #aaa;">( <?php echo __('Удалена'); ?> )</i>
    <?php endif ?>
                                    </td>
                                    <td>
    <?php if ($obj->user_id): ?>
        <?php if ($obj->user_name): ?>
                                                <a href="/wezom/users/edit/<?php echo $obj->user_id ?>"><?php echo $obj->user_name; ?></a>
        <?php else: ?>
                                                <a href="/wezom/users/edit/<?php echo $obj->user_id ?>"><?php echo __('Нет имени'); ?></a>
        <?php endif; ?>
    <?php else: ?>
                                            <i class="color: #ccc;">( <?php echo __('Администратор'); ?> )</i>
    <?php endif; ?>
                                    </td>
                                    <td><?php echo Core\Text::limit_words($obj->text, 100); ?></td>
                                    <td><?php echo $obj->date ? date('d.m.Y', $obj->date) : '---'; ?></td>
                                    <td width="45" valign="top" class="icon-column status-column">
    <?php echo Core\View::widget(['status' => $obj->status, 'id' => $obj->id], 'StatusList'); ?>
                                    </td>
                                    <td class="nav-column">
                                        <ul class="table-controls">
                                            <li>
                                                <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="<?php echo __('Управление'); ?>"><i class="fa fa-cog size14"></i></a>
                                                <ul class="dropdownMenu pull-right">
                                                    <li>
                                                        <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>" title="<?php echo __('Редактировать'); ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a href="/wezom/items/edit/<?php echo $obj->catalog_id; ?>" title="<?php echo __('Перейти к записи'); ?>"><i class="fa fa-inbox"></i> <?php echo __('Перейти к товару'); ?></a>
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
<?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>