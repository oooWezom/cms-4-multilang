<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => 'Отправить', 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Основные данные'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <?php echo \Forms\Builder::bool($obj ? $obj->status : 1); ?>
                    </div>
                    <div class="form-group">
                        <div class="rowSection">
                            <div class="col-md-6 form-group">
                                <?php echo \Forms\Builder::input([
                                    'name' => 'FORM[ip]',
                                    'value' => $obj->ip,
                                    'class' => 'valid',
                                ], 'IP'); ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <?php echo \Forms\Builder::input([
                                    'name' => 'FORM[date]',
                                    'value' => $obj->date ? date('d.m.Y', $obj->date) : NULL,
                                    'class' => 'myPicker',
                                ], 'Дата'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::textarea([
                            'value' => $obj->comment,
                            'name' => 'FORM[comment]',
                            'class' => 'valid',
                        ], 'Комментарий'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>

<script>
    $(function(){
        var pickerInit = function( selector ) {
            var date = $(selector).val();
            $(selector).datepicker({
                showOtherMonths: true,
                selectOtherMonths: false
            });
            $(selector).datepicker('option', $.datepicker.regional['ru']);
            var dateFormat = $(selector).datepicker( "option", "dateFormat" );
            $(selector).datepicker( "option", "dateFormat", 'dd.mm.yy' );
            $(selector).val(date);
        };
        pickerInit('.myPicker');
    });
</script>