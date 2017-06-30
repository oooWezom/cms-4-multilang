<?php
use Core\Text;
use Core\HTML;

?>
<div class="tabs_block">
    <div class="wTab_Nav">
        <?php foreach ($result as $key => $obj): ?>
            <span class="wTab_link <?php echo $key == 0 ? 'curr' : ''; ?>" data-tab-container="wTab_Exmpl"
                  data-tab-link="<?php echo $obj->alias; ?>22"><?php echo $obj->name; ?></span>
        <?php endforeach ?>
    </div>
    <div class="wTab_Cantainer wTab_Exmpl">
        <?php foreach ($result as $key => $obj): ?>
            <div class="wTab_Block <?php echo $obj->alias; ?>22 <?php echo $key == 0 ? 'curr' : ''; ?>">
                <div class="wTxt">
                    <p><?php echo Text::limit_words(strip_tags($obj->text), 50) ?></p>
                </div>
                <a href="<?php echo HTML::link($obj->alias); ?>" class="slide_but"><span><?php echo __('подробнее'); ?></span></a>
            </div>
        <?php endforeach ?>
    </div>
</div>