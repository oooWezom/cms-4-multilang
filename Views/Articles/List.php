<?php use Core\HTML;
use Core\Text; ?>
<?php foreach ($result as $obj): ?>
    <div class="news clearFix">
        <?php if (is_file(HOST . HTML::media('/images/articles/big/' . $obj->image, false))): ?>
            <div class="fll">
                <a href="<?php echo HTML::link('articles/' . $obj->alias); ?>" class="news_img">
                    <img src="<?php echo HTML::media('/images/articles/big/' . $obj->image); ?>" alt=""/>
                </a>
            </div>
        <?php endif ?>
        <div class="flr">
            <a href="<?php echo HTML::link('articles/' . $obj->alias); ?>"
               class="name_news"><?php echo $obj->name; ?></a>
            <p><?php echo Text::limit_words(strip_tags($obj->text), 100); ?></p>
            <a href="<?php echo HTML::link('articles/' . $obj->alias); ?>" class="slide_but"><?php echo __('подробнее') ?></a>
        </div>
    </div>
<?php endforeach; ?>
<?php echo $pager; ?>