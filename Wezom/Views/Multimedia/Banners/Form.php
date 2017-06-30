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
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[text]',
                            'value' => $obj->text,
                        ], __('Описание')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[url]',
                            'value' => $obj->url,
                        ], __('Ссылка')); ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div>
                            <?php if (is_file( HOST.Core\HTML::media('images/banners/'.$obj->image, false) )): ?>
                                <div class="contentImageView">
                                    <a href="<?php echo Core\HTML::media('images/banners/'.$obj->image); ?>" class="mfpImage">
                                        <img src="<?php echo Core\HTML::media('images/banners/'.$obj->image); ?>" />
                                    </a>                                    
                                </div>
                                <div class="contentImageControl">
                                    <a class="btn btn-danger otherBtn" href="/wezom/<?php echo Core\Route::controller(); ?>/delete_image/<?php echo $obj->id; ?>">
                                        <i class="fa fa-remove"></i>
                                        <?php echo __('Удалить изображение'); ?>
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