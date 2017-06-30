<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader" style="padding-bottom: 10px;">
                <?php echo \Forms\Form::open(['class' => 'widgetContent filterForm', 'action' => '/wezom/'.Core\Route::controller().'/index']); ?>
                    <div class="col-md-2">
                        <?php echo \Forms\Builder::input([
                            'name' => 'ip',
                            'value' => Core\Arr::get($_GET, 'ip', NULL),
                        ], 'IP'); ?>
                    </div>
                    <div class="col-md-2">
                        <?php $options = [
                            'created_at-desc' => __('По дате первого посещения Я -> А'),
                            'created_at-asc' => __('По дате первого посещения А -> Я'),
                        ]; ?>
                        <?php echo \Forms\Builder::select($options,
                            Core\Arr::get($_GET, 'sort'), [
                                'name' => 'sort',
                            ], __('Сортировать')); ?>
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
                                <th>IP</th>
                                <th>URL</th>
                                <th><?php echo __('Дата'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $result as $obj ): ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td><a href="<?php echo '/wezom/visitors/index?ip='.$obj->ip; ?>"><?php echo $obj->ip; ?></a></td>
                                    <td><a href="<?php echo $obj->url; ?>" target="_blank"><?php echo $obj->url; ?></a></td>
                                    <td><?php echo date('d.m.Y H:i:s', $obj->created_at); ?></td>
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