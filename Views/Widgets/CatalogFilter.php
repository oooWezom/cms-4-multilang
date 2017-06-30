<?php
use Modules\Catalog\Models\Filter;
use Core\HTML;

?>
<div class="title_in"><?php echo __('фильтр'); ?></div>
<form action="">
    <ul class="main_filt">
        <?php if (count($brands)): ?>
            <li data-key="brand">
                <div class="filt_title"><?php echo __('бренды'); ?></div>
                <ul class="firm content mCustomScrollbar">
                    <?php foreach ($brands as $obj): ?>
                        <li><?php echo Filter::generateInput($filter, $obj, 'brand'); ?></li>
                    <?php endforeach ?>
                </ul>
            </li>
        <?php endif ?>
        <?php if (count($models)): ?>
            <li data-key="model">
                <div class="filt_title"><?php echo __('модель'); ?></div>
                <ul class="model content mCustomScrollbar">
                    <?php foreach ($models as $obj): ?>
                        <li><?php echo Filter::generateInput($filter, $obj, 'model'); ?></li>
                    <?php endforeach ?>
                </ul>
            </li>
        <?php endif; ?>
        <li data-key="available">
            <div class="filt_title"><?php echo __('наличие'); ?></div>
            <ul class="nall">
                <li><?php echo Filter::generateElseInput($filter, __('в наличии'), 1, 'available'); ?></li>
                <li><?php echo Filter::generateElseInput($filter, __('под заказ'), 2, 'available'); ?></li>
                <li><?php echo Filter::generateElseInput($filter, __('нет в наличии'), 0, 'available'); ?></li>
            </ul>
        </li>
        <?php if ($max): ?>
            <li data-key="cost">
                <div class="filt_title"><?php echo __('цена'); ?></div>
                <div id="slider-range"></div>
                <p class="price_ui">
                    <span>от</span>
                    <input type="text" id="amount" data-cost="<?php echo (int)$min; ?>"
                           value="<?php echo Filter::min($min); ?>">
                    <span><?php echo __('до'); ?></span>
                    <input type="text" id="amount2" data-cost="<?php echo (int)$max; ?>"
                           value="<?php echo Filter::max($max); ?>">
                    <span><?php echo __('грн.'); ?></span>
                </p>
                <a href="#" class="ok_price">OK</a>
            </li>
        <?php endif ?>
        <?php foreach ($specifications['list'] as $specification_alias => $specification_name): ?>
            <?php if (isset($specifications['values'][$specification_alias]) and count($specifications['values'][$specification_alias])): ?>
                <?php $spec = end($specifications['values'][$specification_alias]) ?>
                <li data-key="<?php echo $spec->specification_alias; ?>">
                    <div class="filt_title"><?php echo $spec->specification_name; ?></div>
                    <?php if ($spec->specification_type_id == 1): ?>
                        <ul class="color_c">
                            <?php foreach ($specifications['values'][$specification_alias] as $obj): ?>
                                <li><?php echo Filter::generateInput($filter, $obj, $obj->specification_alias, 'color'); ?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php else: ?>
                        <ul class="content mCustomScrollbar" data-key="<?php echo $obj->specification_alias; ?>">
                            <?php foreach ($specifications['values'][$specification_alias] as $obj): ?>
                                <li><?php echo Filter::generateInput($filter, $obj, $obj->specification_alias); ?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                </li>
            <?php endif ?>
        <?php endforeach ?>
    </ul>
    <div class="reset_block">
        <a href="<?php echo HTML::link('products/' . Core\Route::param('alias')); ?>" class="reset_but">
            <span><?php echo __('Сбросить ВСЕ фильтры'); ?></span>
        </a>
    </div>
</form>

<script>
    $(function () {
        $('label.checkBlock').on('click', function () {
            if ($(this).find('input').prop('disabled')) {
                return false;
            }
            window.location.href = $(this).find('a').attr('href');
        });
        $('.ok_price').on('click', function (e) {
            e.preventDefault();
            var min = $('#amount').val();
            var max = $('#amount2').val();
            var filter = [];
            $('ul.main_filt > li').each(function () {
                var it = $(this);
                var key = it.data('key');
                if (key == 'cost') {
                    filter.push('mincost-' + min);
                    filter.push('maxcost-' + max);
                } else {
                    var elements = [];
                    it.find('input[type="checkbox"]').each(function () {
                        if ($(this).prop('checked')) {
                            elements.push($(this).val());
                        }
                    });
                    if (elements.length) {
                        filter.push(key + '-' + elements.join('_'));
                    }
                }
            });
            if (!filter.length) {
                return false;
            }
            var uri = $('.reset_but').attr('href');
            window.location.href = uri + '/' + filter.join('/');
        });
    });
</script>