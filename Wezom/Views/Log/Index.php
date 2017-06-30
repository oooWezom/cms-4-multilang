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
                                <th><?php echo __('Дата'); ?></th>
                                <th><?php echo __('Описание'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $result as $obj ): ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td style="width: 150px;"><?php echo $obj->created_at ? date( 'd.m.Y, H:i:s', $obj->created_at ) : '----'; ?></td>
                                    <td><a href="<?php echo $obj->link; ?>" target="_blank"><?php echo $obj->name; ?></a></td>
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