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
                    <ul class="liTabs t_wrap">
                        <?php foreach( $languages AS $key => $lang ): ?>
                            <?php $public = \Core\Arr::get($langs, $key, array()); ?>
                            <?php echo $lang['default'] == 1 ? '<input type="hidden" class="default_lang" value="'.$lang['name'].'">' : ''; ?>
                            <li class="t_item">
                                <a class="t_link" href="#"><?php echo $lang['name']; ?></a>
                                <div class="t_content">

                                    <div class="form-group">
                                        <?php echo \Forms\Builder::input([
                                            'name' => 'FORM['.$key.'][name]',
                                            'value' => $public->name,
                                            'class' => 'valid',
                                        ], __('Название')); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'id' => 'f_link',
                            'name' => 'FORM[url]',
                            'value' => $obj->url,
                            'class' => 'valid',
                        ], __('Ссылка')); ?>
                        <div class="thisLink"><span class="mainLink"><?php echo 'http://'.Core\Arr::get($_SERVER, 'HTTP_HOST'); ?></span><span class="samaLink"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>

<script type="text/javascript">
    function generate_link() {
        var link = $('#f_link').val();
        if(link != '') {
            if(link[0] != '/') {
                link = '/' + link;
            }
        }
        $('.samaLink').text(link);
    }
    $(document).ready(function(){
        generate_link();
        $('body').on('keyup', '#f_link', function(){ generate_link(); });
        $('body').on('change', '#f_link', function(){ generate_link(); });
    });
</script>