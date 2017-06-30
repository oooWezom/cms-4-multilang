/*
    wPreloader v3.0 / core js file
    WEZOM Studio / Oleg Dutchenko
*/

window.wPreloader = (function(window, document, undefined) {

    /* Приватные переменные */

        var wpr = Object.create(null),
            _pref = 'wpreloader_',
            _timer,
            _array = ['wraper', 'holder', 'ready', 'show', 'block', 'removing'];
            _options = {
                delay: 300,
                block: false,
                mainClass: false,
                markup: '<div class="wpreloader_logo"><ul><li><span></span><span></span></li><li><span></span><span></span></li></ul></div>'
            };

    /* Приватные функции */

        var _getEl = function(where, who) {
                return where.getElementsByClassName(who);
            },

            _crtEl = function(className) {
                var div = document.createElement('div');
                if (className) {
                    div.classList.add(className);
                }
                return div;
            },

            _className = function(num) {
                return _pref + _array[num];
            },

            _showNow = function(wraper, options) {
                wraper.classList.add(_className(3));
                if (options.block) {
                    wraper.classList.add(_className(4));
                }
                if (options.mainClass) {
                    wraper.setAttribute('data-mainclass', options.mainClass);
                    wraper.classList.add(options.mainClass);
                }
                setTimeout(function() {
                    wraper.classList.add(_className(2));
                }, 10);
            },

            _findPreloader = function(wraper) {
                var preloader, elem = _getEl(wraper, _className(0));
                if (elem[0]) {
                    preloader = elem[0];
                } else {
                    preloader = false;
                }
                return preloader;
            },

            _cloneObj = function(object) {
                var newObj = {}, key;
                for (key in object) {
                    newObj[key] = object[key];
                }
                return newObj;
            },

            _extend = function(options) {
                var opts = _cloneObj(_options);
                if (typeof options === 'object') {
                    for (var key in options) {
                        if (_options.hasOwnProperty(key)) {
                            opts[key] = options[key];
                        }
                    }
                }
                return opts;
            },

            _build = function(wraper, options) {
                var holderWraper = _crtEl(_className(0));
                var holder = _crtEl(_className(1));
                holder.innerHTML = options.markup;
                holderWraper.setAttribute('data-delay', options.delay);
                holderWraper.appendChild(holder);
                wraper.appendChild(holderWraper);
                _showNow(wraper, options);
            },

            _remove = function(wraper, callback) {
                var preloader = _findPreloader(wraper);
                if (preloader) {
                    var out = preloader.getAttribute('data-delay');
                    var mClass = wraper.getAttribute('data-mainclass');
                    wraper.classList.add(_className(5));
                    clearTimeout(_timer);
                    _timer = setTimeout(function() {
                        if (typeof mClass != 'undefined') {
                            wraper.classList.remove(mClass);
                            wraper.removeAttribute('data-mainclass');
                        }
                        for (var i = 2; i < _array.length; i++) {
                            wraper.classList.remove(_className(i));
                        }
                        wpr.open = false;
                        preloader.remove();
                        if (typeof callback === 'function') {
                            callback.call();
                        }
                    }, out);
                }
            };


    /* методы */

        wpr.show = function(wraper, options) {
            if (wpr.open) {
                console.warn('wpreloader - is open');
                return false;
            } else {
                wpr.open = false;
                var opts = _extend(options);
                var wrapperElement;
                if (opts.block) {
                    wrapperElement = document.body;
                } else {
                    wrapperElement = wraper || document.body;
                }
                if (wrapperElement.length) {
                    for (var i = 0; i < wrapperElement.length; i++) {
                        _build(wrapperElement[i], opts);
                    }
                } else {
                    _build(wrapperElement, opts);
                }
            }
        };

        wpr.hide = function(wraper, callback) {
            var wrapperElement = wraper || document.body;
            if (wrapperElement.length) {
                for (var i = 0; i < wrapperElement.length; i++) {
                    _remove(wrapperElement[i], callback);
                }
            } else {
                _remove(wrapperElement, callback);
            }
        };

        wpr.config = function(obj) {
            for (var key in obj) {
                if (_options.hasOwnProperty(key)) {
                    _options[key] = obj[key];
                }
            }
        };

    return wpr;

})(this, this.document);