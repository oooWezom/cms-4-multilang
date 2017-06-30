$.wNoty.configGlobal({
    theme: 'default',
    position: 'bottom right',
    stack: 7
});
// function generate(message, type, time) {
//     if (time && time != undefined) {
//         $.wNoty.alert({
//             msg: message,
//             status: type,
//             livetime: time
//         });
//     } else {
//         $.wNoty.alert({
//             msg: message,
//             status: type,
//             immortal: true
//         });
//     }
// }

function generate(message, type, time) {
    noty({
        text: message,
        type: type,
        timeout: time || false,
        animation: {
            open: 'animated flipInX', // Animate.css class names
            close: 'animated flipOutX' // Animate.css class names
        }
    });
}

function loader(selector, type) {
    if (type == 0) {
        $(selector).removeClass('refRel').removeClass('refShow');
    }
    if (type == 1) {
        var refPos = $(selector).css('position');
        if (refPos == 'static') {
            $(selector).addClass('refRel');
        }
        $(selector).addClass('refShow');
    }
}

function preloader() {
    if ($('.wpreloader_wraper').length && $('.wpreloader_wraper').is(':visible')) {
        wPreloader.hide();
    } else {
        wPreloader.show();
    }
}

$(document).ready(function () {
    $('body').on('click', '#fPopUp div.content', function () {
        closePopup($(this));
    });

    $('.sortableSimple').sortable({
        update: function (event, ui) {
            var sort = $(this).sortable('serialize');
            var params = $(this).data('params');
            $.ajax({
                data: {sort: sort, params: params},
                type: 'POST',
                url: '/wezom/ajax/sortableSimple'
            });
        }
    });

    $('.changeBackendLanguage').on('click', function(e) {
        e.preventDefault();
        var lang = $(this).data('lang');
        $.ajax({
            url: '/wezom/ajax/changeLanguage',
            type: 'POST',
            dataType: 'JSON',
            data: {
                lang: lang
            },
            success: function(data) {
                if(data.success) {
                    window.location.reload();
                } else {
                    generate('Error! Please try later', 'warning', 3500);
                }
            },
            error: function() {
                generate('Server error! Please try later', 'error', 3500);
            }
        });
    });

    var pickerInit = function (selector) {
        if (!$(selector).length) {
            return false;
        }
        $(selector).each(function () {
            var date = $(this).val();
            $(this).datepicker({
                showOtherMonths: true,
                selectOtherMonths: false
            });
            $(this).datepicker('option', $.datepicker.regional['ru']);
            var dateFormat = $(this).datepicker("option", "dateFormat");
            $(this).datepicker("option", "dateFormat", 'dd.mm.yy');
            $(this).val(date);
        });
    };
    pickerInit('.fPicker');

    $('.clearMinifyCache').on('click', function (e) {
        e.preventDefault();
        if (confirm('Вы точно хотите удалить минифицированные файлы?')) {
            var it = $(this);
            $.ajax({
                url: '/wezom/ajax/clearMinifyCache',
                success: function (data) {
                    it.closest('li').remove();
                }
            });
        }
    });


    $('body').on('click', '.translitAction', function () {
        var it = $(this);
        var trans = it.data('trans');
		var rules = it.data('rules');
        var source;
        if (trans) {
            source = $('.translitSource[data-trans="' + trans + '"]').val();
        } else {
            source = $('.translitSource').val();
        }
        $.ajax({
            url: '/wezom/ajax/translit',
            type: 'POST',
            dataType: 'JSON',
            data: {
                source: source, 
				rules: rules
            },
            success: function (data) {
                if (!it.data('trans')) {
                    $('.translitConteiner').val(data.result);
                } else {
                    $('.translitConteiner[data-trans="' + it.data('trans') + '"]').val(data.result);
                }
            }
        });
    });

    var change_status = function (it, id) {
        var current = it.data('status');
        var table = $('#parameters').data('table');
        $.ajax({
            url: '/wezom/ajax/setStatus',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                current: current,
                table: table
            },
            success: function (data) {
                it.data('status', data.status);
                var html;
                if (data.status == 1) {
                    html = '<i class="fa fa-check"></i>';
                    it.removeClass('btn-danger');
                    it.addClass('btn-success');
                } else {
                    html = '<i class="fa fa-dot-circle-o"></i>';
                    it.removeClass('btn-success');
                    it.addClass('btn-danger');
                }
                it.html(html);
                $('.liTipContent').remove();
                $('.bs-tooltip').each(function () {
                    $(this).liTip();
                });
                it.trigger('mouseenter');
            }
        });
    };
    $('.setStatus').on('click', function (e) {
        e.preventDefault();
        var it = $(this);
        var id;
        if (it.attr('data-id')) {
            id = it.attr('data-id');
        } else {
            id = it.closest('li').data('id');
        }
        change_status(it, id);
    });

    $('.toolbar').on('click', '.delete-items', function (e) {
        e.preventDefault();
        var ids = [];
        var id;
        $('input[type="checkbox"]').each(function () {
            if ($(this).prop('checked')) {
                id = $(this).closest('li').data('id');
                if (id) {
                    ids.push(id);
                } else {
                    ids.push($(this).closest('tr').data('id'));
                }
            }
        });
        if (ids.length) {
            if (!confirm('Это действие необратимо. Продолжить?')) {
                return false;
            }
            loader('.contentWrapMar', 1);
            var table = $('#parameters').data('table');
            $.ajax({
                url: '/wezom/ajax/deleteMass',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    ids: ids,
                    table: table
                },
                success: function (data) {
                    window.location.reload();
                }
            });
        } else {
            generate('Нечего удалять!', 'warning');
        }
    });

    $('.toolbar').on('click', '.publish', function (e) {
        e.preventDefault();
        var ids = [];
        $('input[type="checkbox"]').each(function () {
            if ($(this).prop('checked')) {
                id = $(this).closest('li').data('id');
                if (id) {
                    ids.push(id);
                } else {
                    ids.push($(this).closest('tr').data('id'));
                }
            }
        });
        if (ids.length) {
            loader('.contentWrapMar', 1);
            var table = $('#parameters').data('table');
            var status = $(this).data('status');
            $.ajax({
                url: '/wezom/ajax/setStatusMass',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    status: status,
                    ids: ids,
                    table: table
                },
                success: function (data) {
                    window.location.reload();
                }
            });
        } else {
            generate('Нечему задавать статус!', 'warning');
        }
    });

    if ($('.changeField').length) {
        $('.changeField').on('click', function (e) {
            e.preventDefault();
            var it = $(this);
            var id = it.data('id');
            var field = it.data('field');
            var table = $('#parameters').data('table');
            $.ajax({
                url: '/wezom/ajax/change_field',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    field: field,
                    table: table
                },
                success: function (data) {
                    if (data.current) {
                        it.text('Да');
                    } else {
                        it.text('Нет');
                    }
                }
            });
        });
    }

    if ($('#orderParameters').length) {
        $('button').on('click', function () {
            if ($(this).attr('href')) {
                window.location.href = $(this).attr('href');
            } else {
                preloader();
                var it = $(this);
                var form = it.closest('.widgetContent');
                var action = form.data('ajax');
                if (action) {
                    $.ajax({
                        url: '/wezom/ajax/' + action,
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            data: form.find('input,select,textarea').serializeArray(),
                            id: $('#orderParameters').data('id')
                        },
                        success: function (data) {
                            if (data.success) {
                                if (data.msg) {
                                    generate(data.msg, 'success');
                                }
                                if (data.reload) {
                                    window.location.reload();
                                } else {
                                    preloader();
                                }
                            } else {
                                if (data.msg) {
                                    generate(data.msg, 'warning');
                                }
                                preloader();
                            }
                        }
                    });
                }
            }
        });
        $('select[name="delivery"]').on('change', function () {
            if ($(this).val() == 2) {
                $('input[name="number"]').closest('.form-group').show();
            } else {
                $('input[name="number"]').closest('.form-group').hide();
            }
        });

        var setAmount = function () {
            var amount = 0;
            var pos = 0;
            $('#orderItemsList tr').each(function () {
                var cost = parseInt($(this).find('.tableOrderItemsCost').text());
                var count = parseInt($(this).find('.count').val());
                amount += cost * count;
                pos += 1;
            });
            $('#orderAmount span').text(amount);
            $('#orderPositionsCount').text(pos);
        };
        $('#orderItems').on('click', function () {
            var it = $(this);
            var table = it.closest('.widgetContent').find('table');
            table.find('tr').each(function () {
                var it = $(this);
                var count = it.find('.count').val();
                var catalog_id = it.find('.catalog_id').val();
                var size_id = it.find('.size_id').val();
                $.ajax({
                    url: '/wezom/ajax/orders/orderItems',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        count: count,
                        catalog_id: catalog_id,
                        size_id: size_id,
                        id: $('#orderParameters').data('id')
                    },
                    success: function (data) {
                        if (data.success && data.email_button) {
                            $('#sendEmail').removeClass('hide');
                        }
                        preloader();
                    }
                });
            });
            setAmount();
        });
        
        $('.orderPositionDelete').on('click', function (e) {
            e.preventDefault();
            preloader();
            var it = $(this).closest('tr');
            $.ajax({
                url: '/wezom/ajax/orders/orderPositionDelete',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    order_id: $('#orderParameters').data('id'),
                    order_item_id: it.data('order-item-id')
                },
                success: function (data) {
                    if (data.success) {
                        it.remove();
                        setAmount();
                        $('#sendEmail').removeClass('hide');
                        generate('Позиция удалена!', 'success', 5000);
                    } else {
                        generate('Ошибка удаления!', 'warning', 5000);
                    }
                    preloader();
                }
            });
        });
        
        $('#sendEmail').on('click', function () {
            $.ajax({
                url: '/wezom/ajax/orders/sendEmail',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: $('#orderParameters').data('id')
                },
                success: function (data) {
                    if (data.success) {
                        generate('E-Mail спешно отправлен заказчику!', 'success', 5000);
                        $('#sendEmail').addClass('hide');
                    } else {
                        if (data.response) {
                            generate(data.response, 'warning', 5000);
                        } else {
                            generate('E-Mail не был отправлен!', 'warning', 5000);
                        }
                    }
                    preloader();
                },
                error: function () {
                    generate('E-Mail не был отправлен!', 'warning', 5000);
                    preloader();
                }
            });
        });
    }
});


