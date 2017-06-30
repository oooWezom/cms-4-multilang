<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <?php echo \Forms\Builder::bool($obj ? $obj->status : 1); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[name]',
                            'value' => $obj->name,
                            'class' => 'valid',
                        ], __('Название')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'id' => 'link',
                            'name' => 'FORM[link]',
                            'value' => str_replace('http://'.Core\Arr::get($_SERVER, 'HTTP_HOST').'/', '', $obj->link),
                            'class' => ['valid', 'translitSource'],
                        ], __('Ссылка')); ?>
                        <div class="thisLink"><span class="mainLink"><?php echo 'http://'.Core\Arr::get($_SERVER, 'HTTP_HOST'); ?></span><span class="samaLink"></span></div>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[h1]',
                            'value' => $obj->h1,
                        ], [
                            'text' => 'H1',
                            'tooltip' => __('Рекомендуется, чтобы тег h1 содержал ключевую фразу, которая частично или полностью совпадает с title'),
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[title]',
                            'value' => $obj->title,
                        ], [
                            'text' => 'Title',
                            'tooltip' => __('<p>Значимая для продвижения часть заголовка должна быть не более 12 слов</p><p>Самые популярные ключевые слова должны идти в самом начале заголовка и уместиться в первых 50 символов, чтобы сохранить привлекательный вид в поисковой выдаче.</p><p>Старайтесь не использовать в заголовке следующие знаки препинания – . ! ? – </p>'),
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::textarea([
                            'name' => 'FORM[keywords]',
                            'rows' => 5,
                            'value' => $obj->keywords,
                        ], [
                            'text' => 'Keywords',
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::textarea([
                            'name' => 'FORM[description]',
                            'value' => $obj->description,
                            'rows' => 5,
                        ], [
                            'text' => 'Description',
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::tiny([
                            'name' => 'FORM[text]',
                            'value' => $obj->text,
                        ], [
                            'text' => __('SEO text'),
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>

<script type="text/javascript">
    function generate_link() {
        var link = $('#link').val();
        if(link != '') {
            if(link[0] != '/') {
                link = '/' + link;
            }
        }
        $('.samaLink').text(link);
    }
    $(document).ready(function(){
        generate_link();
        $('body').on('keyup', '#link', function(){ generate_link(); });
        $('body').on('change', '#link', function(){ generate_link(); });
    });
</script>