<?php
use Core\HTML;
use Core\View;

?>
<div class="slider_main">
    <ul>
        <?php foreach ($result as $obj): ?>
            <li>
                <?php if (is_file(HOST . HTML::media('images/slider/big/' . $obj->image, false))): ?>
                    <img src="<?php echo HTML::media('images/slider/big/' . $obj->image); ?>" alt="">
                <?php endif; ?>
                <div class="slide_posa">
                    <?php if ($obj->name): ?>
                        <div class="name_towar"><span><?php echo $obj->name; ?></span></div>
                    <?php endif ?>
                    <?php if ($obj->description): ?>
                        <div class="slogan_name"><?php echo $obj->description; ?></div>
                    <?php endif ?>
                    <?php if ($obj->url): ?>
                        <a href="<?php echo $obj->url; ?>" class="slide_but"><span><?php echo __('подробнее') ?></span></a>
                    <?php endif ?>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
    <div class="prev1"></div>
    <div class="next1"></div>
</div>