//////////////// DAMN UPLOADER
$(function () {
    var dropzone = $('.dropZone');
    if (dropzone.length) {
        var upl = dropzone.data('upload');
        var sort = dropzone.data('sortable');
        var def = dropzone.data('default');

        var getUploadedPhotos = function () {
            $.ajax({
                type: 'POST',
                url: '/wezom/ajax/' + upl,
                dataType: 'JSON',
                success: function (data) {
                    $('.dropDownload').html(data.images);
                    if (parseInt(data.count)) {
                        $('.loadedBox .checkAll').fadeIn(300);
                    }
                }
            });
        };
        getUploadedPhotos();

        $('.dropDownload').sortable({
            connectWith: ".loadedBlock",
            handle: ".loadedDrag",
            cancel: '.loadedControl',
            placeholder: "loadedBlockPlaceholder",
            update: function () {
                var order = [];
                $(this).find('.loadedBlock').each(function () {
                    order.push($(this).data('image'));
                });
                $.ajax({
                    type: "POST",
                    url: "/wezom/ajax/" + sort,
                    data: {
                        order: order
                    }
                });
            }
        });

        $('.dropDownload').on('click', '.loadedCover .btn.btn-success', function () {
            var it = $(this),
                    itP = it.closest('.loadedBlock'),
                    id = itP.data('image');
            $.ajax({
                url: '/wezom/ajax/' + def,
                type: 'POST',
                data: {
                    id: id
                }
            });
        });
    }
});


