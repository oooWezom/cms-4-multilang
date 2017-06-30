<div class="rowSection clearFix">
    <div class="col-md-6">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Счётчик заказов'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="orderChartSize" id="orderChart"></div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            if($('#orderChart').length){
                $.getJSON('/wezom/ajax/ordersChart', function (obj) {
                    $('#orderChart').highcharts({
                        chart: { type: 'line' },
                        title: { text: null },
                        subtitle: { text: null },
                        plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
                        series: [{ name: 'Заказы', data: obj.count, color:'#94b86e' }],
                        xAxis: { categories: obj.months },
                        yAxis: { title: { text: null }, allowDecimals: false, floor: 0 },
                        legend: { enabled: false }
                    });
                });
            }
        });
    </script>

    <div class="col-md-6">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Последние заказы'); ?>
                </div>
            </div>
            <div class="widgetContent no-padding">
                <table class="table table-striped table-hover table-condensed">
                    <thead class="theadCondensedMain">
                        <tr>
                            <th>№</th>
                            <th><?php echo __('Телефон'); ?></th>
                            <th><?php echo __('Сумма'); ?></th>
                            <th><?php echo __('Дата'); ?></th>
                            <th><?php echo __('Статус'); ?></th>
                            <th class="align-center hidden-xs"><?php echo __('Перейти'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders AS $obj): ?>
                            <tr>
                                <td><a href="/wezom/orders/edit/<?php echo $obj->id; ?>">#<?php echo $obj->id; ?></a></td>
                                <td><a href="tel:<?php echo preg_replace('/[^0-9]/', '', $obj->phone); ?>"><?php echo $obj->phone; ?></a></td>
                                <td><b><?php echo (int) $obj->amount; ?></b> грн</td>
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
                                <td class="align-center hidden-xs">
                                    <span class="btn-group">
                                        <a title="<?php echo __('Перейти к заказу'); ?>" class="tip btn btn-xs bs-tooltip" href="/wezom/orders/edit/<?php echo $obj->id; ?>"><?php echo __('Перейти к заказу'); ?></a>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>