<?php use Core\HTML; ?>
    <ul class="cat_ul">
        <?php foreach ($result as $obj): ?>
            <li>
                <a href="<?php echo HTML::link('products/' . $obj->alias); ?>">
                    <div class="img_block_cat">
                        <?php if (is_file(HOST . HTML::media('images/catalog_tree/' . $obj->image, false))): ?>
                            <img src="<?php echo HTML::media('images/catalog_tree/' . $obj->image); ?>" alt="">
                        <?php else: ?>
                            <img src="<?php echo HTML::media('pic/no-photo.png'); ?>" alt="">
                        <?php endif; ?>
                    </div>
                </a>
                <a href="<?php echo HTML::link('products/' . $obj->alias); ?>">
                    <span><?php echo $obj->name; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php echo $pager; ?>