<div class="widgetHeader">
    <div class="widgetTitle">
        <i class="fa fa-reorder"></i>
        <?php echo __('Лента событий'); ?>
    </div>
</div>
<div class="widgetContent">
    <ul class="liTabs t_wrap">
        <li class="t_item">
            <a class="t_link" href="#"><?php echo __('Активность'); ?></a>
            <div class="t_content">
                <div class="mCustomScrollbar fluid scrollSize_1">
                    <ul class="feeds clearFix">
                        <?php if(sizeof($log)): ?>
                            <?php foreach($log AS $obj): ?>
                                <li class="hoverable">
                                    <a href="<?php echo $obj->link; ?>">
                                        <div class="col1">
                                            <div class="content">
                                                <div class="content-col1"><?php echo Core\Log::icon($obj->type); ?></div>
                                                <div class="content-col2"><div class="desc"><?php echo \Core\Config::get('log.'.$obj->type) ?: $obj->name; ?></div></div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date"><?php echo date('d.m.Y H:i', $obj->created_at); ?></div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><?php echo __('Записей нет'); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>