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

                        <li title="Выберите дату или период" class="range rangeOrderList btn btn-xs bs-tooltip">
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
                                <th><?php echo __('Тема'); ?></th>
                                <th><?php echo __('Содержание'); ?></th>
                                <th><?php echo __('Дата'); ?></th>
                                <th><?php echo __('Получатели'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $result as $obj ): ?>
                                <tr>
                                    <td><?php echo $obj->subject; ?></td>
                                    <td>
                                        <span class="emailsOpen" data-open="<?php echo __('Развернуть'); ?>" data-close="<?php echo __('Свернуть'); ?>"><?php echo __('Развернуть'); ?></span>
                                        <span class="emailsList"><?php echo $obj->text; ?></span>
                                    </td>
                                    <td><?php echo $obj->created_at ? date( 'd.m.Y', $obj->created_at ) : '----'; ?></td>
                                    <td>
                                        <span class="emailsOpen" data-open="<?php echo __('Cписок'); ?> (<?php echo $obj->count_emails; ?>)" data-close="<?php echo __('Свернуть'); ?>"><?php echo __('Cписок'); ?> (<?php echo $obj->count_emails; ?>)</span>
                                        <span class="emailsList"><?php echo str_replace(';', '<br />', $obj->emails); ?></span>
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

<script>
    $(function(){
        $('.emailsOpen').on('click', function(){
            var link = $(this);
            var block = link.closest('td').find('.emailsList');
            if( block.is(':visible') ) {
                $(this).text($(this).data('open'));
                block.hide(500);
            } else {
                $(this).text($(this).data('close'));
                block.show(500);
            }
        });
    });
</script>