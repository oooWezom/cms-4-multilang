$(document).ready(function() {

    function seoSet() {
        $('#clonSeo').height($('#seoTxt').outerHeight());
        $('#seoTxt').css({
            top: $('#clonSeo').offset().top
        });
    }
    

    $(window).resize(function(){
        $(".wConteiner").css('padding-bottom', $(".wFooter").height());
        $(".wFooter").css('margin-top', 1-$(".wFooter").height());
        $(".stat_slider_block ul").trigger("configuration", ["reInit", true]);
    });
        

    if ($('.validat').length) {
        $('.validat').liValidForm({
            captcha: 'code'
        });
    }

    // detect transit support
    var transitFlag = true;
    if (!Modernizr.cssanimations) {
        transitFlag = false;
    }

    jQuery.fn.ForceNumericOnly = function() {
        return this.each(function() {
            $(this).keydown(function(e) {
                var key = e.charCode || e.keyCode || 0;
                // Разрешаем backspace, tab, delete, стрелки, обычные цифры и цифры на дополнительной клавиатуре
                return (key == 8 || key == 9 || key == 46 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
            });
        });
    };

    $("input.dostavka").ForceNumericOnly();

    if($(".lupa").length) {
        $(".lupa").on('click', function() {
            $(".poisk_block").toggleClass('visibility_visible');
            $(".poisk_block").find('input[type="text"]').focus();
            $(".lupa").toggleClass('visibility_hidden');
            if($(".poisk_block").hasClass("visibility_visible")) {
                $(document).on('click', function(event) {
                    if ($(".head_bot").children().hasClass('visibility_visible')) {
                        if ($(event.target).closest('.head_bot').length == 0 && $(event.target).attr('class') != 'visibility_visible') {
                            $(".poisk_block").removeClass('visibility_visible');
                            $(".lupa").removeClass('visibility_hidden');
                        }
                    }
                });
            }
        });
    }

    if($(".head_bot").length) {
        $(".list.top_list").on('mouseenter', function() {
            $(".head_bot > ul > li:first-child()").addClass('li_hover_color');
        });
        $(".list.top_list").on('mouseleave', function() {
            $(".head_bot > ul > li:first-child()").removeClass('li_hover_color');
        });
    }


    $('.enterReg').magnificPopup({
        type: 'inline',
        midClick: true,
        removalDelay: 300,
        mainClass: 'wb_zoomIn'
    });
    $('.enterReg2').magnificPopup({
        type: 'inline',
        midClick: true,
        removalDelay: 300,
        mainClass: 'wb_zoomIn'
    });
    $('.enterReg3').magnificPopup({
        type: 'inline',
        midClick: true,
        removalDelay: 300,
        mainClass: 'wb_zoomIn'
    });
    $('.enterReg4').magnificPopup({
        type: 'inline',
        midClick: true,
        removalDelay: 300,
        mainClass: 'wb_zoomIn'
    });
    $('.enterReg5').magnificPopup({
        type: 'inline',
        midClick: true,
        removalDelay: 300,
        mainClass: 'wb_zoomIn'
    });
    $('#forget_pass').on('click', function(event) {
        $('#entrForm').removeClass('visForm');
        $('#forgetForm').addClass('visForm');
    });
    $('#remember_pass').on('click', function(event) {
        $('#forgetForm').removeClass('visForm');
        $('#entrForm').addClass('visForm');
    });
    // $('.wForm').each(function() {
    //     var form = $(this);
    //     form.validate({
    //         errorElement: "span",
    //         errorClass: "wError",
    //         rules: {
    //             enter_email: {email: true},
    //             forget_email: {email: true},
    //             enter_pass: {minlength: 4},
    //             reg_pass: {minlength: 4},
    //             phone_num: {phoneUA: true},
    //             password: "required",
    //             confirm: {
    //               equalTo: "#password"
    //             },
    //         },
    //         showErrors: function(errorMap, errorList) {
    //             if (errorList.length) {
    //                 var s = errorList.shift();
    //                 var n = [];
    //                 n.push(s);
    //                 this.errorList = n;
    //             }
    //             this.defaultShowErrors();
    //         },
    //         invalidHandler: function(form, validator) {
    //             $(validator.errorList[0].element).trigger('focus');
    //         }
    //     });
        
    // });

    // var v1 = parseInt($("#amount").val(),10);
    // var v2 = parseInt($("#amount2").val(),10);

    // if ($(".price_ui").length) {
        
    //     var slider = $("#slider-range").slider({
    //         range: true,
    //         min: v1,
    //         max: v2,
    //         step: 1,
    //         values: [v1, v2],
    //         slide: function(event, ui) {
    //             $("#amount").val(ui.values[0]);
    //             $("#amount2").val(ui.values[1]);
    //         },
    //     });
    //     $("#amount").val($("#slider-range").slider("values", 0));
    //     $("#amount2").val($("#slider-range").slider("values", 1));

    //     $("#amount").ForceNumericOnly();
    //     $("#amount2").ForceNumericOnly();

    //     $("#amount").keyup(function() {
    //         if ($("#amount2").val().length < 1) {
    //             slider.slider("values", [$(this).val(), 200]);
    //         } else {
    //             if (parseInt($("#amount2").val()) < parseInt($(this).val())) {
    //                 return false;
    //             } else {
    //                 slider.slider("values", [$(this).val(), $("#amount2").val()]);
    //             }
    //         }
    //     });

    //     $("#amount2").keyup(function() {
    //         if ($("#amount").val().length < 1) {
    //             slider.slider("values", [0, $(this).val()]);
    //         } else {
    //             if (parseInt($("#amount").val()) > parseInt($(this).val())) {
    //                 return false;
    //             } else {
    //                 slider.slider("values", [$("#amount").val(), $(this).val()]);
    //             }
    //         }
    //     });

    //     $("#amount2").blur(function(){
    //         if($("#amount2").val() < $("#amount").val()) {
    //            $("#amount2").val($("#amount").val());
    //             slider.slider("values", [$("#amount").val(), $("#amount").val()]);
    //         }
    //     });

    //     $("#amount").blur(function(){
    //         if($("#amount").val() < 200) {
    //            $("#amount").val(200);
    //             slider.slider("values", [200, $("#amount2").val()]);
    //         }
    //     });

    //     $("input[type='reset']").on('click', function() {
    //         slider.slider("values", [200, 3200]);
    //     });

    //     $("input[type='reset']").on('mouseenter', function() {
    //         $(".reset_but span").css('border-bottom','1px dotted #ccc');
    //     });

    //     $("input[type='reset']").on('mouseleave', function() {
    //         $(".reset_but span").css('border-bottom','transparent');
    //     });

    // }


    if($(".accordeon").length) {
        $(".accordeon").on('click', 'span', function() {
            var this_s = $(this);
            if($(this).next("ul").hasClass('show_acc')) {
                $(this).next("ul").stop().slideUp(500, function() {
                    $(".accordeon").find("ul").removeClass('show_acc');
                    seoSet();
                });
                $(this).text("+");
            }
            if(!$(this).next("ul").hasClass('show_acc')) {
                $(".accordeon span").text("+");
                $(".accordeon ul li ul").stop().slideUp(500);
                $(this).next("ul").stop().slideDown(500, function() {
                    $(".accordeon ul li ul").removeClass('show_acc');
                    this_s.next("ul").addClass('show_acc');
                    seoSet();
                });
                $(this).text("-");
            }
        });
    }

    // if($(".prevue_block").length) {
    //     $(".prevue_block").on('click', '.img_prevue', function() {
    //         var src_img = $(this).attr('data-img-src');
    //         $(".big_prevue").find("img").attr('src',src_img);
    //         $(".big_prevue").attr('href',src_img);
    //     });
    // }

    if($("#select5").length) {
        $("#select5").select2 ({
        });
    }

    if($("#select10").length) {
        $("#select10").select2 ({
        });
    }

    if($("#select11").length) {
        $("#select11").select2({});
        $("#select11").on('change', function(event){
            if($(this).val()==2) {
                $(".dostavka").css('display','block');
            } else {
                $(".dostavka").css('display','none');
            }
        });
    }

    function wTab(t) {  
        //выкл. старый активный пункт
            t.parent().children('.curr').removeClass('curr');
        //выкл. новый активный пункт
            t.addClass('curr');
        //выкл. старый активный блок
            $('.' + t.attr('data-tab-container')).children('.curr').removeClass('curr');
        //вкл. новый активный блок
            $('.' + t.attr('data-tab-container')).children('.' + t.attr('data-tab-link')).addClass('curr');
    }
    $('.wTab_Nav').on('click', '.wTab_link', function(event) {
            if ($(this).hasClass('curr')) {
                    return false;
            } else {
                    wTab($(this));
            }
    });

    if($(".lk_block").length) {
        $('.lk_block').on('click', '.red_form', function() {
            $(".enterReg_btn").removeClass('hide_block');
            $(this).addClass('hide_block');
            $(".wFormRow").find("input").removeAttr('disabled');
            return false;
        });
    }

    if ($(".tel").length) {
        var date = [{
            "mask": "\+38\ \(###\)\ ###\-##\-##"
        }];
        $('.tel').inputmask({
            mask: date,
            definitions: {
                '#': {
                    validator: "[0-9]",
                    cardinality: 1
                }
            }
        });
    }
    $(window).load(function() {

        if ($('.slider_main').length) {
            $('.slider_main ul').carouFredSel({
                responsive: true,
                width: '100%',
                items: {visible: 1,
                    height: '34%'
                },
                prev: ".prev1",
                next: ".next1",
                auto: true,
                scroll: {
                    items: 1,
                    fx: "scroll",
                    duration: 1000,
                    pauseOnHover: true
                }
            }, {
                transition: transitFlag
            });
        }

        if ($('.stat_slider_block').length) {
            $('.stat_slider_block ul').carouFredSel({
                responsive: true,
                width: '100%',
                items: {visible: 2},
                prev: ".prev2",
                next: ".next2",
                auto: false,
                scroll: {
                    items: 1,
                    fx: "scroll",
                    duration: 1000,
                    pauseOnHover: true
                }
            }, {
                transition: transitFlag
            });
        }

        if ($('.prevue_block ul li').length > 8) {
            $('.prevue_block ul').carouFredSel({
                // responsive: true,
                width: 60,
                // height: 'auto',
                items: 8,
                prev: ".prev3",
                next: ".next3",
                direction: 'up',
                auto: false,
                scroll: {
                    items: 1,
                    fx: "scroll",
                    duration: 1000,
                    pauseOnHover: true
                }
            }, {
                transition: transitFlag
            });
            $('.prev3, .next3').addClass('showButtonsPack');
        }

        if ($('.otziv_slider').length) {
            $('.otziv_slider ul').carouFredSel({
                responsive: true,
                items: 1,
                prev: ".prev4",
                next: ".next4",
                auto: false,
                scroll: {
                    items: 1,
                    fx: "scroll",
                    duration: 1000,
                    pauseOnHover: true
                }
            }, {
                transition: transitFlag
            });
        }

        var height_f1 = $(".wFooter").height();
        $(".wConteiner").css('padding-bottom', height_f1);
        var height_f = 1 - $(".wFooter").height();
        $(".wFooter").css('margin-top', height_f);

        

    
    if ($('#seoTxt').length) {
        var ifrm = $('<iframe name="seoIframe" scrolling="no" style="position: absolute; left: 0; top: 0; width: 100% ; height: 100% ; z - index: -1; visibility: hidden;"></iframe>');
        ifrm.prependTo($("#seoTxt"));
        var seoTimer;
        seoIframe.onresize = function() {
            clearTimeout(seoTimer);
            seoTimer = setTimeout(function() {
                seoSet();
            }, 200);
        };
        seoSet();
    }

    });



});