<div class="widgetHeader">
    <div class="widgetTitle">
        <i class="fa fa-reorder"></i>
        <?php echo __('Информация от weZom'); ?>
    </div>
    <div class="toolbar no-padding">
        <div class="btn-group">
            <a target="_blank" href="http://wezom.com.ua" class="btn btn-xs">
                <span class="btnText"><?php echo __('Студия'); ?></span>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</div>
<div class="widgetContent">
    <ul class="liTabs t_wrap">
        <li class="t_item">
            <a class="t_link" href="#"><?php echo __('Новости'); ?></a>
            <div class="t_content">
                <div class="mCustomScrollbar fluid scrollSize_1">
                    <ul class="newsWrap">
                        <?php if(sizeof($news)): ?>
                            <?php foreach($news AS $n): ?>
                                <li>
                                    <a href="<?php echo $n['link']; ?>" target="_blank">
                                        <?php if($n['image']): ?>
                                            <span class="photo">
                                            <img src="<?php echo $n['image']; ?>">
                                        </span>
                                        <?php endif; ?>
                                        <span class="subject">
                                            <span class="from"><?php echo $n['name']; ?></span>
                                            <span class="time"><?php echo $n['date']; ?></span>
                                        </span>
                                        <span class="text"><?php echo \Core\Text::limit_chars(trim(strip_tags($n['text'], 150))); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><?php echo __('Новостей нет'); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </li>
        <li class="t_item">
            <a class="t_link" href="#"><?php echo __('Блог'); ?></a>
            <div class="t_content">
                <div class="mCustomScrollbar fluid scrollSize_1">
                    <ul class="newsWrap">
                        <?php if(sizeof($blog)): ?>
                            <?php foreach($blog AS $n): ?>
                                <li>
                                    <a href="<?php echo $n['link']; ?>" target="_blank">
                                        <?php if($n['image']): ?>
                                            <span class="photo">
                                                <img src="<?php echo $n['image']; ?>">
                                            </span>
                                        <?php endif; ?>
                                        <span class="subject">
                                            <span class="from"><?php echo $n['name']; ?></span>
                                            <span class="time"><?php echo $n['date']; ?></span>
                                        </span>
                                        <span class="text"><?php echo \Core\Text::limit_chars(trim(strip_tags($n['text'], 150))); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><?php echo __('Записей в блоге нет'); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>