//////////////// RELATED ITEMS
$(function () {
    var interval;
    var list = $('#relatedList');
    var limit = parseInt(list.data('limit'));
    var page = 1;
    var pages = 1;
    var search = function () {
        loader(list, 1);
        var data = {};
        var key;
        var value;
        $('#relatedItemsBlock input,#relatedItemsBlock select').each(function () {
            key = $(this).data('name');
            value = $(this).val();
            data[key] = value;
        });
        data['id'] = list.data('item');
        data['limit'] = limit;
        data['page'] = page;
        $.ajax({
            url: '/wezom/ajax/catalog/searchItems',
            type: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (resp) {
                if (!parseInt(resp.count)) {
                    list.html('<p class="relatedMessage">По заданным параметрам товары не найдены!</p>');
                    page = 1;
                } else {
                    var pager = '';
                    if (resp.count > limit) {
                        pager += '<div class="relatedPager">';
                        pages = Math.ceil(resp.count / limit);
                        for (var j = 1; j <= pages; j++) {
                            if (j == page) {
                                pager += '<span class="active">' + j + '</span>';
                            } else {
                                pager += '<span>' + j + '</span>';
                            }
                        }
                        pager += '</div>';
                    } else {
                        page = 1;
                    }
                    var html = pager;
                    for (var i = 0; i < resp.items.length; i++) {
                        html += '<div class="relatedItem" data-id="' + resp.items[i].id + '">';
                        if (resp.items[i].image) {
                            html += '<img src="' + resp.items[i].image + '" alt="">';
                        }
                        html += '<div class="relatedName">' + resp.items[i].name + '<br>' + resp.items[i].cost + ' грн</div>';
                        html += '</div>';
                    }
                    html += pager;
                    list.html(html);
                }
                loader(list, 0);
            }
        });
    };
    var listBlock = $('#relatedItemsBlock');
    listBlock.on('change', 'select', function () {
        search();
    });
    listBlock.on('keypress', 'input', function () {
        clearTimeout(interval);
        interval = setTimeout(function () {
            search();
        }, 500);
    });

    list.on('click', '.relatedItem', function () {
        loader(list, 1);
        var it = $(this);
        var clone = it.clone();
        $.ajax({
            dataType: "json",
            type: 'POST',
            data: {
                who_id: $('#relatedList').data('item'),
                with_id: $(this).data('id')
            },
            url: '/wezom/ajax/catalog/addItemToRelated',
            success: function (data) {
                if (data.success) {
                    clone.addClass('active');
                    clone.appendTo('.listSimilar');
                    search();
                } else {
                    generate(data.msg, 'warning');
                }
            }
        });
    });

    $('.listSimilar').on('click', '.relatedItem', function () {
        if (!confirm('Удалить товар из сопутствующих?')) {
            return false;
        }
        var it = $(this);
        var with_id = it.data('id');
        var who_id = $('#relatedList').data('item');
        $.ajax({
            dataType: "json",
            type: 'POST',
            data: {
                who_id: who_id,
                with_id: with_id
            },
            url: '/wezom/ajax/catalog/removeItemFromRelated',
            success: function (data) {
                if (data.success) {
                    it.remove();
                    search();
                }
            }
        });
    });

    list.on('click', '.relatedPager span', function () {
        var it = $(this);
        if (it.hasClass('active')) {
            return false;
        }
        page = parseInt(it.text());
        list.find('.relatedPager span.active').removeClass('active');
        list.find('.relatedPager span:nth-child(' + page + ')').addClass('active');
        search();
    });
});


