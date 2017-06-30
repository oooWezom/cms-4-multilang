<?php

use Forms\Builder;
use Forms\Form;
use Core\HTML;
use Core\Route;
use Core\Support;
?>
<?php echo Builder::open(['data-action' => 'blog']); ?>
<div class="form-actions" style="display: none;">
    <?php echo Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
</div>
<div class="rowSection">
    <div class="col-md-6">
        <div class="widget box loadedBox">
            <div class="widgetHeader myWidgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-file"></i>
                    <?php echo __('Основные данные'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="widgetContent" style="min-height: 150px;">
                        <div class="form-vertical row-border" id="itemPlace">
                            <?php if ($item): ?>
                                <?php if (is_file(HOST . HTML::media('images/blog/big/' . $item->image, false))): ?>
                                    <a rel="lightbox"
                                       href="<?php echo HTML::media('images/blog/big/' . $item->image); ?>">
                                        <img class="someImage"
                                             src="<?php echo HTML::media('images/blog/small/' . $item->image); ?>">
                                    </a>
                                <?php endif; ?>
                                <div class="someBlock">
                                    <a target="_blank"
                                       href="/wezom/blog/edit/<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
                                </div>
                                <?php if ($item->rubric): ?>
                                    <div class="someBlock">
                                        <b><?php echo __('Рубрика'); ?>:</b> <?php echo $item->rubric; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($item->date): ?>
                                    <div class="someBlock">
                                        <b><?php echo __('Дата публикации'); ?>
                                            :</b> <?php echo date('d.m.Y', $item->date); ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="relatedMessage"><?php echo __('Еще не выбрана ни одна запись из блога!'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <?php echo \Forms\Builder::bool($obj ? $obj->status : 1); ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo Builder::input([
                                'name' => 'FORM[date]',
                                'value' => $obj->date ? date('d.m.Y', $obj->date) : NULL,
                                'class' => ['valid', 'myPicker'],
                                    ], __('Дата комментария'));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo Builder::input([
                                'name' => 'FORM[name]',
                                'value' => $obj->name,
                                'class' => 'valid',
                                    ], __('Имя'));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo Builder::textarea([
                                'name' => 'FORM[text]',
                                'class' => 'valid',
                                'value' => $obj->text,
                                    ], __('Сообщение'));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo Builder::input([
                                'name' => 'FORM[date_answer]',
                                'value' => $obj->date_answer ? date('d.m.Y', $obj->date_answer) : NULL,
                                'class' => 'myPicker2',
                                    ], __('Дата ответа администратора'));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo Builder::textarea([
                                'name' => 'FORM[answer]',
                                'value' => $obj->answer,
                                    ], __('Ответ администратора'));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($obj->blog_id): ?>
                <input type="hidden" name="blog_id" id="orderItemId" value="<?php echo $obj->blog_id; ?>">
            <?php else: ?>
                <?php echo Form::hidden(['name' => 'blog_id', 'id' => 'orderItemId']); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="widget box loadedBox" id="orderItemsBlock">
            <div class="widgetHeader myWidgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-file"></i>
                    <?php echo __('Указать запись из блога'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div id="orderItemsBlock">
                        <div class="form-group" style="margin-top: 10px;">
                            <div class="col-md-5">
                                <?php
                                echo Builder::select(Support::selectData($rubrics, 'id', 'name', [
                                            0, ' - ' . __('Не выбрано') . ' - '
                                        ]), $obj->rubric_id, [
                                    'name' => 'rubric_id',
                                ]);
                                ?>
                            </div>
                            <div class="col-md-7">
                                <?php
                                echo Builder::input([
                                    'name' => 'search',
                                    'placeholder' => __('Начните вводить название записи'),
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="widgetContent" style="min-height: 150px;">
                            <div id="orderItemsList" class="form-vertical row-border"
                                 data-id="<?php echo Route::param('id'); ?>" data-limit="16">
                                <p class="relatedMessage"><?php echo __('Выберите рубрику или начните писать название записи в блоге в поле для ввода расположенном выше. После чего на этом месте появится список отфильтрованных записей'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<script>
    $(function () {
        var pickerInit = function (selector) {
            var date = $(selector).val();
            $(selector).datepicker({
                showOtherMonths: true,
                selectOtherMonths: false
            });
            $(selector).datepicker('option', $.datepicker.regional['ru']);
            var dateFormat = $(selector).datepicker("option", "dateFormat");
            $(selector).datepicker("option", "dateFormat", 'dd.mm.yy');
            $(selector).val(date);
        };
        pickerInit('.myPicker');
        pickerInit('.myPicker2');
    });
</script>