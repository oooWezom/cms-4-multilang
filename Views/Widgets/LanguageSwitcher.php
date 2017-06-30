<?php
/**
 * @var array $languages
 * @var string $current
 */
?>
<div>
    <?php foreach ($languages as $language) : ?>
        <a href="<?php echo I18n::switcherLink($language['alias']); ?>"
           class="<?php echo $language['alias'] == $current ? 'current' : null; ?>"
           title="<?php echo $language['short_name']; ?>"><?php echo $language['short_name']; ?></a>
    <?php endforeach; ?>
</div>
