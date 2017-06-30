<?php
use Core\User;
use Forms\Builder;
use Forms\Form;
use Core\HTML;
use Core\Route;
use Core\Arr;
?>
<ul class="t_wrap js-specificationTabs">
    <?php $first = true;
    foreach($languages as $key => $lang){ ?>
        <li class="t_item">
            <a class="t_link <?php echo $first ? 'click_it cur' : ''; ?>" data-lang="lang_<?php echo $lang['alias']; ?>" href="#"><?php echo $lang['name']; ?></a>
        </li>
        <?php $first = false;
    } ?>
</ul>
<?php if(User::god() || User::caccess() == 'edit'): ?>
    <div class="widget box">
        <div class="widgetHeader"><div class="widgetTitle"><i class="fa fa-reorder"></i><?php echo __('Добавить значение'); ?></div></div>
        <div class="widgetContent">
            <table class="table table-striped table-bordered table-hover checkbox-wrap">
                <thead>
                    <tr>
                        <th class="align-center">
                            <?php $first = true;
                            foreach($languages as $key => $lang){ ?>
                                <span class="align-center lang_class lang_<?php echo $lang['alias']; ?>" <?php if(!$first){ echo 'style="display: none;"'; } ?>>
                                    <?php echo __('Название'); ?> (<?php echo $lang['name']; ?>)
                                </span>
                                <?php $first = false;
                            } ?>
                        </th>
                        <th class="align-center"><?php echo __('Цвет'); ?></th>
                        <th class="align-center"><?php echo __('Алиас'); ?></th>
                        <th class="align-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-center">
                            <?php $first = true;
                            foreach($languages as $key => $lang){ ?>
                                <?php echo Builder::input([
                                    'id' => 'sName_'.$lang['alias'],
                                    'data-trans' => 2,
                                    'class' => 'sName_lang lang_class lang_'.$lang['alias'].' '.($first ? 'translitSource ' : ''),
                                    'style' => ($first ? '' : 'display: none;')
                                ]); ?>
                                <?php $first = false;
                            } ?>
                        </td>
                        <td class="align-center">
                            <?php echo Builder::input([
                                'id' => 'sColor',
                                'class' => 'hue',
                                'value' => '#ffffff',
                            ]); ?>
                        </td>
                        <td class="align-center input-group">
                            <?php echo Builder::input([
                                'id' => 'sAlias',
                                'data-trans' => 2,
                                'class' => 'translitConteiner',
                            ]); ?>
                            <span class="input-group-btn">
                                <?php echo Form::button(__('Заполнить автоматически'), [
                                    'type' => 'button',
                                    'class' => 'btn translitAction',
                                    'data-trans' => 2,
									'data-rules' => 'only_chars_and_numbers', 
                                ]); ?>
                            </span>
                        </td>
                        <td class="align-center">
                            <a title="Сохранить" id="sSave" href="#" class="btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;">
                                <i class="fa fa-fixed-width">&#xf0c7;</i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<div class="widget box">
    <div class="widgetHeader"><div class="widgetTitle"><i class="fa fa-reorder"></i><?php echo __('Список значений'); ?></div></div>
    <div class="widgetContent" id="sValuesList">
        <table class="table table-striped table-bordered table-hover checkbox-wrap" data-specification="<?php echo Core\Route::param('id'); ?>">
            <thead>
                <tr>
                    <th class="align-center">
                        <?php $first = true;
                        foreach($languages as $key => $lang){ ?>
                            <span class="align-center lang_class lang_<?php echo $lang['alias']; ?>" <?php if(!$first){ echo 'style="display: none;"'; } ?>>
                                <?php echo __('Название'); ?> (<?php echo $lang['name']; ?>)
                            </span>
                            <?php $first = false;
                        } ?>
                    </th>
                    <th class="align-center"><?php echo __('Цвет'); ?></th>
                    <th class="align-center"><?php echo __('Алиас'); ?></th>
                    <th class="align-center"><?php echo __('Статус'); ?></th>
                    <?php if(User::god() || User::caccess() == 'edit'): ?>
                        <th class="align-center"></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="sortableSimple" data-params="<?php echo HTML::chars(json_encode(['table' => 'specifications_values'])); ?>">
                <?php foreach($list as $obj): ?>
                    <tr id="sort_<?php echo $obj->id; ?>" data-id="<?php echo $obj->id; ?>">
                        <td class="align-center">
                            <?php $first = true;
                            foreach($languages as $key => $lang){
                                $name_filed = 'name_'.$key;
                                $attributes = [
                                    'value' => $obj->$name_filed,
                                    'data-trans' => 'val-'.$obj->id,
                                    'class' => [($first ? 'translitSource' : ''), 'lang_class lang_'.$lang['alias'], 'sName', 'sName_'.$lang['alias']],
                                    'style' => ($first ? '' : 'display: none;')
                                ];
                                if(!User::god() && User::caccess() != 'edit'){
                                    $attributes['disabled'] = 'disabled';
                                }
                                echo Builder::input($attributes);

                                $first = false;
                            } ?>
                        </td>
                        <td class="align-center">
                            <?php $attributes = [
                                'value' => $obj->color,
                                'class' => ['hue', 'sColor'],
                            ]; ?>
                            <?php if(!User::god() && User::caccess() != 'edit'): ?>
                                <?php $attributes['disabled'] = 'disabled'; ?>
                            <?php endif; ?>
                            <?php echo Builder::input($attributes); ?>
                        </td>
                        <td class="align-center  <?php echo !(User::god() || User::caccess() == 'edit') ?: 'input-group' ?>">
                            <?php $attributes = [
                                'value' => $obj->alias,
                                'data-trans' => 'val-'.$obj->id,
                                'class' => ['translitConteiner', 'sAlias'],
                            ]; ?>
                            <?php if(!User::god() && User::caccess() != 'edit'): ?>
                                <?php $attributes['disabled'] = 'disabled'; ?>
                            <?php endif; ?>
                            <?php echo Builder::input($attributes); ?>
                            <?php if(User::god() || User::caccess() == 'edit'): ?>
                                <span class="input-group-btn">
                                    <?php echo Form::button(__('Заполнить автоматически'), [
                                        'type' => 'button',
                                        'class' => 'btn translitAction',
                                        'data-trans' => 'val-'.$obj->id,
										'data-rules' => 'only_chars_and_numbers', 
                                    ]); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="align-center">
                            <label class="ckbxWrap" style="top: 8px; left: 6px;">
                                <?php $attributes = [
                                    'class' => 'sStatus',
                                    'value' => 1,
                                ]; ?>
                                <?php if(!User::god() && User::caccess() != 'edit'): ?>
                                    <?php $attributes['disabled'] = 'disabled'; ?>
                                <?php endif; ?>
                                <?php echo Builder::checkbox($obj->status, $attributes); ?>
                            </label>
                        </td>
                        <?php if(User::god() || User::caccess() == 'edit'): ?>
                            <td class="align-center" style="width: 80px;">
                                <a title="<?php echo __('Сохранить изменения'); ?>" href="#" class="sSave btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa fa-fixed-width">&#xf0c7;</i></a>
                                <a title="<?php echo __('Удалить'); ?>" href="#" class="sDelete btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa fa-trash-o"></i></a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="sParametersColor" data-id="<?php echo Core\Route::param('id'); ?>"></div>


