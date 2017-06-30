<?php
use Forms\Builder;
use Forms\Form;
use Core\Arr;

echo Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <ul class="liTabs t_wrap">
                        <?php foreach( $languages as $key => $lang ): ?>
                            <?php $public = Arr::get($langs, $key, []); ?>
                            <?php echo $lang['default'] == 1 ? '<input type="hidden" class="default_lang" value="'.$lang['name'].'">' : ''; ?>
                            <li class="t_item">
                                <a class="t_link" href="#"><?php echo $lang['name']; ?></a>
                                <div class="t_content">
                                    <div class="form-group">
                                        <?php echo Builder::input([
                                            'name' => 'FORM['.$key.'][name]',
                                            'value' => $public->name,
                                            'class' => ['valid',  $lang['default'] == 1 ? 'translitSource' : ''],
                                            'data-trans' => 'specification'
                                        ], __('Название')); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <?php echo Builder::bool($obj ? $obj->status : 1); ?>
                    </div>
                    <div class="form-group">
                        <?php echo Builder::alias([
                            'name' => 'FORM[alias]',
                            'value' => $obj->alias,
                            'class' => 'valid',
							'data-trans' => 'specification'
                        ], [
                            'text' => __('Алиас'),
                            'tooltip' => __('<b>Алиас (англ. alias - псевдоним)</b><br>Алиасы используются для короткого именования страниц. <br>Предположим, имеется страница с псевдонимом «<b>about</b>». Тогда для вывода этой страницы можно использовать или полную форму: <br><b>http://domain/?go=frontend&page=about</b><br>или сокращенную: <br><b>http://domain/about.html</b>'),
                        ],[
							'data-rules' => 'only_chars_and_numbers', 
							'data-trans' => 'specification', 
						]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo Builder::select($types,
                            $obj->type_id, [
                                'name' => 'FORM[type_id]',
                                'class' => 'valid',
                            ], __('Тип')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo Form::close(); ?>