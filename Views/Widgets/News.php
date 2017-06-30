<?php
use Core\Dates;
use Core\HTML;
use Core\Text;

?>
<div class="wRight">
    <div class="index news">
        <div class="title_news"><?php echo __('новости'); ?></div>
        <div class="date_news">
            <div class="date"><?php echo date('d', $obj->date); ?></div>
            <div class="mounth"><?php echo Dates::shortMonth(date('m', $obj->date)); ?></div>
        </div>
        <div class="news_text">
            <a href="<?php echo HTML::link('news/' . $obj->alias); ?>"
               class="news_title">
                <span><?php echo $obj->name; ?></span>
            </a>
            <p><?php echo Text::limit_words(strip_tags($obj->text), 20); ?></p>
            <a href="<?php echo HTML::link('news/' . $obj->alias); ?>" class="next_but"><?php echo __('подробнее'); ?></a>
        </div>
        <a href="<?php echo HTML::link('news'); ?>" class="slide_but">
            <span><?php echo __('все новости'); ?></span>
        </a>
    </div>
</div>