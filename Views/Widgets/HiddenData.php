<?php 
use Core\Arr;
use Core\HTML;
use Core\Route;
use Core\Config;
?>
<div style="display: none;">
    <!-- Basket -->
    <div id="orderBasket" class="wBasket wBasketModule wb_animate">
        <div class="wBasketWrapp">
            <div class="wBasketHead">
                <div class="wBasketTTL"><?php echo __('Корзина')?></div>
            </div>
            <div class="wBasketBody">
                <ul class="wBasketList" id="topCartList">
                    <?php $amount = 0; ?>
                    <?php foreach ($cart as $key => $item): ?>
                        <?php $obj = Arr::get($item, 'obj'); ?>
                        <?php if ($obj): ?>
                            <li class="wb_item" data-id="<?php echo $obj->id; ?>"
                                data-count="<?php echo Arr::get($item, 'count', 1) ?>"
                                data-price="<?php echo $obj->cost; ?>">
                                <div class="wb_li">
                                    <?php if (is_file(HOST . HTML::media('/images/catalog/medium/' . $obj->image, false))): ?>
                                        <div class="wb_side">
                                            <div class="wb_img">
                                                <a href="<?php echo HTML::link($obj->alias . '/p' . $obj->id); ?>"
                                                   class="wbLeave">
                                                    <img src="<?php echo HTML::media('images/catalog/medium/' . $obj->image); ?>"/>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="wb_content">
                                        <div class="wb_row">
                                            <div class="wb_del"><span title="<?php echo __('Удалить товар')?>"><?php echo __('Удалить товар')?></span></div>
                                            <div class="wb_ttl">
                                                <a href="<?php echo HTML::link($obj->alias . '/p' . $obj->id); ?>"
                                                   class="wbLeave">
                                                    <?php echo $obj->name; ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="wb_cntrl">
                                            <div class="wb_price_one"><p><span><?php echo $obj->cost; ?></span> <?php echo __('грн.')?></p>
                                            </div>
                                            <div class="wb_amount_wrapp">
                                                <div class="wb_amount">
                                                    <input type="text" class="editCountItem"
                                                           value="<?php echo Arr::get($item, 'count', 1); ?>">
                                                    <span data-spin="plus" class="editCountItem"></span>
                                                    <span data-spin="minus" class="editCountItem"></span>
                                                </div>
                                            </div>
                                            <div class="wb_price_totl"><p>
                                                <span><?php echo $obj->cost * Arr::get($item, 'count', 1); ?></span>
                                                <?php echo __('грн.')?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php $amount += $obj->cost * Arr::get($item, 'count', 1); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!-- ▼ итог корзины ▼ -->
            <div class="wBasketFooter">
                <div class="wb_footer">
                    <div class="tar wb_footer_tot">
                        <div class="wb_total"><?php echo __('Итого:');?> <span id="topCartAmount"><?php echo $amount; ?></span> <?php echo __('грн.');?></div>
                    </div>
                    <div class="flr wb_footer_go">
                        <div class="wb_gobasket">
                            <a href="<?php echo HTML::link('cart'); ?>" class="wb_butt"><span><?php echo __('Оформить заказ');?></span></a>
                        </div>
                    </div>
                    <div class="fll wb_footer_go">
                        <div class="wb_goaway wbLeave">
                            <a href="#" class="wb_close_init wb_butt"><span><?php echo __('продолжить покупки')?></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ▼ дополнения к корзине ▼ -->
            <!-- <div class="wBasketAddons"></div> -->
        </div>
    </div>
    <div id="enterReg" class="animate_zoom">
        <div class="enterReg_top">
            <div class="enterBlock">
                <!-- Enter site -->
                <div class="title"><?php echo __('Вход на сайт');?></div>
                <div id="entrForm" form="true" class="wForm enterBlock_form visForm" data-ajax="login">
                    <div class="wFormRow">
                        <input type="email" name="email" data-name="email" data-rule-email="true" placeholder="<?php echo __('E-mail');?>"
                               data-rule-required="true"/>
                        <label><?php echo __('E-mail');?></label>
                    </div>
                    <div class="wFormRow">
                        <input type="password" name="password" data-name="password" minlength="<?php echo Config::get('main.password_min_length'); ?>" placeholder="<?php echo __('Пароль');?>"
                               data-rule-required="true"/>
                        <label><?php echo __('Пароль');?></label>
                    </div>
                    <label class="checkBlock">
                        <input type="checkbox" checked="checked" name="remember" data-name="remember" value="1"/>
                        <ins></ins>
                        <p><?php echo __('Запомнить данные');?></p>
                    </label>
                    <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
                    <?php if (array_key_exists('token', $_SESSION)): ?>
                        <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
                    <?php endif; ?>
                    <div class="passLink" id="forget_pass"><?php echo __('Забыли пароль?');?></div>
                    <div class="tar">
                        <button class="wSubmit enterReg_btn"><?php echo __('войти');?></button>
                    </div>
                </div>
                <!-- Forgot password -->
                <div id="forgetForm" form="true" class="wForm enterBlock_form" data-ajax="forgot_password">
                    <div class="wFormRow">
                        <input type="email" data-name="email" name="email" data-rule-email="true" placeholder="<?php echo __('E-mail');?>"
                               data-rule-required="true">
                        <label><?php echo __('E-mail');?></label>
                    </div>
                    <div class="forgetInf">
                        <?php echo __('После отправления, в течении 5 минут к Вам на почту придут инструкции по восстановлению пароля.');?>
                    </div>
                    <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
                    <?php if (array_key_exists('token', $_SESSION)): ?>
                        <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
                    <?php endif; ?>
                    <div class="passLink" id="remember_pass"><?php echo __('Вернуться');?></div>
                    <div class="tar">
                        <button class="wSubmit enterReg_btn"><?php echo __('отправить');?></button>
                    </div>
                </div>
            </div>
            <!-- Registration -->
            <div form="true" class="wForm regBlock " data-ajax="registration">
                <div class="title"><?php echo __('Новый пользователь');?></div>
                <div class="wFormRow">
                    <input type="text" data-name="email" name="email" data-rule-email="true" placeholder="<?php echo __('E-mail');?>"
                           data-rule-required="true"/>
                    <label><?php echo __('E-mail');?></label>
                </div>
                <div class="wFormRow">
                    <input type="password" data-name="password" name="password" minlength="<?php echo Config::get('main.password_min_length'); ?>" placeholder="<?php echo __('Пароль');?>"
                           data-rule-required="true"/>
                    <label><?php echo __('Пароль');?></label>
                </div>
                <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
                <?php if (array_key_exists('token', $_SESSION)): ?>
                    <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
                <?php endif; ?>
                <label class="checkBlock">
                    <input type="checkbox" data-name="agree" name="agree" data-rule-required="true" value="1"/>
                    <ins></ins>
                    <p><?php echo __('Я согласен с условиями использования и обработку моих персональных данных');?></p>
                </label>
                <div class="tar">
                    <button class="wSubmit enterReg_btn"><?php echo __('зарегистрироваться');?></button>
                </div>
            </div>
        </div>
        <!-- Enter by social networks -->
        <div class="socEnter">
            <p><?php echo __('Вход через');?></p>
            <div class="socLinkEnter">
                <div id="uLogin"
                     data-ulogin="display=small;fields=first_name,last_name,email;providers=vkontakte,facebook,odnoklassniki,mailru;hidden=;redirect_uri=http%3A%2F%2F<?php echo $_SERVER['HTTP_HOST']; ?>%2Faccount%2Flogin-by-social-network"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- Callback -->
    <div id="enterReg2" class="animate_zoom">
        <div class="enterReg_top">
            <div form="true" class="wForm regBlock" data-ajax="callback">
                <div class="title"><?php echo __('Заказ звонка');?></div>
                <div class="wFormRow">
                    <input type="text" data-name="name" name="name" data-rule-bykvu="true" placeholder="<?php echo __('Имя');?>"
                           data-rule-minlength="2" data-rule-required="true">
                    <label><?php echo __('Имя');?></label>
                </div>
                <div class="wFormRow">
                    <input type="tel" class="tel" data-name="phone" name="phone" data-rule-phoneUA="true" maxlength="19"
                           minlength="19" placeholder="<?php echo __('Телефон');?>" data-rule-required="true">
                    <label><?php echo __('Телефон');?></label>
                </div>
                <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
                <?php if (array_key_exists('token', $_SESSION)): ?>
                    <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
                <?php endif; ?>
                <div class="tar">
                    <button class="wSubmit enterReg_btn"><?php echo __('заказать звонок');?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick order -->
    <div id="enterReg5" class="animate_zoom">
        <div class="enterReg_top">
            <div form="true" class="wForm regBlock" data-ajax="order_simple">
                <div class="title"><?php echo __('Быстрый заказ');?></div>
                <div class="wFormRow">
                    <input type="tel" class="tel" data-name="phone" name="phone" data-rule-phoneUA="true" maxlength="19"
                           minlength="19" placeholder="<?php echo __('Телефон');?>" data-rule-required="true">
                    <label><?php echo __('Телефон');?></label>
                </div>
                <input type="hidden" data-name="lang" value="<?php echo \I18n::$lang; ?>">
                <?php if (array_key_exists('token', $_SESSION)): ?>
                    <input type="hidden" data-name="token" value="<?php echo $_SESSION['token']; ?>"/>
                <?php endif; ?>
                <input type="hidden" data-name="id" id="idFastOrder" name="id"
                       value="<?php echo Route::param('id'); ?>"/>
                <div class="tar">
                    <button class="wSubmit enterReg_btn"><?php echo __('отправить');?></button>
                </div>
            </div>
        </div>
    </div>
</div>