<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="rowSection">
        <div class="col-md-7">
            <div class="widget box loadedBox">
                <div class="widgetHeader myWidgetHeader">
                    <div class="widgetTitle">
                        <i class="fa fa-file"></i>
                        <?php echo __('Добавить товар в заказ'); ?>
                    </div>
                </div>
                <div class="widgetContent">
                    <div class="form-vertical row-border">
                        <div id="orderItemsBlock">
                            <div class="form-group" style="margin-top: 10px;">
                                <div class="col-md-5">
                                    <?php echo \Forms\Builder::select('<option value="0"> - ' . __('Не выбрано') . ' - </option>'.$tree,
                                        NULL,
                                        ['data-name' => 'parent_id']
                                    ); ?>
                                </div>
                                <div class="col-md-7">
                                    <?php echo \Forms\Builder::input([
                                        'data-name' => 'search',
                                        'placeholder' => __('Начните вводить название или артикул товара'),
                                    ]); ?>
                                </div>
                            </div>
                            <div class="widgetContent" style="min-height: 150px;">
                                <div id="orderItemsList" class="form-vertical row-border" data-id="<?php echo \Core\Route::param('id'); ?>" data-limit="5">
                                    <p class="relatedMessage"><?php echo __('Выберите группу или начните писать название товара или артикул в поле для ввода расположенном выше. После чего на этом месте появится список товаров'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="widget box loadedBox">
                <div class="widgetHeader myWidgetHeader">
                    <div class="widgetTitle">
                        <i class="fa fa-file"></i>
                        <?php echo __('Выбранный товар'); ?>
                    </div>
                </div>
                <div class="widgetContent" id="orderItemsBlock">
                    <div class="form-vertical row-border">
                        <div class="widgetContent" style="min-height: 150px;">
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="f_count"><?php echo __('Количество'); ?></label>
                                <div class="col-md-10">
                                    <input id="f_count" class="form-control valid" type="number" min="0" max="" name="count" value="1" />
                                </div>
                            </div>
                            <div class="form-vertical row-border" id="itemPlace">
                                <p class="relatedMessage"><?php echo __('Еще не выбран ни один товар!'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo \Forms\Builder::hidden([
                    'name' => 'catalog_id',
                    'id' => 'orderItemId',
                ]); ?>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>