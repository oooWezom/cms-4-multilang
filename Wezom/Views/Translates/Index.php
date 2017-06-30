<?php
/* @var $languages [] */
/* @var $frontend boolean */
/* @var $limit integer */
/* @var $offset integer */
?>
<div class="widget box">
    <div class="widgetContent">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered" id="translateFilenameBackend">
            <thead>
            <tr>
                <th><?php echo __('Ключ'); ?></th>
                <?php foreach( $languages AS $_key => $lang ): ?>
                    <th data-lang="<?php echo $_key; ?>"><?php echo $lang['name']; ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
        </table>
    </div>
</div>

<span class="btn btn-inverse" id="addTranslation"><?php echo __('Добавить перевод'); ?></span>
<span class="btn btn-inverse" id="updateTranslation"><?php echo __('Обновить таблицу переводов'); ?></span>

<script>
    $('.table').dataTable({
        language: {
            url: '/Wezom/Media/js/<?php echo I18n::$lang?>.json'
        },
        ajax: {
            url: '/wezom/<?php echo $frontend ? 'translates' : 'btranslates'; ?>',
            type: "POST",
            dataType: "JSON"
        },
        processing: true,
        serverSide: true,
        pageLength: <?php echo $limit; ?>,
        displayStart: <?php echo $offset; ?>,
        drawCallback: function () {
            $('.table tbody tr').each(function(){
                $(this).find('td').each(function(i, el){
                    var lang = $('.table thead tr th:nth-child(' + (i + 1) + ')').data('lang');
                    if (lang) {
                        $(this).addClass('qe');
                        $(this).data('lang', lang);
                    }
                });
            });
            $('.qe').liQuickEdit({
                qeOpen: function (el, text) {},
                qeClose: function (el, text) {
                    $.ajax({
                        url: '/wezom/ajax/<?php echo $frontend ? 'saveTranslation' : 'saveTranslationBackend'; ?>',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            key: el.closest('tr').find('td:first-child').text(),
                            value: text,
                            lang: el.data('lang'),
                            filename: $('#translateFilename').data('filename')
                        }
                    });
                }
            })
        }
    });
    $(function(){
        $('#addTranslation').on('click', function(e){
            var html = '<div id="translatesForm">';
            html += '<div class="form-group"><label class="control-label" for="f_key"><?php echo __('Ключ'); ?></label><div class=""><input id="f_key" class="form-control" data-name="key" type="text" /></div></div>';
            <?php foreach( $languages AS $key => $lang ): ?>
            html += '<div class="form-group"><label class="control-label" for="f_<?php echo $key; ?>"><?php echo $lang['name']; ?></label><div class=""><input id="f_<?php echo $key; ?>" class="form-control" data-name="<?php echo $key; ?>" type="text" /></div></div>';
            <?php endforeach; ?>
            html += '</div>';
            $(document).alert2({
                message: html,
                openCallback: function(){},
                closeCallback: function(){},
                headerCOntent: '<?php echo __('Укажите ключ и переводы'); ?>',
                footerContent: '<button type="button" class="btn btn-primary" id="addPleaseThisTranslates"><?php echo __('Сохранить'); ?></button>',
                typeMessage: false
            });
        });
        $('body').on('click', '#addPleaseThisTranslates', function(){
            preloader();
            var form = $('body').find('#translatesForm');
            var data = {};
            form.find('input').each(function(){
                var key = $(this).data('name');
                var val = $(this).val();
                data[key] = val;
            });
            $.ajax({
                url: '/wezom/ajax/<?php echo $frontend ? 'addTranslation' : 'addTranslationBackend'; ?>',
                type: 'POST',
                dataType: 'JSON',
                data: data,
                success: function(data){
                    window.location.reload();
                },
                error: function(){
                    preloader();
                }
            });
        });

        $('#updateTranslation').on('click', function(){
            preloader();
            $.ajax({
                url: '/wezom/api/<?php echo $frontend ? 'translatesForFrontend' : 'translates'; ?>',
                dataType: 'JSON',
                success: function(data) {
                    if(data.success) {
                        window.location.reload();
                    } else {
                        generate('<?php echo __('Произошла ошибка! Попробуйте позднее'); ?>', 'warning', 3500);
                    }
                },
                error: function() {
                    generate('<?php echo __('Произошла ошибка! Попробуйте позднее'); ?>', 'warning', 3500);
                }
            });
        });
    });
</script>
