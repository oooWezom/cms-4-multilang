<?php
use Core\HTML;
use Core\View;

?>
<div class="novelty clearFix">
    <div class="fll">
        <div class="new_pos"><?php echo __('похожие<br>модели'); ?></div>
        <a href="<?php echo HTML::link('products/' . $alias); ?>"
           class="slide_but"><span><?php echo __('пререйти в раздел'); ?></span></a>
    </div>
    <div class="flr">
        <ul>
            <?php foreach ($result as $obj): ?>
                <?php echo View::tpl(['obj' => $obj], 'Catalog/ListItemTemplate'); ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>