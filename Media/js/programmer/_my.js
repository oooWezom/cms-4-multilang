$(function () {
    wHTML.formOnSubmit = function (formJQ) {
        if (formJQ.data('ajax')) {
            var data = new FormData();
            var name;
            var val;
            var type;
            formJQ.find('input,textarea,select').each(function () {
                var thisJQ = $(this);
                name = thisJQ.data('name');
                val = this.value;
                type = this.type;
                if ((type != 'checkbox' && name) || (type == 'checkbox' && this.checked && name)) {
                    if (type == 'file') {
                        data.append(name, $(this)[0].files[0]);
                    } else if (type == 'radio' && $(this).prop('checked')) {
                        data.append(name, val);
                    } else if (type != 'radio') {
                        data.append(name, val);
                    }
                }
            });
            var request = new XMLHttpRequest();
            request.open("POST", '/form/' + formJQ.data('ajax'));
            request.onreadystatechange = function () {
                var status;
                var resp;
                if (request.readyState == 4) {
                    status = request.status;
                    resp = request.response;
                    resp = jQuery.parseJSON(resp);
                    if (status == 200) {
                        if (resp.success) {
                            if (!resp.noclear) {
                                //formJQ.validReset();
                            }
                            if (resp.clear) {
                                formJQ.find('input').each(function () {
                                    if ($(this).attr('type') != 'hidden' && $(this).attr('type') != 'checkbox') {
                                        $(this).val('');
                                    }
                                });
                                formJQ.find('textarea').val('');
                            }
                            if (resp.insert && resp.insert.selector && resp.insert.html) {
                                $(resp.insert.selector).html(resp.insert.html);
                            }
                            if (resp.response) {
                                generate(resp.response, 'success', 3500);
                            }
                        } else {
                            if (resp.response) {
                                generate(resp.response, 'warning', 3500);
                            }
                        }
                        if (resp.redirect) {
                            if (window.location.href == resp.redirect) {
                                window.location.reload();
                            } else {
                                window.location.href = resp.redirect;
                            }
                        }
                        if (resp.reload) {
                            window.location.reload();
                        }
                        wHTML.formAfterSubmit(resp, formJQ);
                    } else {
                        alert('Something went wrong,\nbut HTML fine ;)');
                    }
                }

            };
            request.send(data);
            return false;
        }

    };
});
