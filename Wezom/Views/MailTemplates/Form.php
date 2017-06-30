<?php

use Core\Arr;

/* @var $languages [] */
/* @var $obj [] */

?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <?php $langs = []; ?>
    <?php foreach ($languages as $key => $lang): ?>
        <?php $langs[$key] = $obj->$key; ?>
        <?php unset($obj->$key); ?>
    <?php endforeach; ?>`
<?php else: ?>
    <?php $langs = Arr::get($obj, 'langs', []); ?>
    <?php $obj = Arr::get($obj, 'obj', []); ?>
<?php endif; ?>
<form id="myForm" class="rowSection validate" method="post" action="">
    <div class="form-actions" style="display: none;">
        <input class="submit btn btn-primary pull-right" type="submit" value="<?php echo __('Отправить'); ?>">
    </div>
    <div class="col-md-7">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Основные данные'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="widgetContent">
                        <ul class="liTabs t_wrap">
                            <?php foreach ($languages as $key => $lang): ?>
                                <?php $public = Arr::get($langs, $key, []); ?>
                                <?php echo $lang['default'] == 1 ? '<input type="hidden" class="default_lang" value="' . $lang['name'] . '">' : ''; ?>
                                <li class="t_item">
                                    <a class="t_link" href="#"><?php echo $lang['name']; ?></a>
                                    <div class="t_content">
                                        <div class="form-group">
                                            <label class="control-label" for="f_theme"><?php echo __('Тема'); ?></label>
                                            <div class="">
                                                <input id="f_theme" class="form-control translitSource valid"
                                                       name="FORM[<?php echo $key; ?>][subject]" type="text"
                                                       value="<?php echo $public->subject; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"><?php echo __('Шаблон'); ?></label>
                                            <div class="">
                                                <textarea style="height: 350px;" class="tinymceEditor form-control"
                                                          rows="20"
                                                          name="FORM[<?php echo $key; ?>][text]"><?php echo $public->text; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Базовые настройки'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Наименование шаблона'); ?></label>
                        <div class="red" style="font-weight: bold;">
                            <?php echo $obj->name; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Опубликовано'); ?></label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="status" value="0"
                                       type="radio" <?php echo !$obj->status ? 'checked' : ''; ?>>
                                <?php echo __('Нет'); ?>
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="status" value="1"
                                       type="radio" <?php echo $obj->status ? 'checked' : ''; ?>>
                                <?php echo __('Да'); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Переменные'); ?>
                </div>
            </div>
            <div class="pageInfo alert alert-info">
                <div class="rowSection">
                    <div class="col-md-6"><strong><?php echo __('Доменное имя сайта'); ?></strong></div>
                    <div class="col-md-6">{{site}}</div>
                </div>
                <?php if ($obj->id != 15 && $obj->id != 20 && $obj->id != 21 && $obj->id != 26): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>IP</strong></div>
                        <div class="col-md-6">{{ip}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Текущая дата в формате dd.mm.YYYY'); ?></strong>
                        </div>
                        <div class="col-md-6">{{date}}</div>
                    </div>
                <?php endif; ?>
                <?php if ($obj->id == 1): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>E-Mail</strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Текст сообщения'); ?></strong></div>
                        <div class="col-md-6">{{text}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 2): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Ссылка для отмены рассылки'); ?></strong></div>
                        <div class="col-md-6">{{link}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>E-Mail</strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 3): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Номер телефона'); ?></strong></div>
                        <div class="col-md-6">{{phone}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 4): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Ссылка для подтверждения'); ?></strong></div>
                        <div class="col-md-6">{{link}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 5 OR $obj->id == 6): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Новый пароль для входа'); ?></strong></div>
                        <div class="col-md-6">{{password}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 7): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Город'); ?></strong></div>
                        <div class="col-md-6">{{city}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Текст коментария'); ?></strong></div>
                        <div class="col-md-6">{{text}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>E-Mail</strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Оценка'); ?></strong></div>
                        <div class="col-md-6">{{mark}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 8 || $obj->id == 9): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Фамилия'); ?></strong></div>
                        <div class="col-md-6">{{last_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>E-Mail</strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Город'); ?></strong></div>
                        <div class="col-md-6">{{city}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Комментарий'); ?></strong></div>
                        <div class="col-md-6">{{comment}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Телефон'); ?></strong></div>
                        <div class="col-md-6">{{phone}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Способ оплаты'); ?></strong></div>
                        <div class="col-md-6">{{payment}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Способ доставки'); ?></strong></div>
                        <div class="col-md-6">{{delivery}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Название сертификата'); ?></strong></div>
                        <div class="col-md-6">{{item_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Цена сертификата'); ?></strong></div>
                        <div class="col-md-6">{{price}}</div>
                    </div>
                    <!-- <div class="rowSection">
                        <div class="col-md-6"><strong><?php //echo __('Количество'); ?></strong></div>
                        <div class="col-md-6">{{count}}</div>
                    </div> -->
                    <?php if ($obj->id == 9): ?>
                        <div class="rowSection">
                            <div class="col-md-6"><strong><?php echo __('Код'); ?></strong></div>
                            <div class="col-md-6">{{code}}</div>
                        </div>
                    <?php endif ?>
                <?php endif ?>
                <?php if ($obj->id == 11 OR $obj->id == 12): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Фамилия'); ?></strong></div>
                        <div class="col-md-6">{{last_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>E-Mail</strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Город'); ?></strong></div>
                        <div class="col-md-6">{{city}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Комментарий'); ?></strong></div>
                        <div class="col-md-6">{{comment}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Телефон'); ?></strong></div>
                        <div class="col-md-6">{{phone}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Способ оплаты'); ?></strong></div>
                        <div class="col-md-6">{{payment}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Способ доставки'); ?></strong></div>
                        <div class="col-md-6">{{delivery}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Список заказаных товаров'); ?></strong></div>
                        <div class="col-md-6">{{items}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6">
                            <strong><?php echo __('Ссылка на заказ в кабинете пользователя'); ?></strong></div>
                        <div class="col-md-6">{{link_user}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Ссылка на заказ в админ-панели'); ?></strong></div>
                        <div class="col-md-6">{{link_admin}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 15): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Номер заказа'); ?></strong></div>
                        <div class="col-md-6">{{id}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Фамилия'); ?></strong></div>
                        <div class="col-md-6">{{last_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Отчество'); ?></strong></div>
                        <div class="col-md-6">{{middle_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Список заказаных товаров'); ?></strong></div>
                        <div class="col-md-6">{{items}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Стоимость товаров'); ?></strong></div>
                        <div class="col-md-6">{{amount}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Скидка'); ?></strong></div>
                        <div class="col-md-6">{{discount}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Стоимость заказа с учетом скидки'); ?></strong>
                        </div>
                        <div class="col-md-6">{{real_amount}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 20 || $obj->id == 21): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя, указанное при заказе'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Фамилия, указанное при заказе'); ?></strong></div>
                        <div class="col-md-6">{{last_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Номер заказа'); ?></strong></div>
                        <div class="col-md-6">{{id}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Сумма заказа'); ?></strong></div>
                        <div class="col-md-6">{{amount}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 26): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>E-Mail</strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Пароль'); ?></strong></div>
                        <div class="col-md-6">{{password}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 27): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Указанный E-Mail'); ?></strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Наименование товара'); ?></strong></div>
                        <div class="col-md-6">{{item_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Цена товара'); ?></strong></div>
                        <div class="col-md-6">{{price}}</div>
                    </div>
                <?php endif ?>
                <?php if ($obj->id == 28): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Указанный номер телефона'); ?></strong></div>
                        <div class="col-md-6">{{phone}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Наименование товара'); ?></strong></div>
                        <div class="col-md-6">{{item_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Цвет'); ?></strong></div>
                        <div class="col-md-6">{{color_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Размер'); ?></strong></div>
                        <div class="col-md-6">{{size_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Количество'); ?></strong></div>
                        <div class="col-md-6">{{count}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Ссылка на товар на сайте'); ?></strong></div>
                        <div class="col-md-6">{{link}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Ссылка на товар в админ-панели'); ?></strong></div>
                        <div class="col-md-6">{{link_admin}}</div>
                    </div>
                <?php endif ?>

                <?php if ($obj->id == 29 || $obj->id == 30): ?>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Фамилия'); ?></strong></div>
                        <div class="col-md-6">{{last_name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Имя'); ?></strong></div>
                        <div class="col-md-6">{{name}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Список заказаных товаров'); ?></strong></div>
                        <div class="col-md-6">{{items}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Общая стоимость всех товаров'); ?></strong></div>
                        <div class="col-md-6">{{amount}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Номер телефона'); ?></strong></div>
                        <div class="col-md-6">{{phone}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>E-Mail</strong></div>
                        <div class="col-md-6">{{email}}</div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong><?php echo __('Адрес доставки'); ?></strong></div>
                        <div class="col-md-6">{{address}}</div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</form>