/////////// ADD POSITION TO ORDER
$(function () {
    var listBlock = $('#orderItemsBlock');
    if (listBlock.length) {
        var interval;
        var list = $('#orderItemsList');
        var limit = parseInt(list.data('limit'));
        var page = 1;
        var pages = 1;
        var active = 0;
        var action = 'catalog';
        if ($('#myForm').data('action')) {
            action = $('#myForm').data('action');
        }
        var search = function () {
            loader(list, 1);
            var data = {};
            var key;
            var value;
            $('#orderItemsBlock input,#orderItemsBlock select').each(function () {
                key = $(this).data('name');
                value = $(this).val();
                data[key] = value;
            });
            data['id'] = list.data('item');
            data['limit'] = limit;
            data['page'] = page;
            $.ajax({
                url: '/wezom/ajax/' + action + '/searchItems',
                type: 'POST',
                dataType: 'JSON',
                data: data,
                success: function (resp) {
                    if (!parseInt(resp.count)) {
                        list.html('<p class="relatedMessage">По заданным параметрам ничего не найдено!</p>');
                        page = 1;
                    } else {
                        var pager = '';
                        if (resp.count > limit) {
                            pager += '<div class="relatedPager">';
                            pages = Math.ceil(parseInt(resp.count) / limit);
                            for (var j = 1; j <= pages; j++) {
                                if (j == page) {
                                    pager += '<span class="active">' + j + '</span>';
                                } else {
                                    pager += '<span>' + j + '</span>';
                                }
                            }
                            pager += '</div>';
                        } else {
                            page = 1;
                        }
                        var html = pager;
                        for (var i = 0; i < resp.items.length; i++) {
                            if (active == resp.items[i].id) {
                                html += '<div class="relatedItem active" data-id="' + resp.items[i].id + '">';
                            } else {
                                html += '<div class="relatedItem" data-id="' + resp.items[i].id + '">';
                            }
                            if (resp.items[i].image) {
                                html += '<img src="' + resp.items[i].image + '" alt="">';
                            }
                            if (resp.items[i].cost) {
                                html += '<div class="relatedName">' + resp.items[i].name + '<br>' + resp.items[i].cost + ' грн</div>';
                            } else {
                                html += '<div class="relatedName">' + resp.items[i].name + '</div>';
                            }
                            if (resp.items[i].email) {
                                html += '<div class="relatedName">' + resp.items[i].email + '</div>';
                            }
                            html += '</div>';
                        }
                        html += pager;
                        list.html(html);
                    }
                    loader(list, 0);
                }
            });
        };
        listBlock.on('change', 'select', function () {
            search();
        });
        listBlock.on('keypress', 'input', function () {
            clearTimeout(interval);
            interval = setTimeout(function () {
                search();
            }, 500);
        });
        list.on('click', '.relatedPager span', function () {
            var it = $(this);
            if (it.hasClass('active')) {
                return false;
            }
            page = parseInt(it.text());
            list.find('.relatedPager span.active').removeClass('active');
            list.find('.relatedPager span:nth-child(' + page + ')').addClass('active');
            search();
        });
        var itemPlace = $('#itemPlace');
        list.on('click', '.relatedItem', function () {
            loader(list, 1);
            list.find('.relatedItem.active').removeClass('active');
            var it = $(this);
            active = it.data('id');
            it.addClass('active');
            $('#orderItemId').val(active);
            $.ajax({
                url: '/wezom/ajax/' + action + '/getItem',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: active
                },
                success: function (resp) {
                    if (resp.success) {
                        var html = '';
                        if (resp.item.image) {
                            html += '<a href="' + resp.item.image_big + '" rel="lightbox">';
                            html += '<img src="' + resp.item.image + '" class="someImage" />';
                            html += '</a>';
                        }
                        html += '<div class="someBlock"><a href="' + resp.item.link + '" target="_blank">' + resp.item.name + '</a></div>';
                        if (resp.item.rubric) {
                            html += '<div class="someBlock"><b>Рубрика:</b> ' + resp.item.rubric + '</div>';
                        }
                        if (resp.item.date) {
                            html += '<div class="someBlock"><b>Дата публикации:</b> ' + resp.item.date + '</div>';
                        }
                        if (resp.item.brand_id && resp.item.brand_name) {
                            html += '<div class="someBlock"><b>Бренд:</b> <a href="' + resp.item.brand_link + '" target="_blank">' + resp.item.brand_name + '</a></div>';
                        }
                        if (resp.item.cost) {
                            html += '<div class="someBlock"><b>Цена:</b> ' + resp.item.cost + ' грн</div>';
                        }
                        if (resp.item.uid) {
                            html += '<div class="someBlock"><b>ID:</b> ' + resp.item.uid + '</div>';
                        }
                        if (resp.item.email) {
                            html += '<div class="someBlock"><b>E-Mail:</b> <a href="mailto:' + resp.item.email + '">' + resp.item.email + '</a></div>';
                        }
                        if (resp.item.phone) {
                            html += '<div class="someBlock"><b>Номер телефона:</b> ' + resp.item.phone + '</div>';
                        }
                        itemPlace.html(html);
                    }
                    loader(list, 0);
                }
            });
        });
    }
});

