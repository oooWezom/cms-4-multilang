<?php use Core\HTML; ?>
<div class="new clearFix">
    <?php if (is_file(HOST . HTML::media('/images/articles/small/' . $obj->image, false)) and $obj->show_image): ?>
        <div class="news_img">
            <img src="<?php echo HTML::media('/images/articles/big/' . $obj->image); ?>" alt=""/>
        </div>
    <?php endif; ?>
    <div class="wTxt">
        <?php echo $obj->text; ?>
        <a href="<?php echo HTML::link('articles'); ?>" class="back_to_news"><span><?php echo __('вернуться к списку статей') ?></span></a>
    </div>
</div>