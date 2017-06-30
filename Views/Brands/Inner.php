<?php use Core\HTML; ?>
<div class="brand-top-wrap wTxt">
    <div class="bt-text">
        <?php echo $brand->text; ?>
    </div>
    <?php if (is_file(HOST . HTML::media('images/brands/small/' . $brand->image, false))): ?>
        <a class="bt-pic fresco" data-fresco-group="gr1"
           href="<?php echo HTML::media('images/brands/original/' . $brand->image); ?>">
            <img src="<?php echo HTML::media('images/brands/small/' . $brand->image); ?>"
                 alt="<?php echo $brand->name; ?>" title="<?php echo $brand->name; ?>" class="floatleft brandInnerPic"/>
        </a>
    <?php endif ?>
</div>
