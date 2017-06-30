<?php use Core\HTML; ?>
<?php echo $text; ?>
<?php if (count($kids)): ?>
    <ul class="faq_block wTxt">
        <?php foreach ($kids as $obj): ?>
            <li>
                <a href="<?php echo HTML::link($obj->alias); ?>"><?php echo $obj->name; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>