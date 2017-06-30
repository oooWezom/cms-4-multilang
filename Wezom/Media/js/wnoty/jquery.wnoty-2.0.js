/*
 wNoty v2.0 / core js file
 WEZOM Studio / Oleg Dutchenko
 */

(function($) {

    if ($.wNoty) {

        // проверка повторного подключения
        console.error('\nПовторное подключение wNoty.js\n ');
        return;

    } else {

        /* объявление wNoty */

        $.wNoty = {
            instance: {
                open: false
            },
            proto: {},
            innermethods: {},
            global: {
                stack: 3,
                delay: 300,
                theme: 'light',
                minifyImmortals: true,
                position: 'bottom right'
            },
            options: {
                status: 'default',
                escapeClose: true,
                modal: false,
                liveTime: 5000,
                die: true,
                immortal: false,
                close: {
                    html: '&times;',
                    title: 'Закрыть'
                }
            }
        };

        /* Приватные переменные */

        var wnt = $.wNoty,
            _pref = 'wnoty_',
            _proto = wnt.proto,
            _options = wnt.options,
            _global = wnt.global,
            _instance = wnt.instance,
            _groups = _instance.groups,
            _methods = wnt.innermethods;



        /* Приватные функции */

        var _jqEl = function(tagName, tagClass, tagId) {
                var newElement = $(document.createElement(tagName));
                if (!!tagId) {
                    newElement.prop('id', tagId);
                }
                if (!!tagClass) {
                    newElement.addClass(tagClass);
                }
                return newElement;
            },

            _generateName = function(className, bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + className;
                return str;
            },

            _immortalClass = function(bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + 'immortal';
                return str;
            },

            _themeClass = function() {
                var str = _pref+'theme_'+_global.theme;
                return str;
            },

            _controlsClass = function(bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + 'controls';
                return str;
            },

            _wrapClass = function(bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + 'wraper';
                return str;
            },

            _overlayClass = function(bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + 'overlay';
                return str;
            },

            _groupClass = function(bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + 'group';
                return str;
            },

            _groupKey = function(groupElement) {
                var id = groupElement[0].id;
                var len = _groupClass().length;
                var str = id.slice(++len);
                return str;
            },

            _informerClass = function(bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + 'informer';
                return str;
            },

            _informerId = function() {
                var date = new Date();
                var str = _informerClass() + '_' + date.getTime();
                return str;
            },

            _messageClass = function(bool) {
                var dot = (bool) ? '.' : '';
                var str = dot + _pref + 'message';
                return str;
            },

        // клонирование объекта
            _cloneObj = function(object) {
                var newObj = {}, key;
                for (key in object) {
                    newObj[key] = object[key];
                }
                return newObj;
            };


        /* прото */


        _proto.checkInstance = function(options) {
            var opts = options;
            if (!_instance.wraper) {
                _proto.wraper();
            }
            if (options.modal) {
                opts.die = false;
            } else {
                if (!_instance.group) {
                    _proto.newGroup();
                }
            }
            if (opts.immortal) {
                opts.die = false;
            }
            return opts;
        };

        _proto.checkEnd = function() {
            var a, b, c, d, e, pos = _global.position.split(' ');
            for (var i = 0; i < pos.length; i++) {
                switch (pos[i]) {
                    case 'left': case 'middle':
                    e = 'left';
                    break;
                    case 'right':
                        e = 'right';
                        break;
                    case 'top': case 'middle':
                    a = 'last';
                    b = 'nextAll';
                    c = 'prepend';
                    d = 'top';
                    break;
                    case 'bottom':
                        a = 'first';
                        b = 'prevAll';
                        c = 'append';
                        d = 'bottom';
                        break;
                }
            };
            _instance.last = a;
            _instance.siblings = b;
            _instance.place = c;
            _instance.posy = d;
            _instance.posx = e;
        };

        _proto.config = function(options, element) {
            opts = _proto.checkInstance(options);
            opts.caller = element || false;
            var informElement = _jqEl('div', _informerClass(), _informerId());
            _proto.generate(opts, informElement);
        };

        _proto.buttonEl = function(options, action) {
            tagName = options.element || 'button';
            var newElement =  _jqEl(tagName, _generateName(action) + ' ' + _generateName('button'));
            if (options.addclass) {
                newElement.addClass(options.addclass);
            }
            if (options.title) {
                newElement.prop('title', options.title);
            }
            if (options['html']) {
                newElement.html(options['html']);
            }
            newElement.on('click', function(event) {
                event.preventDefault;
                if (options['click']) {
                    options['click'].call(this);
                }
                _proto.reMove($(this).closest(_informerClass(true)));
            });
            return newElement;
        };

        _proto.generate = function(options, element) {
            var message = _jqEl('div', _messageClass());
            if (options.header) {
                var head = _jqEl('div', _generateName('header'));
                head.html(options.header);
                element.append(head);
                head.on('click', function(event) {
                    event.preventDefault();
                    $(this).nextAll().stop().slideToggle(_global.delay);
                });
            }
            message.append(options.msg);
            element.append(message);
            if (options.close) {
                element.prepend(_proto.buttonEl(options.close, 'close'));
            }
            if (options.buttons) {
                var controls = _jqEl('div', _controlsClass());
                for (var key in options.buttons) {
                    controls.append(_proto.buttonEl(options.buttons[key], key))
                }
                element.append(controls);
            };
            if (options.status) {
                element.addClass(_generateName('status_'+options.status));
            }
            if (options.addclass) {
                element.addClass(options.addclass);
            }
            if (options.immortal) {
                element.addClass(_immortalClass());
            }
            element.data(_generateName('options'), options);
            _proto.insert(element);
        };

        _proto.getOptions = function(element) {
            return element.data(_generateName('options'));
        };

        _proto.calc = function() {
            _instance.count = _instance.group.children().length;
        };

        _proto.setDie = function(element) {
            var options = _proto.getOptions(element);
            if (options.die) {
                element.data(_generateName('timeout'), setTimeout(function() {
                    _proto.reMove(element);
                }, _options.liveTime));
            }
        };

        _proto.reMove = function(element) {
            if (typeof element.data(_generateName('timeout')) === 'function') {
                clearTimeout(element.data(_generateName('timeout')));
            }
            element.slideUp(_global.delay, function() {
                element.remove();
                _proto.calc();
            });
        };

        _proto.clearElement = function(remover) {
            if (remover.hasClass(_immortalClass())) {
                remover.addClass(_generateName('minify'));
                if (_global.minifyImmortals) {
                    if (remover.find(_generateName('header', true)).length) {
                        remover.find(_generateName('header', true)).nextAll().stop().slideUp(_global.delay);
                    };
                }
            } else {
                _proto.reMove(remover);
            }
        };

        _proto.clearing = function() {
            var childs = _instance.group.children();
            _instance.count = childs.length;
            if (_instance.count >= _global.stack) {
                var index = _global.stack - 1;
                if (_instance.last === 'first') {
                    index = childs.length - _global.stack;
                }
                var removers = $(childs[index]).add($(childs[index])[_instance.siblings]());
                removers.each(function(i, el) {
                    _proto.clearElement($(el));
                });
            }
        };

        _proto.insert = function(element) {
            _proto.clearing();
            _instance.group[_instance.place](element);
            element.css('display', 'none').slideDown(_global.delay, function(){
                _proto.setDie(element);
                _proto.calc();
            });
        };

        _proto.wraper = function() {
            var wrap = _jqEl('div', _wrapClass());
            var overlay = _jqEl('div', _overlayClass());
            overlay.css('display', 'none');
            _instance.body = $('body');
            _instance.body.append(wrap);
            _instance.wraper = $(_wrapClass(true));
            _instance.wraper.after(overlay);
            _instance.overlay = $(_overlayClass(true));
        };

        _proto.newGroup = function() {
            var mainClass = _groupClass(),
                positions = _global.position.split(' '),
                classes = [mainClass, _themeClass()];
            for (var i = 0; i < positions.length; i++) {
                classes.push(_pref+'position_'+positions[i]);
            }
            if (_global.addclass) {
                classes.push(_global.addclass);
            }
            var groupElement = _jqEl('div', classes.join(' '));
            _instance.wraper.append(groupElement);
            _instance.group = $(_groupClass(true));
        };


        /* подготовка */

        var cloneOptions = _cloneObj(_options);
        _proto.checkEnd();

        $(document).on('keydown', function(event) {
            if (event.keyCode === 27) {
                if (_instance.count) {
                    var remover = _instance.group.children()[_instance.last]();
                    if (remover.data(_generateName('options')).escapeClose) {
                        _proto.reMove(remover);
                    }
                }
            }
        });

        /* внутрение методы */

        _methods.init = function(options, isAlert) {
            if (this.length) {
                return this.each(function() {
                    var pluginOptions = $.extend(true, _cloneObj(cloneOptions), options);
                    $(this).on('click', function(event) {
                        _instance.callElement = $(this);
                        event.preventDefault();
                        _proto.config(pluginOptions, $(this));
                    });
                });
            } else if (!isAlert){
                console.warn('$.wNoty\nселектор "'+this.selector+'"\nне найден на странице!!!');
            }
            if (isAlert) {
                var pluginOptions = $.extend(true, _cloneObj(cloneOptions), options);
                _proto.config(pluginOptions);
            }
        };

        _methods.close = function(callback) {
            this.stop().slideUp(_global.delay, function() {
                if (typeof callback === 'function') {
                    callback.call(this);
                }
            });
        };

        /* глобальные методы */

        wnt.configOptions = function(options) {
            $.extend(true, _options, options);
            cloneOptions = _cloneObj(_options);
        };

        wnt.configGlobal = function(options) {
            $.extend(true, _global, options);
            _proto.checkEnd();
        };

        wnt.alert = function(options) {
            if (typeof options === 'object') {
                _methods.init(options, true);
            }
        };

        wnt.destroy = function() {};



        /* Вызов */

        /*wnt.alert = function(options) {
         if (_instance) {
         wnt.proto.config();
         } else {
         wnt.proto.generate();
         }
         };*/

        /* jquery plugin */

        $.fn.wnoty = $.fn.wNoty = function(options) {


            if (_methods[options]) {
                return _methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
            } else if (typeof options === 'object' || !options) {
                return _methods.init.apply(this, arguments);
            }

        }
    }

})(jQuery);