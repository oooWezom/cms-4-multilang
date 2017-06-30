<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
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

                    <ul class="liTabs t_wrap">
                        <?php foreach( $languages AS $key => $lang ): ?>
                            <?php $public = \Core\Arr::get($langs, $key, array()); ?>
                            <?php echo $lang['default'] == 1 ? '<input type="hidden" class="default_lang" value="'.$lang['name'].'">' : ''; ?>
                            <li class="t_item">
                                <a class="t_link" href="#"><?php echo $lang['name']; ?></a>
                                <div class="t_content">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo __('Название'); ?></label>
                                        <b class="red"><?php echo $public->name; ?></b>
                                    </div>
                                    <div class="form-group">
                                        <?php echo \Forms\Builder::tiny([
                                            'name' => 'FORM['.$key.'][text]',
                                            'value' => $public->text,
                                            'id' => $key,
                                        ], __('Контент')); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if($obj->alias == 'contact'): ?>
                        <div class="form-group">
                            <?php echo \Forms\Builder::textarea([
                                'name' => 'FORM[other]',
                                'value' => $obj->other,
                                'rows' => 7,
                            ], __('Карта')); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-reorder"></i>
                    <?php echo __('Мета-данные'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <ul class="liTabs t_wrap">
                        <?php foreach( $languages AS $key => $lang ): ?>
                            <?php $public = \Core\Arr::get($langs, $key, array()); ?>
                            <?php echo $lang['default'] == 1 ? '<input type="hidden" class="default_lang" value="'.$lang['name'].'">' : ''; ?>
                            <li class="t_item">
                                <a class="t_link" href="#"><?php echo $lang['name']; ?></a>
                                <div class="t_content">
                                    <div class="form-group">
                                        <?php echo \Forms\Builder::input([
                                            'name' => 'FORM['.$key.'][h1]',
                                            'value' => $public->h1,
                                        ], [
                                            'text' => 'H1',
                                            'tooltip' => __('Рекомендуется, чтобы тег h1 содержал ключевую фразу, которая частично или полностью совпадает с title'),
                                        ]); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo \Forms\Builder::input([
                                            'name' => 'FORM['.$key.'][title]',
                                            'value' => $public->title,
                                        ], [
                                            'text' => 'Title',
                                            'tooltip' => __('<p>Значимая для продвижения часть заголовка должна быть не более 12 слов</p><p>Самые популярные ключевые слова должны идти в самом начале заголовка и уместиться в первых 50 символов, чтобы сохранить привлекательный вид в поисковой выдаче.</p><p>Старайтесь не использовать в заголовке следующие знаки препинания – . ! ? – </p>'),
                                        ]); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo \Forms\Builder::textarea([
                                            'name' => 'FORM['.$key.'][keywords]',
                                            'rows' => 5,
                                            'value' => $public->keywords,
                                        ], [
                                            'text' => 'Keywords',
                                        ]); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo \Forms\Builder::textarea([
                                            'name' => 'FORM['.$key.'][description]',
                                            'value' => $public->description,
                                            'rows' => 5,
                                        ], [
                                            'text' => 'Description',
                                        ]); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>