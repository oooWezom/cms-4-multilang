<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo $pageName; ?>
                    <span class="label label-primary"><?php echo $count; ?></span>
                </div>
                <div class="toolbar no-padding" id="ordersToolbar" data-uri="<?php echo 'wezom'.Core\Arr::get($_SERVER, 'REQUEST_URI'); ?>">
                    <div class="btn-group">
                        <li class="btn btn-xs">
                            <a href="/wezom/<?php echo Core\Route::controller(); ?>/index">
                                <i class="fa fa-refresh"></i>
                                <span class="hidden-xx"><?php echo __('Сбросить'); ?></span>
                            </a>
                        </li>
                        <span class="btn btn-xs dropdownToggle dropdownSelect">
                             <i class="fa fa-filter"></i>
                             <span class="current-item"><?php echo isset($_GET['status']) ? ( Core\Arr::get( $_GET, 'status' ) ? __('Опубликованы') : __('Неопубликованы') ) : __('Все'); ?></span>
                             <span class="caret"></span>
                        </span>
                        <ul class="dropdownMenu pull-right">
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', NULL); ?>">
                                    <i class="fa fa-filter"></i><?php echo __('Все'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', 1); ?>">
                                    <i class="fa fa-filter"></i><?php echo __('Опубликованы'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', 0); ?>">
                                    <i class="fa fa-filter"></i><?php echo __('Неопубликованы'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="widget">
                <div class="widgetContent">
                    <table class="table table-striped table-hover checkbox-wrap ">
                        <thead>
                            <tr>
                                <th class="checkbox-head">
                                    <label><input type="checkbox"></label>
                                </th>
                                <th><?php echo __('Изображение'); ?></th>
                                <th><?php echo __('Описание'); ?></th>
                                <th><?php echo __('Ссылка'); ?></th>
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
                                        <?php if (is_file(HOST.Core\HTML::media('images/banners/'.$obj->image, false))): ?>
                                            <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>">
                                                <img src="<?php echo Core\HTML::media('images/banners/'.$obj->image); ?>" alt="<?php echo $obj->name; ?>" width="50">
                                            </a>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if ($obj->text): ?>
                                            <?php echo $obj->text; ?>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if ($obj->url): ?>
                                            <a href="<?php echo $obj->url; ?>" target="_blank"><?php echo $obj->url; ?></a>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
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