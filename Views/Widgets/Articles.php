<?php
use Core\HTML;
use Core\Text;

?>
<div class="wMiddle">
    <div class="middle_title"><?php echo __('статьи'); ?></div>
    <div class="stat_slider_block">
        <ul class="stat_slider">
            <?php foreach ($result as $obj): ?>
                <li class="stat_block">
                    <?php if (is_file(HOST . HTML::media('/images/articles/small/' . $obj->image, false))): ?>
                        <a href="<?php echo HTML::link('articles/' . $obj->alias); ?>" class="stat_img">
                            <img src="<?php echo HTML::media('images/articles/small/' . $obj->image); ?>" alt="">
                        </a>
                    <?php endif ?>
                    <div class="opus_block">
                        <a href="<?php echo HTML::link('articles/' . $obj->alias); ?>"
                           class="opus_title"><?php echo $obj->name; ?></a>
                        <p><?php echo Text::limit_words($obj->text, 40); ?></p>
                        <div class="clear"></div>
                        <a href="<?php echo HTML::link('articles/' . $obj->alias); ?>" class="opus_but"><?php echo __('подробнее...'); ?></a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <a href="<?php echo HTML::link('articles'); ?>" class="slide_but"><?php echo __('архив статей'); ?></a>
    <div class="prev2">
        <div class="arrow"></div>
    </div>
    <div class="next2">
        <div class="arrow"></div>
    </div>
</div>