<!-- SCRIPT ZONE -->
<script>
    $(function(){

        $('.js-specificationTabs').on('click', 'a',  function(e){
            e.preventDefault();
            var lang = $(this).attr('data-lang');

            $('.js-specificationTabs a').removeClass('cur');
            $(this).addClass('cur');
            $('.lang_class').css('display', 'none');
            $('.'+lang).css('display', '');
        });

        if( $('#sParametersColor').length ) {
            // Start colorpicker
            var setMinicolor = function(){
                $('.hue').each( function() {
                    $(this).minicolors({
                        control: 'hue',
                        defaultValue: $(this).val(),
                        position: 'bottom right',
                        change: function(hex, opacity) {
                            if( !hex ) return;
                            if( opacity ) hex += ', ' + opacity;
                        },
                        theme: 'bootstrap'
                    });
                });
            };
            setMinicolor();
            // Specification id
            var sID = $('#sParametersColor').data('id');
            // Message with error for admin
            var generate_warning = function( message ) {
                $(document).alert2({
                    message: message,
                    headerCOntent: false,
                    footerContent: false,
                    typeMessage: 'warning' //false or 'warning','success','info','danger'
                });
            };
            // Set checkbox
            var checkboxStart = function( el ) {
                var parent = el.parent();
                if(parent.is('label')){
                    if(el.prop('checked')){
                        parent.addClass('checked');
                    } else {
                        parent.removeClass('checked');
                    }
                }
            };
            // Generate a row from object
            var generateTR = function( obj ) {
                var html = '';
                html  = '<tr id="sort_'+obj.id+'" data-id="'+obj.id+'">';
                html += '<td class="align-center">';
                <?php $first = true;
                foreach($languages as $key => $lang){
                    if($first){ ?>
                        html += '<input type="text" class="form-control lang_class lang_<?php echo $lang['alias']; ?> sName sName_<?php echo $lang['alias']; ?> translitSource" data-trans="val-'+obj.id+'" value="'+obj.name_<?php echo $lang['alias']; ?>+'" />';
                    <?php } else { ?>
                        html += '<input type="text" class="form-control lang_class lang_<?php echo $lang['alias']; ?> sName sName_<?php echo $lang['alias']; ?>" style="display: none;" data-trans="val-'+obj.id+'" value="'+obj.name_<?php echo $lang['alias']; ?>+'" />';
                    <?php  }
                    $first = false;
                } ?>
                html += '</td>';
                html += '<td class="align-center">';
                html += '<input type="text" class="form-control sColor hue" value="'+obj.color+'" />';
                html += '</td>';
                html += '<td class="align-center input-group">';
                html += '<input class="form-control sAlias translitConteiner" data-trans="val-'+obj.id+'" type="text" value="'+obj.alias+'" />';
                html += '<span class="input-group-btn">' +
                        '<button class="btn translitAction" data-trans="val-'+obj.id+'" data-rules="only_chars_and_numbers"  type="button"><?php echo __('Заполнить автоматически'); ?></button>' +
                        '</span>';
                html += '</td>';
                html += '<td class="align-center"><label class="ckbxWrap" style="top: 8px; left: 6px;">';
                if ( parseInt( obj.status ) == 1 ) {
                    html += '<input class="sStatus" type="checkbox" value="1" checked />';
                } else {
                    html += '<input class="sStatus" type="checkbox" value="1" />';
                }
                html += '</label></td>';
                html += '<td class="align-center">';
                html += '<a title="<?php echo __('Сохранить изменения'); ?>" href="#" class="sSave btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa fa-fixed-width">&#xf0c7;</i></a>';
                html += '<a title="<?php echo __('Удалить'); ?>" href="#" class="sDelete btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa fa-trash-o"></i></a>';
                html += '</td>';
                html += '</tr>';
                return html;
            };;
            // Add a row
            $('#sSave').on('click', function(e){
                e.preventDefault();
                loader($('#sValuesList'), 1);
                <?php foreach($languages as $key => $lang){ ?>
                    var name_<?php echo $lang['alias']; ?> = $('#sName_<?php echo $lang['alias']; ?>').val();
                <?php } ?>
                var color = $('#sColor').val();
                var alias = $('#sAlias').val();
                $.ajax({
                    url: '/wezom/ajax/specifications/addColorSpecificationValue',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        specification_id: sID,
                        <?php foreach($languages as $key => $lang){ ?>
                            name_<?php echo $lang['alias']; ?>: name_<?php echo $lang['alias']; ?>,
                        <?php } ?>
                        color: color,
                        alias: alias
                    },
                    success: function(data){
                        if( data.success ) {
                            var html = '';
                            if( data.result.length ) {
                                for( var i = 0; i < data.result.length; i++ ) {
                                    html += generateTR(data.result[i]);
                                }
                            }
                            $('#sValuesList tbody').html(html);
                            $('#sValuesList input[type=checkbox]').each(function(){ checkboxStart($(this)); });
                            $('#sValuesList input[type=checkbox]').on('change',function(){ checkboxStart($(this)); });
                            $('.sName_lang').val('');
                            $('#sAlias').val('');
                            $('#sColor').val('#ffffff');
                            setMinicolor();

                            $('.click_it').click();
                        } else {
                            generate_warning(data.error);
                        }
                        loader($('#sValuesList'), 0);
                    }
                });
            });
            // Save a row
            $('#sValuesList').on('click', '.sSave', function(e){
                e.preventDefault();
                loader($('#sValuesList'), 1);
                var tr = $(this).closest('tr');
                var id = tr.data('id');
                <?php foreach($languages as $key => $lang){ ?>
                    var name_<?php echo $lang['alias']; ?> = tr.find('.sName_<?php echo $lang['alias']; ?>').val();
                <?php } ?>
                var color = tr.find('.sColor').val();
                var alias = tr.find('.sAlias').val();
                var status = 0;
                if( tr.find('.sStatus').parent().hasClass('checked') ) {
                    status = 1;
                }
                $.ajax({
                    url: '/wezom/ajax/specifications/editColorSpecificationValue',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        specification_id: sID,
                        <?php foreach($languages as $key => $lang){ ?>
                            name_<?php echo $lang['alias']; ?>: name_<?php echo $lang['alias']; ?>,
                        <?php } ?>
                        status: status,
                        id: id,
                        color: color,
                        alias: alias
                    },
                    success: function(data){
                        if( data.success ) {
                            var html = '';
                            if( data.result.length ) {
                                for( var i = 0; i < data.result.length; i++ ) {
                                    html += generateTR(data.result[i]);
                                }
                            }
                            $('#sValuesList tbody').html(html);
                            $('#sValuesList input[type=checkbox]').each(function(){ checkboxStart($(this)); });
                            $('#sValuesList input[type=checkbox]').on('change',function(){ checkboxStart($(this)); });
                            setMinicolor();

                            $('.click_it').click();
                        } else {
                            generate_warning(data.error);
                        }
                        loader($('#sValuesList'), 0);
                    }
                });
            });
            // Delete a row
            $('#sValuesList').on('click', '.sDelete', function(e){
                e.preventDefault();
                loader($('#sValuesList'), 1);
                var tr = $(this).closest('tr');
                var id = tr.data('id');
                $.ajax({
                    url: '/wezom/ajax/specifications/deleteSpecificationValue',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    success: function(data){
                        if( data.success ) {
                            tr.remove();
                        } else {
                            generate_warning(data.error);
                        }
                        loader($('#sValuesList'), 0);
                    }
                });
            });
        }
    });
</script>