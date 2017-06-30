//global var
var alert2,
    refreshBlock;


$(document).ready(function() {

    //start fieldToogle
    $('.fieldToogle.switch').each(function() {
        var fieldEl = $(this);
        var fieldToogle = fieldEl.data('toogle');
        var toggleEl = $(fieldToogle);
        if (fieldEl.prop('checked')) {
            toggleEl.show();
        } else {
            toggleEl.hide();
        }
    });
    //end fieldToogle

    //start skidkatype
    $('.skidkatype').on('change', function() {
        $('.skidkatypeItem').hide().filter('#' + $(this).val()).show();
    }).trigger('change');

    var skidkaAddWrap = $('.skidkaAddWrap');
    var skidkaAddLink = $('.skidkaAddLink');

    $('.skidkaAddLink').on('click', function() {
        if (skidkaAddWrap.is(':hidden')) {
            skidkaAddWrap.show();
        } else {
            skidkaAddWrap.hide();
        }
        return false;
    });
    $('.cancelSkidkaBtn').on('click', function() {
        skidkaAddWrap.hide();
        return false;
    });


    //end skidkatype



    //start standartMessage
    var orderMessageField = $('.orderMessageField');

    $('.standartMessage').on('change', function() {
        var value = $(this).val();

        if ($.trim(orderMessageField.val()) != '') {

            $(document).alert2({
                message: 'Вы хотите изменить уже существующее сообщение?', //string
                openCallback: function() {}, //function
                closeCallback: function() {}, //function
                headerCOntent: false, //string or false
                footerContent: '<button type="button" class="btn btn-default popup-modal-dismiss">Отмена</button> <button type="button" class="btn btn-primary popup-modal-dismiss changeMesage">Да</button>', //string or false
                typeMessage: false //false or 'warning','success','info','danger'
            });

            $(document).on('click', '.changeMesage', function(e) {

                orderMessageField.val(value);

                $(this).off(e);

                return false;
            })

        } else {
            orderMessageField.val(value)
        }
        return false
    });
    //end standartMessage


    //start widget-refresh

    refreshBlock = function(refContent, refTime) {

        var refPos = refContent.css('position');
        if (refPos == 'static') {
            refContent.addClass('refRel');
        }
        refContent.addClass('refShow');
        setTimeout(function() {
            refContent.removeClass('refRel').removeClass('refShow');
        }, refTime)

    };

    $(document).on('click', '.widget-refresh', function() {
        var refToggle = $(this);
        var refContent = $(refToggle.attr('data-refresh'));
        var refTime = refToggle.attr('data-time');
        refreshBlock(refContent, refTime);
        return false;
    });
    //end widget-refresh


    //start style input file

    $(".file_upload").each(function() {
        var wrapper = $(this),
            inp = wrapper.find("input"),
            btn = wrapper.find(".button"),
            lbl = wrapper.find("mark");

        // Crutches for the :focus style:
        inp.focus(function() {
            wrapper.addClass("focus");
        }).blur(function() {
            wrapper.removeClass("focus");
        });

        var file_api = (window.File && window.FileReader && window.FileList && window.Blob) ? true : false;

        inp.change(function() {
            var file_name;
            if (file_api && inp[0].files[0])
                file_name = inp[0].files[0].name;
            else
                file_name = inp.val().replace("C:\\fakepath\\", '');

            if (!file_name.length)
                return;

            if (lbl.is(":visible")) {
                lbl.text(file_name);
                btn.text("Выбрать...");
            } else
                btn.text(file_name);
        }).change();

    });
    //end style input file

    //start tableTask
    var tableTask = $('.tableTask');
    $('[type=checkbox]', tableTask).on('change', function() {
        var ckbx = $(this);
        if (ckbx.prop('checked')) {
            $(this).closest('tr').addClass('taskComplete');
        } else {
            $(this).closest('tr').removeClass('taskComplete');
        }
    });

    //end tableTask

    //start all check
    $('.checkbox-wrap').on('change', '.checkbox-head [type=checkbox]', function() {
        var checkEl = $(this);

        var checkChild = checkEl.closest('.checkbox-wrap').find('.checkbox-column [type=checkbox]').not(checkEl);
        var checkHead = checkEl.closest('.checkbox-wrap').find('.checkbox-head [type=checkbox]').not(checkEl);

        if (checkEl.prop('checked')) {
            checkChild.prop('checked', true).trigger('change');
            checkHead.parent('label').addClass('checked');
        } else {
            checkChild.prop('checked', false).trigger('change');
            checkHead.parent('label').removeClass('checked');
        }
    });
    //end all check


    //start custom input
    var radio = $('[type=radio]:not(".switch")');
    var ckbx = $('[type=checkbox]:not(".switch")');
    radio.each(function() {
        var el = $(this);
        var par = el.parent();
        if (par.is('label')) {
            par.addClass('radioWrap');
            if (el.prop('disabled')) {
                par.addClass('disabled')
            }
        }
    });
    ckbx.each(function() {
        var el = $(this);
        var par = el.parent();
        if (par.is('label') && !par.hasClass('btn')) {
            par.addClass('ckbxWrap');
            if (el.prop('disabled')) {
                par.addClass('disabled')
            }
        }
    });
    $('.form-block select').closest('.controls').addClass('selectWrap');
    radio.on('change', function() {
        var el = $(this);
        var name = el.attr('name');
        var parent = el.parent();
        if (parent.is('label')) {
            if (el.prop('checked')) {
                $('[name=' + name + ']').parent('label').removeClass('checked');
                parent.addClass('checked')
            }
        }
    });



    ckbx.on('change', function() {
        var el = $(this);
        var parent = el.parent();
        if (parent.is('label')) {
            if (el.prop('checked')) {
                parent.addClass('checked')
            } else {
                parent.removeClass('checked')
            }
        }
    });

    radio.add(ckbx).trigger('change');

    //end custom input

    //start widget collapse 
    $(document).on('click', '.widget-collapse', function() {
        var widgetCollapse = $(this);
        var widget = widgetCollapse.closest('.widget');
        var widgetContent = $('.widgetContent', widget);
        if (widget.is('.widgetClosed')) {
            //widgetContent.stop(true).slideDown(200);
            widget.removeClass('widgetClosed');

        } else {
            /*
			widgetContent.stop(true).slideUp(200,function(){
				widget.addClass('widgetClosed');	
			});
			*/
            widget.addClass('widgetClosed');

        }
        return false;
    });
    //end widget collapse  

    //start dropdown-toggle
    var navbarNav = $('.navbarNav');
    var dropdown = $('.dropdown');
    $(document).on('click', '.dropdownToggle', function() {
        var ddToggle = $(this);
        var ddParent = ddToggle.parent().addClass('dropdownWrap');
        //var ddWrap = ddToggle.closest('.dropdown');
        var ddMenu = ddToggle.next('.dropdownMenu');
        var ddMenuId = function() {};
        ddMenu.css({
            top: ddToggle.position().top + ddToggle.outerHeight(),
            left: ddToggle.position().left
        });
        if (ddMenu.is('.pull-right')) {
            ddMenu.css({
                left: 'auto',
                right: (ddParent.width() - ddToggle.position().left) - ddToggle.outerWidth()
            })
        }
        if (ddToggle.is('.dropdownMenuOpen')) {
            ddToggle.add(ddMenu).removeClass('dropdownMenuOpen');
        } else {
            ddToggle.add(ddMenu).addClass('dropdownMenuOpen');
            $('.dropdownToggle').not(ddToggle).removeClass('dropdownMenuOpen');
            $('.dropdownMenu').not(ddMenu).removeClass('dropdownMenuOpen');
        }
        if (ddToggle.is('.dropdownSelect')) {
            $('a', ddMenu).on('click', function() {
                var subActive = $(this);
                $('.dropdownToggle').add('.dropdownMenu').removeClass('dropdownMenuOpen');
                $('.current-item', ddToggle).text(subActive.text());
                if (ddToggle.is('.choiceColorToggle')) {
                    $('.current-item', ddToggle).css({
                        backgroundColor: subActive.css('background-color')
                    })
                }
            })
        }
        ddMenu.add(ddToggle).on('mouseleave', function() {
            ddMenuId = setTimeout(function() {
                ddToggle.add(ddMenu).removeClass('dropdownMenuOpen');
            }, 2000)
        }).on('mouseenter', function() {
            clearTimeout(ddMenuId);
        });
        return false
    });
    //end dropdown-toggle


    //start select
    $('.form-block select').closest('.controls').addClass('selectWrap');
    //end select

    //start file
    $('.file').each(function() {
        var f = $(this);
        var fileItem = f.closest('.file_item');
        var fakeBut = $('.fakebut', fileItem);
        var rightPos = fileItem.width() - (fakeBut.position().left + fakeBut.outerWidth());
        f.css({
            right: rightPos
        });
    });
    $(document).on('change', '.file', function() {

        var item_wrap = $(this).closest('.form-group');
        var file = $(this).val();
        var reWin = /.*\\(.*)/;
        var fileTitle = file.replace(reWin, "$1"); //w*s
        var reUnix = /.*\/(.*)/;
        var fileTitle = fileTitle.replace(reUnix, "$1"); //*nix

        $('.fakefile input', item_wrap).val(fileTitle)
    });
    //end file	

    //sart placeholder
    if (!Modernizr.input.placeholder) {
        $('input[placeholder]').each(function() {
            var el = $(this);
            var ph = el.attr('placeholder');
            if (el.val() == '') {
                el.val(ph)
            }
            el.focus(function() {
                if (el.val() == ph) {
                    el.val('')
                }
            }).blur(function() {
                if (el.val() == '') {
                    el.val(ph)
                }
            })
        })
    }
    //end placeholder

    //start submit
    $('a.submit').bind('click', function() {
        $(this).closest('form').submit();
        return false
    });
    //end submit

    //start vertical middle
    $('.middle_inner').each(function() {
        $('<span>').addClass('helper').appendTo($(this).parent());
    });
    //end vertical middle




    /*-----------------------*/
    $(window).load(function() {
        $(window).trigger('resize');
    });

    var sideBar = $('.sideBar');
    var fResize = true;
    if ($(window).width() < 980) {
        fResize = true;
        $('.container').addClass('sideBarClose');
        sideBar.css({
            left: -sideBar.width()
        });
    } else {
        fResize = false;
        $('.container').removeClass('sideBarClose');
        sideBar.css({
            left: 0
        });
    }


    $(window).on('resize', function() {
        if ($(window).width() < 980) {
            if (!fResize) {
                $('.container').addClass('sideBarClose');
                sideBar.css({
                    left: -sideBar.width()
                });

                //$.cookie('sidebar_close', 1);	
            }
            fResize = true;
        } else {
            if (fResize) {

                $('.container').removeClass('sideBarClose');
                sideBar.css({
                    left: 0
                });
                //$.cookie('sidebar_close', 0);
            }
            fResize = false;
        }
        if ($('.mCustomScrollbar').length) {
            $('.mCustomScrollbar').mCustomScrollbar("update");
        }
    });

    if ($('.dropDownload').length) {
        $('.dropDownload').sortable({
            connectWith: ".loadedBlock",
            handle: ".loadedDrag",
            cancel: '.loadedControl',
            placeholder: "loadedBlockPlaceholder"
        });
    }

    $('.dropModule').on('click', '.checkAll', function(event) {
        var block = $(this).closest('.loadedBox').find('.dropDownload').find('.loadedBlock');
        block.addClass('chk');
        block.find('.loadedCheck').find('input').prop('checked', true);
        $('.loadedBox .uncheckAll, .loadedBox .removeCheck').fadeIn(300);
    });

    $('.dropModule').on('click', '.uncheckAll', function(event) {
        var block = $(this).closest('.loadedBox').find('.dropDownload').find('.loadedBlock');
        block.removeClass('chk');
        block.find('.loadedCheck').find('input').prop('checked', false);
        $('.loadedBox .uncheckAll, .loadedBox .removeCheck').fadeOut(300);
    });

    $('.dropDownload').on('click', '.loadedCheck label', function(event) {
        if ($(this).children('input').is(':checked')) {
            $(this).closest('.loadedBlock').addClass('chk');
        } else {
            $(this).closest('.loadedBlock').removeClass('chk');
        }
        if ($('.dropDownload .chk').length > 0) {
            $('.loadedBox .uncheckAll, .loadedBox .removeCheck').fadeIn(300);
        } else {
            $('.loadedBox .uncheckAll, .loadedBox .removeCheck').fadeOut(300);
        }
    });

    $('.dropDownload').magnificPopup({
        delegate: '.btnImage',
        type: 'image',
        gallery: {
            enabled: true
        },
        removalDelay: 300,
        mainClass: 'zoom-in'
    });

    $('.mfpImage').magnificPopup({
        type: 'image',
        removalDelay: 300,
        mainClass: 'zoom-in'
    });

    /*-------------------------------  Cropper  ------------------------------------*/

    var crop = $('#editModule');
    if (crop.length) {
        var data = crop.data('config');
        var ratio = parseInt(data.width, 10) / parseInt(data.height, 10);
        var preview = $('<div class="cropPreviewIn"><div class="cropPreviewImg"></div><div class="cropPreviewTxt">' + data.width + ' x ' + data.height + '</div></div>');
        preview.children('.cropPreviewImg').css({
            width: data.width,
            height: data.height
        });
        preview.appendTo($('#cropPreview'));
        initCrop(ratio);
    }

    function initCrop(ratio) {
        var wd = +($('.cropBlock').outerWidth()) / ratio;
        var wdP = +($('.cropBlock').parent().width());
        $('.cropBlock').css('padding-top', wd / wdP * 100 + '%');

        var $crop = $('.cropBlockIn > img'),
            options = {
                dragMode: 'move',
                aspectRatio: ratio,
                preview: '.cropPreviewImg'
            };

        $crop.cropper(options).on('built.cropper', function(event) {
            setTimeout(function() {
                $('#editModule').removeClass('preloadCrop');
            }, 500);
        });

        $(document).keydown(function(event) {
            if (event.keyCode === 13) {
                $crop.cropper('reset', true);
            }
        });
        $('#croppedBtn').on('click', function(event) {
            preloader();
            event.preventDefault();
            var json = [
                '{"cropBox":' + JSON.stringify($crop.cropper('getData')),
                '"naturalImage":' + JSON.stringify($crop.cropper('getImageData')) + '}'
            ].join();
            $.ajax({
                type: 'POST',
                /*url:  url ,*/
                data: {
                    json: json
                },
                dataType: 'JSON',
                success: function(data) {
                    preloader();
                    if (data.success) {
                        generate('Фото обрезано!', 'success', 5000);
                    } else {
                        generate('Что-то пошло не так!', 'warning', 5000);
                    }
                },
                error: function(data) {
                    preloader();
                    generate('Что-то пошло не так!', 'warning', 5000);
                }
            });
        });
    }

});