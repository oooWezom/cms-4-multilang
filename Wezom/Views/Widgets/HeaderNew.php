<li class="dropdown dropdownMenuHidden">
    <a class="dropdownToggle" href="#">
        <i class="fa fa-warning"></i>
        <!-- <span class="badge"><?php //echo $count; ?></span> -->
    </a>
    <ul class="dropdownMenu extended notification scrollbarHeight pull-right">
        <li class="title">
            <p><?php echo __('Всего у вас уведомений', [':count' => $count]); ?></p>
        </li>
        <?php foreach($result as $obj): ?>
            <li>
                <a href="<?php echo $obj->link; ?>">
                    <?php echo Core\Log::icon($obj->type); ?>
                    <span class="message" title="<?php echo \Core\Config::get('log.'.$obj->type) ?: $obj->name; ?>"><?php echo \Core\Config::get('log.'.$obj->type) ?: $obj->name; ?></span>
                    <span class="time"><?php echo date('d.m.Y H:i', $obj->created_at); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
        <li class="footer">
            <a href="/wezom/log/index"><?php echo __('Смотреть все уведомления'); ?></a>
        </li>
    </ul>
</li>