var multi_select = function () {
    $('.multiSelectBlock select').each(function () {
        if ($(this).prop('multiple')) {
            $(this).multiSelect({
                keepOrder: true,
                selectableHeader: "<div class='multiSelectNavigation custom-header select-all'>Выбрать все</div>",
                selectionHeader: "<div class='multiSelectNavigation custom-header deselect-all'>Отменить все</div>"
            });
        }
    });
    $('.select-all').click(function () {
        $(this).closest('.multiSelectBlock').find('select').multiSelect('select_all');
        return false;
    });
    $('.deselect-all').click(function () {
        $(this).closest('.multiSelectBlock').find('select').multiSelect('deselect_all');
        return false;
    });
};
$(function () {
    if ($('.multiSelectBlock').length) {
        multi_select();
    }

    $('form').on('submit', function () {
        preloader();
    });

    $('form.filterForm').on('click', 'input[type="submit"]', function () {
        var form = $(this).closest('form');
        var arr = [];
        var key;
        var value;
        form.find('input,select').each(function () {
            if (!$(this).attr('type') || $(this).attr('type') != 'submit') {
                key = $(this).attr('name');
                value = $(this).val();
                if (value != "") {
                    arr.push(key + '=' + value);
                }
            }
        });
        var link = form.attr('action');
        if (arr.length) {
            link += '?' + arr.join('&');
        }
        window.location.href = link;
        return false;
    });

    var uid;
    $('#sendThePassword').on('click', function (e) {
        e.preventDefault();
        uid = $(this).data('id');
        var message = $('#sendThePasswordForm').html();
        var footer = '<span class="agencyErrorBlock"></span><button class="btn btn-default popup-modal-dismiss" type="button" style="margin-right: 10px;">Закрыть</button><button class="btn btn-primary sendNewPasswordPlease" type="button">Выслать пароль</button>';
        var header = 'Отправить пароль на E-Mail пользователя';
        $(document).alert2({
            message: message,
            openCallback: function () {},
            closeCallback: function () {},
            headerCOntent: header,
            footerContent: footer,
            closeOnBgClick: false,
            enableEscapeKey: false,
            typeMessage: false
        });
        return false;
    });
    $('body').on('click', '.sendNewPasswordPlease', function () {
        var it = $(this);
        wPreloader.show(it.closest('.modal-content'));
        var password = it.closest('.modal-content').find('input[name="password"]').val();
        $.ajax({
            url: '/wezom/ajax/sendNewPassword',
            type: 'POST',
            dataType: 'JSON',
            data: {
                password: password,
                uid: uid
            },
            success: function (data) {
                if (data.success) {
                    $.magnificPopup.close();
                    setTimeout(function () {
                        generate(data.response, 'success');
                    }, 350);
                } else {
                    generate(data.response, 'warning');
                }
                preloader();
            },
            error: function () {
                generate('Произошел сбой. Пароль не был отправлен', 'warning');
                preloader();
            }
        });
    });

    $('button.setPosition').on('click', function () {
        var id = $(this).closest('tr').data('id');
        var input = $(this).parent().find('input');
        var pos = parseInt(input.val());
        $.ajax({
            url: '/wezom/ajax/catalog/setPosition',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                sort: pos
            },
            success: function (data) {
                input.val(pos);
                generate('Позиция изменена!', 'success', 5000);
            },
            error: function (data) {
                generate('Произошел сбой! Позиция не изменена', 'warning', 5000);
            }
        });
    });

    $('button.setSpecificationPosition').on('click', function () {
        var id = $(this).closest('tr').data('id');
        var input = $(this).parent().find('input');
        var pos = parseInt(input.val());
        $.ajax({
            url: '/wezom/ajax/specifications/setPosition',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                sort: pos
            },
            success: function (data) {
                input.val(pos);
                generate('Позиция изменена!', 'success', 5000);
            },
            error: function (data) {
                generate('Произошел сбой! Позиция не изменена', 'warning', 5000);
            }
        });
    });

    $('.dropDownload').on('click', 'button', function () {
        if (!$(this).hasClass('btnImage')) {
            var href = $(this).attr('href');
            console.log(href);
            if (href) {
                window.location.href = href;
            }
        }
    });
    
    var change_status_sitemap = function (it, id) {
        var current = it.attr('data-status');
        var table = $('#parameters').data('table');
        $.ajax({
            url: '/wezom/ajax/setStatusSitemap',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                current: current,
                table: table
            },
            success: function (data) {
                it.data('status', data.status);
                var html;
                if (data.ids) {
                    for(i=0; i<data.ids.length; i++) {
                        it = $('.status-column [data-id = "'+data.ids[i]+'"] ');
                        if (data.status == 1) {
                            html = '<i class="fa fa-check"></i>';
                            it.removeClass('btn-danger');
                            it.addClass('btn-success');
                        } else {
                            html = '<i class="fa fa-dot-circle-o"></i>';
                            it.removeClass('btn-success');
                            it.addClass('btn-danger');
                        }
                        it.html(html);
                        it.attr('data-status',data.status);
                    }
                    
                }

                $('.liTipContent').remove();
                $('.bs-tooltip').each(function () {
                    $(this).liTip();
                });
                
            }
        });
    };
    $('.setStatusSitemap').on('click', function (e) {
        e.preventDefault();
        var it = $(this);
        var id;
        if (it.attr('data-id')) {
            id = it.attr('data-id');
        } else {
            id = it.closest('li').data('id');
        }
        change_status_sitemap(it, id);
    });
	
});