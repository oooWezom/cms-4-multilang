<?php
use Core\HTML;

// Beginning group of pages: $n1...$n2
$n1 = 1;
$n2 = min($_count_out, $_total_pages);

// Ending group of pages: $n7...$n8
$n7 = max(1, $_total_pages - $_count_out + 1);
$n8 = $_total_pages;

// Middle group of pages: $n4...$n5
$n4 = max($n2 + 1, $_current - $_count_in);
$n5 = min($n7 - 1, $_current + $_count_in);
$use_middle = ($n5 + 1 >= $n4);

// Point $n3 between $n2 and $n4
$n3 = (int)(($n2 + $n4) / 2);
$use_n3 = ($use_middle && (($n4 - $n2) > 1));

// Point $n6 between $n5 and $n7
$n6 = (int)(($n5 + $n7) / 2);
$use_n6 = ($use_middle && (($n7 - $n5) > 1));

// Links to display as array(page => content)
$links = [];

// Generate links data in accordance with calculated numbers
for ($i = $n1; $i <= $n2; $i++) {
    $links[$i] = $i;
}
if ($use_n3) {
    $links[$n3] = '&hellip;';
}
for ($i = $n4; $i <= $n5; $i++) {
    $links[$i] = $i;
}
if ($use_n6) {
    $links[$n6] = '&hellip;';
}
for ($i = $n7; $i <= $n8; $i++) {
    $links[$i] = $i;
}
?>
<div class="pagination">
    <?php if ($_navigation): ?>
        <?php if ($_previous !== false): ?>
            <a href="<?php echo HTML::chars($page->url($_previous)) ?>" rel="prev">Предыдущая</a>
        <?php endif ?>
    <?php endif ?>

    <?php foreach ($links as $number => $content): ?>
        <?php if ($number === $_current): ?>
            <a href="<?php echo HTML::chars($page->url($number)) ?>" class="active_pag"><?php echo $content ?></a>
        <?php else: ?>
            <a href="<?php echo HTML::chars($page->url($number)) ?>"><?php echo $content ?></a>
        <?php endif ?>
    <?php endforeach ?>

    <?php if ($_navigation): ?>
        <?php if ($_next !== false): ?>
            <a href="<?php echo HTML::chars($page->url($_next)) ?>" rel="next">Следующая</a>
        <?php endif ?>
    <?php endif ?>
</div>