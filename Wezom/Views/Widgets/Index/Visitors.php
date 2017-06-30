<div class="rowSection clearFix">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Счётчик посещений'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="visitChartSize" id="visitChart"></div>
            </div>
            <div class="divider"></div>
            <div class="widgetContent">
                <ul class="stats" id="stats">
                    <li class="test"> <a href="/wezom/hits/index"><strong></strong></a> <small><?php echo __('Хитов'); ?></small> </li>
                    <li class="light hidden-xs"> <strong></strong> <small><?php echo __('За 24 часа'); ?></small> </li>
                    <li class="test"> <a href="/wezom/visitors/index"><strong></strong></a> <small><?php echo __('Уникальных посетителей'); ?></small> </li>
                    <li class="light hidden-xs"> <strong></strong> <small><?php echo __('За 24 часа'); ?></small> </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        if( $('#visitChart').length){
            var stats = $('ul#stats');
            $.getJSON('/wezom/ajax/visitorsMainData', function (obj) {
                stats.find('li.test:first strong').text(obj.hits);
                stats.find('li.test:last strong').text(obj.unique_hits);
                stats.find('li.light:first strong').text(obj.hits_tf);
                stats.find('li.light:last strong').text(obj.unique_hits_tf);
                $('#visitChart').highcharts({
                    title: { text: null },
                    subtitle: { text: 'Источник: weZom CMS' },
                    tooltip: { shared: true, crosshairs: true },
                    plotOptions: { series: { cursor: 'pointer', marker: { lineWidth: 1 } } },
                    series: [{ name: 'Хитов', data: obj.visits }, { name: 'Уникальных посетителей', data: obj.visitors }],
                    yAxis: { title: { text: 'Количество' }, allowDecimals: false, floor: 0 },
                    xAxis: { type: 'date', categories: obj.dates, offset: 0, tickmarkPlacement: 'on' }
                });
            });
        }
    });
</script>