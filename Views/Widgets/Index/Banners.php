<?php use Core\HTML; ?>
<ul class="under_slider">
    <?php foreach ($result as $obj): ?>
        <li>
            <?php if (is_file(HOST . HTML::media('images/banners/' . $obj->image, false))): ?>
                <img src="<?php echo HTML::media('images/banners/' . $obj->image); ?>" alt="">
            <?php endif; ?>
            <div class="under_text">
                <p><?php echo $obj->small; ?></p>
                <p><?php echo $obj->big; ?></p>
                <br/>
                <?php if ($obj->url): ?>
                    <a href="<?php echo $obj->url; ?>" class="slide_but"><span><?php echo __('подробнее'); ?></span></a>
                <?php endif; ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>