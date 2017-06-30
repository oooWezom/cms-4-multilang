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
                            'name' => 'FORM[link_from]',
                            'value' => str_replace('http://'.Core\Arr::get($_SERVER, 'HTTP_HOST').'/', '', $obj->link_from),
                            'class' => 'link',
                        ], __('Ссылка с')); ?>
                        <div class="thisLink"><span class="mainLink"><?php echo 'http://'.Core\Arr::get($_SERVER, 'HTTP_HOST'); ?></span><span class="samaLink"></span></div>
                    </div>
                    <div class="form-group">
                        <?php echo \Forms\Builder::input([
                            'name' => 'FORM[link_to]',
                            'value' => str_replace('http://'.Core\Arr::get($_SERVER, 'HTTP_HOST').'/', '', $obj->link_to),
                            'class' => 'link',
                        ], __('Ссылка на')); ?>
                        <div class="thisLink"><span class="mainLink"><?php echo 'http://'.Core\Arr::get($_SERVER, 'HTTP_HOST'); ?></span><span class="samaLink"></span></div>
                    </div>
                    <div class="form-group">
                        <?php $options = []; ?>
                        <?php for($i = 300; $i <= 305; $i++): ?>
                            <?php $options[$i] = $i; ?>
                        <?php endfor; ?>
                        <?php $options[307] = 307; ?>
                        <?php echo \Forms\Builder::select($options, $obj->type, [
                                'name' => 'FORM[type]',
                        ], __('Тип')); ?>
                    </div>
                    <div class="widgetContent" style="min-height: 150px;">
                        <div class="relatedMessage form-vertical row-border">
                            <p><?php echo __('Список перенаправлений'); ?></p>
                            <ul>
                                <li>300 — <?php echo __('Multiple Choices («множество выборов»)'); ?></li>
                                <li>301 — <?php echo __('Moved Permanently («перемещено навсегда»)'); ?></li>
                                <li>302 — <?php echo __('Moved Temporarily («перемещено временно»)'); ?></li>
                                <li>303 — <?php echo __('See Other (смотреть другое)'); ?></li>
                                <li>304 — <?php echo __('Not Modified (не изменялось)'); ?></li>
                                <li>305 — <?php echo __('Use Proxy («использовать прокси»)'); ?></li>
                                <li>307 — <?php echo __('Temporary Redirect («временное перенаправление»)'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>

<script type="text/javascript">
    function generate_link(it) {
        var link = it.val();
        if(link != '') {
            if(link[0] != '/') {
                link = '/' + link;
            }
        }
        it.parent().find('.samaLink').text(link);
    }
    $(document).ready(function(){
        $('input.link').each(function(){
            generate_link($(this));
        });
        $('body').on('keyup', '.link', function(){ generate_link($(this)); });
        $('body').on('change', '.link', function(){ generate_link($(this)); });
    });
</script>