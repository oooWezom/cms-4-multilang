<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo $pageName; ?>
                    <span class="label label-primary"><?php echo $count; ?></span>
                </div>
                <div class="toolbar no-padding" id="ordersToolbar" data-uri="<?php echo Core\Arr::get($_SERVER, 'REQUEST_URI'); ?>">
                    <div class="btn-group">
                        <li class="btn btn-xs">
                            <a href="/wezom/<?php echo Core\Route::controller(); ?>/index">
                                <i class="fa fa-refresh"></i>
                                <span class="hidden-xx"><?php echo __('Сбросить'); ?></span>
                            </a>
                        </li>
                        <span class="btn btn-xs dropdownToggle dropdownSelect">
                             <i class="fa fa-filter"></i>
                             <span class="current-item"><?php echo isset($_GET['status']) ? ( Core\Arr::get( $_GET, 'status' ) ? __('Прочитанные') : __('Непрочитанные') ) : __('Все'); ?></span>
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
                                    <i class="fa fa-filter"></i><?php echo __('Прочитанные'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', 0); ?>">
                                    <i class="fa fa-filter"></i><?php echo __('Непрочитанные'); ?>
                                </a>
                            </li>
                        </ul>

                        <li title="<?php echo __('Выберите дату или период'); ?>" class="range rangeOrderList btn btn-xs bs-tooltip">
                            <a href="#">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo Core\Support::getWidgetDatesRange(); ?></span>
                                <i class="caret"></i>
                            </a>
                        </li>

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
                                <th><?php echo __('Имя'); ?></th>
                                <th><?php echo __('Номер телефона'); ?></th>
                                <th>IP</th>
                                <th><?php echo __('Дата'); ?></th>
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
                                    <td><a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>"><?php echo $obj->name; ?></a></td>
                                    <td><a href="tel:<?php echo $obj->phone; ?>"><?php echo $obj->phone; ?></a></td>
                                    <td><?php echo $obj->ip; ?></td>
                                    <td><?php echo $obj->created_at ? date( 'd.m.Y', $obj->created_at ) : '----'; ?></td>
                                    <td width="45" valign="top" class="icon-column status-column">
                                        <a
                                            data-pub="<b><?php echo __('Отметить как непрочитанное'); ?></b><br><?php echo __('Прочитано'); ?>"
                                            data-unpub="<b><?php echo __('Отметить как прочитано'); ?></b><br><?php echo __('Не прочитано'); ?>"
                                            title="<?php echo $obj->status == 1 ? '<b>' . __('Отметить как непрочитанное') . '</b><br>' . __('Прочитано') : '<b>' . __('Отметить как прочитано') . '</b><br>' . __('Не прочитано'); ?>"
                                            data-status="<?php echo $obj->status; ?>" 
                                            data-id="<?php echo $obj->id; ?>"
                                            href="#" 
                                            class="setStatus bs-tooltip btn btn-xs <?php echo $obj->status == 1 ? 'btn-success' : 'btn-danger' ?>"
                                        >
                                            <?php if ($obj->status == 1): ?>
                                                <i class="fa fa-check"></i>
                                            <?php else: ?>
                                                <i class="fa fa-dot-circle-o"></i>
                                            <?php endif ?>
                                        </a>
                                    </td>
                                    <td class="nav-column">
                                        <ul class="table-controls">
                                            <li>
                                                <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="Управление"><i class="fa fa-cog size14"></i></a>
                                                <ul class="dropdownMenu pull-right">
                                                    <li>
                                                        <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>" title="<?php echo __('Редактировать'); ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
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