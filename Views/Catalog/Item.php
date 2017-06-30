<?php
use Core\HTML;
use Core\Widgets;
use Core\Config;
use Core\Support;

?>
<div class="fll">
    <div class="prevue_block clearFix">
        <div class="fll">
            <div class="prev3"><span></span></div>
            <div class="next3"><span></span></div>
            <ul>
                <?php foreach ($images as $im): ?>
                    <?php if (is_file(HOST . HTML::media('images/catalog/small/' . $im->image, false))): ?>
                        <li>
                            <div class="img_prevue"
                                 data-img-src="<?php echo HTML::media('images/catalog/big/' . $im->image); ?>"
                                 data-img-src-original="<?php echo HTML::media('images/catalog/original/' . $im->image); ?>">
                                <img src="<?php echo HTML::media('images/catalog/small/' . $im->image); ?>"/>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="prevue_block_in">
            <div class="big_prevue fresco" data-fresco-group="gr1"
                 href="<?php echo HTML::media('images/catalog/original/' . $images[0]->image) ?>">
                <?php if (is_file(HOST . HTML::media('images/catalog/big/' . $images[0]->image, false))): ?>
                    <img src="<?php echo HTML::media('images/catalog/big/' . $images[0]->image); ?>" alt=""/>
                <?php endif; ?>
                <?php echo Support::addItemTag($obj); ?>
            </div>
        </div>
    </div>
    <?php echo Widgets::get('Socials'); ?>
    <?php echo Widgets::get('Item_Comments'); ?>
</div>
<div class="flr">
    <div class="tovar_info">
        <div class="tovar_name"><h1><?php echo $obj->h1 ? $obj->h1 : $obj->name; ?></h1></div>
        <?php if ($obj->sale): ?>
            <div class="old_price2">
                <span><?php echo $obj->cost_old; ?></span> <?php echo __('грн'); ?>
            </div>
        <?php endif ?>
        <div class="tovar_price">
            <?php echo $obj->cost; ?> <span><?php echo __('грн'); ?></span>
        </div>
        <?php if ($obj->available == 0): ?>
            <div class="tovar_vNall">
                <p><?php echo __('товара нет в наличии'); ?></p>
            </div>
        <?php endif; ?>
        <?php if ($obj->available == 1): ?>
            <div class="tovar_vNall">
                <div class="vNall_img"></div>
                <p><?php echo __('товар в наличии'); ?></p>
                <p><?php echo Config::get('static.dostavka_est'); ?></p>
            </div>
        <?php endif; ?>
        <?php if ($obj->available == 2): ?>
            <div class="tovar_vNall noNall">
                <div class="vNall_img"></div>
                <p><?php echo __('товар под заказ'); ?></p>
                <?php echo Config::get('static.dostavka_bron'); ?>
            </div>
        <?php endif; ?>
        <div class="sel_but clearFix">
            <div class="fll"></div>
            <div class="flr">
                <a href="#" class="buy_but addToCart" data-id="<?php echo $obj->id; ?>"><span><?php echo __('В КОРЗИНУ'); ?></span></a>
                <a href="#enterReg5" class="popup_but enterReg5" data-id="<?php echo $obj->id; ?>"><span><?php echo __('КУПИТЬ В ОДИН КЛИК'); ?></span></a>
            </div>
        </div>
    </div>
    <div class="tovar_info2">
        <div class="middle_title"><?php echo __('характеристики товара'); ?></div>
        <div class="har_table">
            <table>
                <?php if ($obj->artikul): ?>
                    <tr>
                        <td><?php echo __('артикул'); ?></td>
                        <td><?php echo $obj->artikul; ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($obj->brand_name): ?>
                    <tr>
                        <td><?php echo __('бренд'); ?></td>
                        <td>
                            <a href="<?php echo HTML::link('brands/' . $obj->brand_alias); ?>"><?php echo $obj->brand_name; ?></a>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if ($obj->model_name): ?>
                    <tr>
                        <td><?php echo __('модель'); ?></td>
                        <td><?php echo $obj->model_name; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>пол</td>
                    <td><?php echo $obj->sex == 2 ? __('Унисекс') : ($obj->sex ? __('Женская модель') : __('Мужская модель')); ?></td>
                </tr>
                <?php foreach ($specifications as $name => $value): ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $value; ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
        <?php echo Widgets::get('Item_InfoItemPage'); ?>
        <?php echo Widgets::get('Item_QuestionAboutItem'); ?>
    </div>
</div>