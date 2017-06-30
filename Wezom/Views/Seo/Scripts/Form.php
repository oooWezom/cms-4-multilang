<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => 'Отправить', 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <?php echo \Forms\Builder::bool($obj ? $obj->status : 1); ?>
                    </div>
                    <div class="form-group">
                        <?php $options = [
                            'head' => __('Вставить перед </head>'),
                            'body' => __('Вставить после <body>'),
                            'counter' => __('Счетчик (в футере)'),
                        ]; ?>
                        <?php echo \Forms\Builder::select($options,
                            $obj->place, [
                                'name' => 'FORM[place]',
                                'class' => 'valid',
                            ], __('Место расположения')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[name]',
                            'value' => $obj->name,
                            'class' => 'valid',
                        ], __('Название')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::textarea([
                            'name' => 'FORM[script]',
                            'value' => $obj->script,
                            'rows' => 20,
                        ], __('Код')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>