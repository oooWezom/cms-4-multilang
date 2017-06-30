var closePopup = function( it ) {
    it.fadeOut(650);
    setTimeout(function(){
        it.remove();
    }, 700);
};
var generate = function( message, type, time ) {
    var mainBlock = $('#fPopUp');
    var current;
    if(!mainBlock.length) {
        $('<div id="fPopUp"></div>').appendTo('body');
        mainBlock = $('#fPopUp');
    }
    var i = 1;
    var count = 0;
    mainBlock.find('.content').each(function(){
        current = parseInt($(this).data('i'));
        if(current + 1 > i) {
            i = current + 1;
        }
        count++;
    });
    if(count >= 5) {
        mainBlock.find('div.content:first-child').remove();
    }
    $('<div class="content ' + type + '" data-i="' + i + '" style="display: none;">' + message + '</div>').appendTo(mainBlock);
    mainBlock.find('div.content[data-i="' + i + '"]').fadeIn(200);
    if(time) {
        setTimeout(function(){
            closePopup(mainBlock.find('div.content[data-i="' + i + '"]'));
        }, time);
    }
};

$(function(){
    $('body').on('click', '#fPopUp div.content', function(){ closePopup($(this)); });

    if( $('ul.color_c').length ) {
        $('ul.color_c input').each(function(){
            if ($(this).prop('disabled')) {
                $(this).closest('li').css('opacity', '0.2');
                $(this).closest('li').find('ins').css('border', '0');
                $(this).css('cursor', 'auto');
            }
        });
        $('ul.color_c a').on('click', function(){
            window.location.href = $(this).attr('href');
        });
    }

    var v1 = parseInt($("#amount").val(),10);
    var v2 = parseInt($("#amount2").val(),10);
    var min = parseInt($("#amount").data('cost'),10);
    var max = parseInt($("#amount2").data('cost'),10);

    if ($(".price_ui").length) {
        
        var slider = $("#slider-range").slider({
            range: true,
            min: min,
            max: max,
            step: 1,
            values: [v1, v2],
            slide: function(event, ui) {
                $("#amount").val(ui.values[0]);
                $("#amount2").val(ui.values[1]);
            }
        });
        $("#amount").val($("#slider-range").slider("values", 0));
        $("#amount2").val($("#slider-range").slider("values", 1));

        $("#amount").ForceNumericOnly();
        $("#amount2").ForceNumericOnly();

        $("#amount").keyup(function() {
            if ($("#amount2").val().length < 1) {
                slider.slider("values", [$(this).val(), min]);
            } else {
                if (parseInt($("#amount2").val()) < parseInt($(this).val())) {
                    return false;
                } else {
                    slider.slider("values", [$(this).val(), $("#amount2").val()]);
                }
            }
        });

        $("#amount2").keyup(function() {
            if ($("#amount").val().length < 1) {
                slider.slider("values", [0, $(this).val()]);
            } else {
                if (parseInt($("#amount").val()) > parseInt($(this).val())) {
                    return false;
                } else {
                    slider.slider("values", [$("#amount").val(), $(this).val()]);
                }
            }
        });

        $("#amount2").blur(function(){
            if($("#amount2").val() < $("#amount").val()) {
               $("#amount2").val($("#amount").val());
                slider.slider("values", [$("#amount").val(), $("#amount").val()]);
            }
        });

        $("#amount").blur(function(){
            if($("#amount").val() < min) {
               $("#amount").val(min);
                slider.slider("values", [min, $("#amount2").val()]);
            }
        });

        $("input[type='reset']").on('click', function() {
            slider.slider("values", [min, max]);
        });

        $("input[type='reset']").on('mouseenter', function() {
            $(".reset_but span").css('border-bottom','1px dotted #ccc');
        });

        $("input[type='reset']").on('mouseleave', function() {
            $(".reset_but span").css('border-bottom','transparent');
        });

    }

    $('.enterReg5').on('click', function(){
        var id = $(this).data('id');
        $('#idFastOrder').val(id);
    });

    if($(".prevue_block").length) {
        $(".prevue_block").on('click', '.img_prevue', function() {
            var src_img = $(this).attr('data-img-src');
            var src_img_original = $(this).attr('data-img-src-original');
            $(".big_prevue").find("img").attr('src',src_img);
            $(".big_prevue").attr('href',src_img_original);
        });
    }

    if( $('.lk_menu').length ) {
        var h1 = $('.lk_menu').height();
        var h2 = $('.lk_content').height();
        if( h1 > h2 ) {
            $('.lk_content').height( h1 );
        }
        if( h2 > h1 ) {
            $('.lk_menu').height( h2 );
        }
    }


    $('.wForm').each(function() {
        var formValid = $(this);
        formValid.validate({
            showErrors: function(errorMap, errorList) {
                if (errorList.length) {
                    var s = errorList.shift();
                    var n = [];
                    n.push(s);
                    this.errorList = n;
                }
                this.defaultShowErrors();
            },
            invalidHandler: function(form, validator) {
                $(validator.errorList[0].element).trigger('focus');
                formValid.addClass('no_valid').removeClass('success');
            },
            submitHandler: function(form) {
                var $form = $(form);
                formValid.removeClass('no_valid').addClass('success');
                if (form.tagName === 'FORM') {
                    form.submit();
                } else {
                    if( $form.data('ajax') ) {
                        if($form.data('loader')) {
                            loader($form.data('loader'), 1);
                        }
                        var data = new FormData();
                        var name;
                        var val;
                        var type;
                        $form.find('input,textarea,select').each(function(){
                            name = $(this).attr('data-name');
                            val = $(this).val();
                            type = $(this).attr('type');
                            if((type != 'checkbox' && name) || (type == 'checkbox' && $(this).prop('checked') && name)) {
                                if(type == 'file') {
                                    data.append(name, $(this)[0].files[0]);
                                } else if(type == 'radio' && $(this).prop('checked')) {
                                    data.append(name, val);
                                } else if(type != 'radio') {
                                    data.append(name, val);
                                }
                            }
                        });
                        var request = new XMLHttpRequest();
                        request.open("POST", '/form/' + $form.data('ajax'));
                        request.onreadystatechange = function() {
                            var status;
                            var resp;
                            if (request.readyState == 4) {
                                wPreloader.hide();
                                status = request.status;
                                resp = request.response;
                                resp = jQuery.parseJSON(resp);
                                if (status == 200) {
                                    if( resp.success ) {
                                        if (!resp.noclear) {
                                            $form.find('input').each(function(){
                                                if( $(this).attr('type') != 'hidden' && $(this).attr('type') != 'checkbox' ) {
                                                    $(this).val('');
                                                }
                                            });
                                            $form.find('textarea').val('');
                                        }
                                        if (resp.clear) {
                                            for(var i = 0; i < resp.clear.length; i++) {
                                                $('input[name="' + resp.clear[i] + '"]').val('');
                                                $('textarea[name="' + resp.clear[i] + '"]').val('');
                                            }
                                        }
                                        if (resp.insert && resp.insert.selector && resp.insert.html) {
                                            $(resp.insert.selector).html(resp.insert.html);
                                        }
                                        if ( resp.response ) {
                                            generate(resp.response, 'success', 3500);
                                        }
                                    } else {
                                        if ( resp.response ) {
                                            generate(resp.response, 'warning', 3500);
                                        }
                                    }
                                    if( resp.redirect ) {
                                        if(window.location.href == resp.redirect) {
                                            window.location.reload();
                                        } else {
                                            window.location.href = resp.redirect;
                                        }
                                    }
                                } else {
                                    alert('Something went wrong.');
                                }
                            }
                            if($form.data('loader')) {
                                loader($form.data('loader'), 0);
                            }
                        };
                        request.send(data);
                        return false;
                    }
                }
            }
        });
    });
    $('.wSubmit').on('click', function(event) {
        var form = $(this).closest('.wForm');
        form.valid();
        if (form.valid()) {
            var mark = '<div class="cssload-wrap"><div class="cssload-cssload-spinner"></div></div>';
            wPreloader.show(false, {
                block: true,
                markup: mark
            });
            form.submit();
        }
    });

    /// CART START
    var setTopCartCount = function(count){
        $('#topCartCount').text(count);
    };
    $('.addToCart').on('click', function(e){
        e.preventDefault();
        var it = $(this);
        var id = it.data('id');
        $.ajax({
            url: '/ajax/addToCart',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function(data){
                if( data.success ) {
                    var html = '';
                    var item;
                    var count = 0;
                    var amt;
                    var amount = 0;
                    for( var i = 0; i < data.cart.length; i++ ) {
                        item = data.cart[i];
                        amt = parseInt( item.count ) * parseInt( item.cost );
                        html += '<li class="wb_item" data-id="'+item.id+'" data-count="'+item.count+'" data-price="'+item.cost+'">';
                        html += '<div class="wb_li">';
                        if ( item.image ) {
                            html += '<div class="wb_side"><div class="wb_img">';
                            html += '<a href="/'+item.alias+'/p'+item.id+'" class="wbLeave"><img src="'+item.image+'" /></a>';
                            html += '</div></div>';
                        }
                        html += '<div class="wb_content">';
                        html += '<div class="wb_row">';
                        html += '<div class="wb_del"><span title="Удалить товар">Удалить товар</span></div>';
                        html += '<div class="wb_ttl"><a href="/'+item.alias+'/p'+item.id+'" class="wbLeave">'+item.name+'</a></div>';
                        html += '</div>';
                        html += '<div class="wb_cntrl">';
                        html += '<div class="wb_price_one"><p><span>'+item.cost+'</span> грн.</p></div>';
                        html += '<div class="wb_amount_wrapp">';
                        html += '<div class="wb_amount">';
                        html += '<input type="text" value="'+item.count+'">';
                        html += '<span data-spin="plus"></span>';
                        html += '<span data-spin="minus"></span>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="wb_price_totl"><p><span>'+amt+'</span> грн.</p></div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
                        amount += amt;
                        count += parseInt( item.count );
                    }
                    $('#topCartList').html(html);
                    $('#topCartAmount').html(amount);
                    setTopCartCount(count);
                }
                $('.wb_edit_init').click();
            }
        });
    });
    /// CART END

    /// Remove social netqork from pc
    $('.deleteConnection').on('click', function(){
        var id = $(this).data('id');
        $.ajax({
            url: '/ajax/removeConnection',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            complete: function(data){
                window.location.reload();
            }
        });
    });
});
