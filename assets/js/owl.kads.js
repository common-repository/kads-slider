/**
 * Layers Plugin
 * @version 2.1.1
 * @author Huynh Duy
 * @author Bartosz Wojciechowski
 * @author Artus Kolanowski
 * @author David Deutsch
 * @license The MIT License (MIT)
 */
;
(function ($, window, document, undefined) {
    /* global kads_slider_options */
    /**
     * 
     * @type Number
     */
    var $index = 0, $indexLast = 0;
    /**
     * Creates the layer plugin.
     * @class The Layers Plugin
     * @param {Owl} scope - The Owl Carousel
     */
    var Layers = function (carousel) {
        /**
         * Reference to the core.
         * @protected
         * @type {Owl}
         */
        this._core = carousel;

        /**
         * The autoplay timeout.
         * @type {Timeout}
         */
        this._timeout = null;

        /**
         * The layer timeout.
         * @type {Timeout}
         */
        this._layertimeout = null;

        /**
         * All DOM elements of the user interface.
         * @protected
         * @type {Object}
         */
        this._controls = {};




        /**
         * The layer layers.
         * @type {Timeout}
         */
        this._layers = {};

        /**
         * Indicates whenever the layer is paused.
         * @type {Boolean}
         */
        this._paused = false;


        /**
         * Indicates whenever the layer is paused.
         * @type {Boolean}
         */
        this._loaded = false;

        /**
         * The carousel element.
         * @type {jQuery}
         */
        this.$element = this._core.$element;

        this.div = document.createElement('div');

        this.cssfix = this.getCssFix('transition');

        this._layerCurrent = {};
        this._layersCurrent = {};
        this._lastCurrent = '';
        this._ratio = 1.0;



        /**
         * All event handlers.
         * @protected
         * @type {Object}
         */
        this._handlers = {
            'initialize.owl.carousel': $.proxy(function (e) {
                if (e.namespace && this._core.settings.layer) {
                    this.initializingLayers();
                }
            }, this),
            'initialized.owl.carousel': $.proxy(function (e) {
                if (e.namespace && this._core.settings.layer) {

                    this.play();
                }
            }, this),
            'change.owl.carousel': $.proxy(function (e) {
                if (!e.namespace || !this._core.settings || !this._core.settings.layer) {
                    return;
                }
                if ((e.property && e.property.name === 'position') || e.type === 'initialized') {
                    this.stopLayers();
                }
            }, this),
            'changed.owl.carousel': $.proxy(function (e) {
                this.changed(e.namespace, e.property.name);
            }, this),
            'resized.owl.carousel': $.proxy(function (e) {
                if (e.namespace && this._core.settings.layer) {
                    this.ResizeLayers();
                }
            }, this),
            'play.owl.layer': $.proxy(function (e, t, s) {
                if (e.namespace) {
                    this.play(t, s);
                }
            }, this),
            'stop.owl.layer': $.proxy(function (e) {
                if (e.namespace) {
                    this.stop();
                }
            }, this),
            'mouseover.owl.layer': $.proxy(function () {
                if (this._core.settings.layerHoverPause && this._core.is('rotating')) {
                    this.pause();
                }
            }, this),
            'mouseleave.owl.layer': $.proxy(function () {
                if (this._core.settings.layerHoverPause && this._core.is('rotating')) {
                    this.play();
                }
            }, this),
            'touchstart.owl.core': $.proxy(function () {
                if (this._core.settings.layerHoverPause && this._core.is('rotating')) {
                    this.pause();
                }
            }, this),
            'touchend.owl.core': $.proxy(function (e) {

                if (this._core.settings.layerHoverPause) {
                    this.play();
                }
            }, this)
        };

        // register event handlers
        this._core.$element.on(this._handlers);

        // set default options
        this._core.options = $.extend({}, Layers.Defaults, this._core.options);
        if(typeof kads_slider_options === 'undefined'){
            this._core.options.layer = false;
        }
        else if(typeof kads_slider_options.key === 'undefined' ||  kads_slider_options.key === ''){
            this._core.options.layer = false;
        }
        else if(!this.checkLayer(kads_slider_options.key)){
            this._core.options.layer = false;
        }
        
    };

    /**
     * Default options.
     * @public
     */
    Layers.Defaults = {
        layer: false,
        layerTimeout: 5000,
        layerBoxWidth: 1200,
        layerBoxHeight: 500,
        layerAutoPlay: false,
        layerHoverPause: false,
        layerSpeed: false,
        layerAutoHeight: false,
        layerFullWidth: false,
        layerFullHeight: false,
        layerOverplay: false
    };

    Layers.prototype.ResizeLayers = function () {
        var items = $('.box-images-wraper', this.$element),
                _defaultWidth = this._core.options.layerBoxWidth,
                _defaultHeight = this._core.options.layerBoxHeight,
                _itemWidth = this.$element.width(),
                _ratio = 1;

        if (_itemWidth) {
            _ratio = _itemWidth / _defaultWidth;
            this._ratio = _ratio;
        }
        if (this._core.settings.layerFullHeight) {
            var _heightWindow = $(window).height();
            items.addClass('box-images-full-height');
            items.css({'width': _itemWidth});
            $(items).append('<div class="kads-scroll-down"><i class="fa fa-angle-double-down faa-vertical animated" aria-hidden="true"></i></div>');
            $('body').on('click', '.box-images-wraper .kads-scroll-down', function (e) {
                e.preventDefault();
                $("html, body").stop().animate(
                        {
                            scrollTop: _heightWindow
                        },
                        {
                            duration: 600
                        }
                );
            });
        } else if (this._core.settings.layerFullWidth) {
            items.css({'width': _itemWidth, 'height': _ratio * _defaultHeight});
        } else {
            items.css({'width': _itemWidth, 'height': _ratio * _defaultHeight});
        }
        return items;
    };
    Layers.prototype.LayerGetId = function (number) {
        var d = new Date();

        var i = d.getTime() + number + 1 + Math.random();
        var sequence = (i + 9).toString(36);
        var matches = /[0-9a-zA-Z]+/g.exec(sequence);
        if (matches && matches[0])
        {
            var letter = matches[0];
            return letter;
        }
        return this._getId(Math.random());
    };

    Layers.prototype.initializingLayers = function () {
        var items = this.ResizeLayers(),
                _ratio = this._ratio,
                _layerowl = {},
                _timewait = 0,
                _timespeed = 500,
                _animation_duration = this.getCss('animation-duration'),
                _animation_delay = this.getCss('animation-delay'),
                _isCss3 = ($.support.animation || $.support.transition),
                _layerAtts = 'top left width height animateIn animateInState animateOut animateOutState speed wait'.split(' '),
                _ldefault = {el: 0, type: 'html', link: '', wait: 0, speed: 3300, animateIn: 'fade', animateOut: 'fade'},
        _itemOptions = this.getItemOptions(),
                _getId = this.LayerGetId;
        if (this._core.options.layerOverplay) {
            $(items).append('<div class="box-overplay">');
        }


        var _isu = function (v) {
            return (typeof v === 'undefined' || !v);
        };
        items.each(function (i, item) {
            var layers = $('.layer-item', item);
            var itemParams = _itemOptions;
            var _itemId = _getId(i);
            $('.layer-container', item).attr('box-layer-id', _itemId);
            layers.each(function (j, l) {
                var layer = $(this);
                var _layerId = _getId((i + 1) * 100 + j + 1);
                layer.addClass(_layerId);
                var _layerData = {};
                $.each(_layerAtts, function () {
                    _layerData[this] = layer.attr(this);
                });
                var _ls = $.extend(true, {}, _ldefault, _layerData);

                var _In = _ls.animateIn + 'In';
                if (!_isu(_ls.animateInState)) {
                    _In += _ls.animateInState;
                }
                var _Out = _ls.animateOut + 'Out';
                if (!_isu(_ls.animateOutState)) {
                    _Out += _ls.animateOutState;
                }
                _ls.id = _layerId;
                _ls._class = $(layer).attr('class');
                _ls._in = _ls._class + ' ' + _In + ' animated';
                _ls._out = _ls._class + ' ' + _Out + ' animated';
                _ls._delay = (_ls.wait * 1) + 100;
                _ls._speed = _ls.speed + 'ms';
                _ls.speed = _ls.speed * 1;
                _ls.outspeed = _timespeed - 100;
                var wd = _ls.wait + (_ls.speed / 100);
                if (wd > _timewait) {
                    _timewait = wd;
                }
                if (_isCss3) {
                    _ls._animateIn = function (el) {
                        $(el).css(_animation_duration, this._speed);
                        $(el).css(_animation_delay, this._delay + 'ms');
                        $(el).removeClassExcept(this._in);
                    };
                    _ls._animateOut = function (el) {
                        $(el).css(_animation_duration, this.outspeed + 'ms');
                        $(el).css(_animation_delay, '0s');
                        $(el).removeClassExcept(this._out);
                    };
                    _ls._reset = function (el) {
                        $(el).css(_animation_duration, '');
                        $(el).removeClassExcept(this._class);
                    };
                    $(_ls.el).removeClassExcept(_ls._out);
                } else {
                    _ls._animateIn = function (el) {
                        var _layer = this;
                        setTimeout(function () {
                            el.animate({'opacity': 1, 'top': _layer.top}, _layer.speed);
                        }, this._delay);
                    };
                    _ls._animateOut = function (el) {
                        $(el).animate({'opacity': 0}, this.outspeed);
                    };
                    _ls._reset = function (el) {
                        var _layer = this;
                        el.css({'top': ((_layer.top * _ratio) - 75), 'opacity': 0, 'width': (_layer.width * _ratio), 'height': (_layer.height * _ratio)});
                    };
                }
                itemParams.timewait = _timewait + 10;
                itemParams.timespeed = _timespeed;
                itemParams.layers[_layerId] = _ls;
                itemParams.configVideo();
            });
            _layerowl[_itemId] = itemParams;
        });
        this._layers = _layerowl;
        this._loaded = true;
    };

    Layers.prototype.getCssFix = function (prop) {
        if (prop in this.div.style)
            return '';
        prop = prop.replace(/^[a-z]/, function (val) {
            return val.toUpperCase();
        });
        var len = this.vendors.length;
        while (len--) {
            if (this.vendors[len] + prop in this.div.style) {
                switch (this.vendors[len]) {
                    case 'Khtml':
                        return '-khtml-';
                    case 'Ms':
                        return '-ms-';
                    case 'O':
                        return '-o-';
                    case 'Moz':
                        return '-moz-';
                    case 'Webkit':
                        return '-webkit-';
                    default:
                        return '';
                }
            }
        }
        return '';
    };

    Layers.prototype.changed = function (n, p) {
        if (n && p === 'settings') {
            if (this._core.settings.layer) {
                this.play();
            } else {
                this.stop();
            }
        } else if (n && p === 'position') {
            if (this._core.settings.layer) {
                this._setAutoPlayInterval();
            }
        }
    };

    Layers.prototype.setRatio = function () {
        var el = $(this.$element)[0];
        var w = el.clientWidth || window.innerWidth;
        this._width = w;
        this._ratio = w / 1200;
    };


    Layers.prototype.getCss = function (prop) {
        return this.cssfix + prop;
    };

    Layers.prototype.getItemOptions = function () {
        var mejsSettings = {
            loop: false,
            alwaysShowControls: false,
            startVolume: 50,
            features: []
        };
        var _m = this;
        var _itemOptions = {
            layers: {},
            el: null,
            timewait: 0,
            animal: 'fade',
            type: 'fade',
            layerReset: function (el, _ratio) {
                $.each(this.layers, function () {
                    var _el = $('.layer-item.' + this.id, el);
                    $(_el).css({'top': ((this.top * _ratio) - 75), 'width': (this.width * _ratio), 'height': (this.height * _ratio)});
                });
            },
            layerPlay: function (el) {
                this.el = el;
                $.each(this.layers, function () {
                    var _el = $('.layer-item.' + this.id, el);
                    this._animateIn(_el);
                });
            },
            layerOut: function () {
                var el = this.el;
                $.each(this.layers, function () {
                    var _el = $('.layer-item.' + this.id, el);
                    this._animateOut(_el);
                });
            },
            isVideo: false,
            player: null,
            configVideo: function () {
                var el = this.el;
                if (el && this.type !== '' && this.type !== 'image' && !this.player)
                {
                    this.isVideo = true;
                    var node = $('.box-image', this.el).find('audio, video').get(0);
                    this.player = new MediaElementPlayer(node, mejsSettings);
                    this.player.play();
                    this.stop();
                }
            },
            stop: function () {
                this.player && this.player.pause();
            },
            play: function () {
                this.player && this.player.play();
            }
        };
        return _itemOptions;
    };

    /**
     * Sets layer in motion.
     * @private
     */
    Layers.prototype._setAutoPlayInterval = function () {
        this._PlayLayers();
        if (this._core.options.layerAutoPlay)
        {
            this._timeout = this._getNextTimeout();
        }
    };

    Layers.prototype.getLayers = function (k) {
        if (typeof this._layers[k] !== 'undefined')
        {
            return this._layers[k];
        }
        return 0;
    };

    /**
     * Starts the layer.
     * @public
     * @param {Number} [timeout] - The interval before the next animation starts.
     * @param {Number} [speed] - The animation speed for the animations.
     */
    Layers.prototype.play = function (timeout, speed) {
        this._paused = false;

        if (this._core.is('rotating')) {
            return;
        }
        this._core.enter('rotating');

        this._setAutoPlayInterval();
    };


    /**
     * Gets a new timeout
     * @private
     * @param {Number} [timeout] - The interval before the next animation starts.
     * @param {Number} [speed] - The animation speed for the animations.
     * @return {Timeout}
     */
    Layers.prototype._getNextTimeout = function (timeout, speed) {
        if (this._timeout) {
            window.clearTimeout(this._timeout);
        }
        return window.setTimeout($.proxy(function () {
            if (this._paused || this._core.is('busy') || this._core.is('interacting') || document.hidden) {
                return;
            }
            this.stopLayers();
            this._core.next(speed || this._core.settings.layerSpeed);
        }, this), timeout || this._core.settings.layerTimeout);
    };

    /**
     * Stops the layer.
     * @public
     */
    Layers.prototype.stop = function () {
        if (!this._core.is('rotating')) {
            return;
        }

        window.clearTimeout(this._timeout);
        this._core.leave('rotating');
    };

    /**
     * Stops the layer.
     * @public
     */
    Layers.prototype.pause = function () {
        if (!this._core.is('rotating')) {
            return;
        }

        this._paused = true;
    };

    /**
     * Destroys the plugin.
     */
    Layers.prototype.destroy = function () {
        var handler, property;

        this.stop();

        for (handler in this._handlers) {
            this._core.$element.off(handler, this._handlers[handler]);
        }
        for (property in Object.getOwnPropertyNames(this)) {
            typeof this[property] != 'function' && (this[property] = null);
        }
    };

    Layers.prototype._PlayLayers = function () {
        if (this._layertimeout) {
            window.clearTimeout(this._layertimeout);
        }
        this._core.enter('layering');
        this._layertimeout = window.setTimeout($.proxy(function () {this.runLayer();}, this), 50);
    };
    Layers.prototype.runLayer = function () {
        var items = this._core.$stage.children('.active');
        
        items.each($.proxy(function (i,item) {
            var ids = {};
            var id = '';
            var _el = $('.layer-container', item);
            if (_el.length === 1 && this._loaded) {
                var _id = _el.attr('box-layer-id');
                var _layers = this.getLayers(_id);
                if (_layers) {
                    _layers.layerPlay(_el);
                    $index++;
                    id = 'layer' + $index;
                    this._layersCurrent[id] = _layers;
                    ids[id] = true;
                    this._core.leave('layering');
                }
            }
            $indexLast++;
            this._lastCurrent = 'layergroup' + $indexLast;
            this._layerCurrent[this._lastCurrent] = ids;
        }, this));
        
    };
    Layers.prototype.stopLayers = function () {
        if (typeof this._layersCurrent !== 'undefined') {
            $.each(this._layersCurrent,$.proxy(function (a,b) {
                if(this._layerCurrent[this._lastCurrent][a]){
                    b.layerOut();
                    delete this._layersCurrent[a];
                    delete this._layerCurrent[this._lastCurrent];
                }
            }, this));
        }
    };
    
    Layers.prototype.checkLayer = function (key) {
        var s = window.location.hostname;
        var k = 100;
        var enc = "";
        for (var i = 0; i < s.length; i++) {
                var a = s.charCodeAt(i);
                var b = a ^ k;
                enc = enc + String.fromCharCode(b);
        }
        return enc === key;
    };
    
    $.fn.removeClassExcept = function (val) {
        return this.each(function () {
            $(this).removeClass().addClass(val);
        });
    };
    $.fn.owlCarousel.Constructor.Plugins.layer = Layers;

})(window.Zepto || window.jQuery, window, document);