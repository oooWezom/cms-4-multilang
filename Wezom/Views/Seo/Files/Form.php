<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <h2><?php echo $name; ?></h2>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::textarea([
                            'name' => 'FORM[text]',
                            'rows' => 30,
                            'value' => $text,
                        ], [
                            'text' => __('Содержание'),
                        ]); ?>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>