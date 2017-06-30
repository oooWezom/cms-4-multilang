<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
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
                    <?php foreach( $languages AS $key => $lang ): ?>
                        <?php $public = \Core\Arr::get($langs, $key, array()); ?>
                        <?php echo $lang['default'] == 1 ? '<input type="hidden" class="default_lang" value="'.$lang['name'].'">' : ''; ?>                        
                        <div class="form-group">
                            <?php echo \Forms\Builder::input([
                                'name' => 'FORM['.$key.'][name]',
                                'value' => $public->name,
                            ], __('Название').' ['.$key.']'); ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[url]',
                            'value' => $obj->url,
                        ], __('Ссылка')); ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div>
                            <?php if (is_file( HOST . Core\HTML::media('images/slider/big/'.$obj->image, false) )): ?>
                                <div class="contentImageView">
                                    <a href="<?php echo Core\HTML::media('images/slider/big/'.$obj->image); ?>" class="mfpImage">
                                        <img src="<?php echo Core\HTML::media('images/slider/small/'.$obj->image); ?>" />
                                    </a>
                                </div>
                                <div class="contentImageControl">
                                    <a class="btn btn-danger otherBtn" href="/wezom/<?php echo Core\Route::controller(); ?>/delete_image/<?php echo $obj->id; ?>">
                                        <i class="fa fa-remove"></i>
                                        <?php echo __('Удалить изображение'); ?>
                                    </a> 
                                    <br>                                   
                                    <a class="btn btn-warning otherBtn" href="<?php echo \Core\General::crop('slider', 'small', $obj->image); ?>">
                                        <i class="fa fa-pencil"></i>
                                        <?php echo __('Редактировать'); ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <?php echo \Forms\Builder::file(['name' => 'file']); ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>