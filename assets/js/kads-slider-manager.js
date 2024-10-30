/* global kadsOptions */
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function ($) {
    'use strict';
    var _m = {};
    $.kadsManager = {
        init: function () {
            // Default value in server
            var sever = {id: '', ajax: '', url: '', current: 0, action: 'add', boxSelected: null};
            /**
             * Server data Params
             * @public id
             * @public ajax
             * @public url
             * @public current
             * @public action
             * @public boxSelected
             */
            this.sever = $.extend(true, {}, sever, kadsOptions);
            /**
             * Set data Vendors
             */
            this.vendors = 'Khtml Ms O Moz Webkit'.split(' ');
            /**
             * Create div element
             */
            this.div = document.createElement('div');
            /**
             * Globle functions Manager
             */
            _m = this;
            /**
             * Css3 Loaded
             */
            this.css3Loaded = {};
            /**
             * Current Script loaded
             */
            this.scriptLoaded = {};
            /**
             * Index for layer in gird edit.
             */
            this.lidx = 0;
            /**
             * Current layer selected.
             */
            this.current = 0;
            /**
             * List layer current edit image in slider.
             */
            this.listLayer = {};
            /**
             * Current layser selected div.
             */
            this.layerSelected = null;
            /**
             * See Layer preview current
             */
            this.layerPreviewCurrent = null;
            /**
             * When stop typing to run funtions.
             */
            this.ivtimer = null;
            /**
             * time interval to run functions.
             */
            this.ivtyping = 500;
            /**
             * Support gird box in image gird edit.
             */
            this.gSize = 10;
            /**
             * Set default on Layer.
             */
            this.handles = "n, e, s, w, ne, se, sw, nw ";
            /**
             * 
             */
            this.viplay = 0;
            /**
             * Frane media;
             */
            this.frame = [];
            /**
             * Set is reset data
             */
            this.isReset = 0;
            /**
             * key data
             */
            this.key = '';
            /**
             * Check is creaete layer
             */
            this.isCreate = 1;
            /**
             * Check is current edit Layer
             */
            this.isEdit = 0;
            /**
             * Set Next time Runing
             */
            this.maxTimeRun = 0;
            /**
             * This is refix in px
             */
            this.refixpx = 3;
            /**
             * Current data selected
             */
            this.currentData = {};
            /**
             * This item data
             */
            this.item = this.idefault();
            /**
             * .kads-slider-page-admin
             */
            this.c = $('.kads-slider-page-admin');
            /**
             * .box-list-items
             */
            this.cl = $('.box-list-items');
            if(this.c.length){
                /**
                 * .slider-preview-warper
                 * @public
                 */
                this.p = $('.slider-preview-warper', this.c);
                /**
                 * .slider-add-warper
                 * @public
                 */
                this.g = $('.slider-add-warper', this.c);
                /**
                 * .kads-slider-timeline-cont Element
                 * @public
                 */
                this.gImage = $('.box-gird-image', this.g);
                /**
                 * .kads-slider-timeline-cont Element in .slider-add-warper
                 * @public
                 */
                this.tl = $('.kads-slider-timeline-cont', this.g);
                /**
                * .kads-slider-ul-list-row Element in .kads-slider-ul-list-row
                * @public
                */
                this.ltl = $('.kads-slider-ul-list-row', this.tl);
                
                
                this.ltlOverPlay = $('.kads-slider-overplay-wrap', this.tl);
                
                /**
                * .kads-slider-child-layer-list Element in .kads-slider-ul-list-row
                * @public
                */
                this.ll = $('.kads-slider-child-layer-list', this.tl);
                /**
                 * .add-actions-popup in .slider-add-warper
                 */
                this.pm = $('.add-actions-popup', this.g);
                /**
                 * .add-actions-popup-bg in .slider-add-warper
                 */
                this.pmBg = $('.add-actions-popup-bg', this.g);
                
                /**
                 * .button-actions-popup-bg in .slider-add-warper
                 */
                this.pmbutton = $('.button-actions-popup', this.g);
                /**
                 * .label in .add-actions-popup in .add-actions-popup
                 */
                this.pl = $('.label', this.pm);
                /**
                 * .button in .add-actions-popup
                 */
                this.pb = $('.button', this.pm);
                /**
                 * .input-main in .slider-add-warper
                 */
                this.im = $('.input-main', this.g);
                /**
                 *  #main-thumb in .slider-add-warper
                 */
                this.ithumb = $('#main-thumb', this.g);
                /**
                 * Control lable
                 */
                this.clabel = null;
                /**
                 * Control time line
                 */
                this.ctline = null;
                /**
                 * .html-layer-template in .slider-add-warper
                 */
                this.ltpl = $('.html-layer-template', this.g).html();
                /**
                 * .html-add-item-template  in .slider-add-warper
                 */
                this.iaddtpl = $('.html-add-item-template', this.g).html();
                /**
                 * .html-layer-timeline-label in .slider-add-warper
                 */
                this.htmlltllabel = $('.html-layer-timeline-label', this.g).html();
                /**
                 * .html-layer-timeline in .slider-add-warper
                 */
                this.htmlltl = $('.html-layer-timeline', this.g).html();
                /**
                 * List controls data
                 */
                this.controls = {};
                /**
                 * List Style data
                 */
                this.styles = {};
                /**
                 * Get current css fix
                 */
                this.cssfix = this.getCssFix('transition');
                /**
                 * Check is css 3
                 */
                this.iscss3 = this.checkCss();
                /**
                 * Element item
                 */
                this.el = null;
                /**
                 * Options animate
                 */
                this.optionsanimate = {};
                /**
                 * Layer Animations
                 */
                this.animations = {
                    'bounce': ' Top Left Right Bottom BottomLeft BottomRight TopLeft TopRight'.split(' '),
                    'zoom': ' Top Left Right Bottom BottomLeft BottomRight TopLeft TopRight'.split(' '),
                    'fade': ' Top Left Right Bottom BottomLeft BottomRight TopLeft TopRight'.split(' '),
                    'flip': ' X Y TopFront TopBack BottomFront BottomBack LeftFront LeftBack RightFront RightBack'.split(' '),
                    'rotate': ' TopLeft TopRight BottomLeft BottomRight'.split(' '),
                    'slide': ' Top Left Right Bottom'.split(' ')
                };
                this.loadEvents();
                this.isFullHeight = $('#kads-yesno-control-sliderfullheight',this.c).is(':checked');
                if(this.isFullHeight){
                    $('.box-gird',_m.c).height('800px');
                }
                
                this.isOverplay = $('#kads-yesno-control-slideroverplay',this.c).is(':checked');
                if(this.isOverplay){
                    this.addOverplay();
                }
            }
            else if(this.cl.length){
                this.loadEventsLists();
            }
            
        },
        /**
         * Check is undefined
         * @param {type} v
         * @returns {Boolean}
         */
        isu: function (v) {
            return (typeof v === 'undefined' || !v);
        },
        /**
         * Set log in browser
         * @returns {undefined}
         */
        logs: function () {
            if (this.isu(console.log)) {
                for (var i in arguments) {
                    console.log(arguments[i]);
                }
            }
        },
        /**
         * Load Event in List data
         * 
         * @returns {undefined}
         */
        loadEventsLists: function () {
            var ajaxurl = this.sever.ajax;
            $('.kads-slider-loading').fadeOut();
            $('.bt-remove',this.cl).on('click', function (event) {
                event.preventDefault();
                var box_item = $(this).closest('.box-item');
                var id = $(this).attr('data-id');
                var title = $(this).attr('data-title');
                var data_params = {
                        action: 'kads_slider_ajax_remove',
                        id: id
                };
                var x = confirm('Do you really want to delete: ' + title + ' ?');
                if(x){
                    $('.kads-slider-loading').fadeIn();
                    $.ajax({
                        url:ajaxurl,
                        type:"POST",
                        data:data_params,
                        dataType:"json",
                        success: function(response){
                           if (response && response.success)
                            {
                                box_item.remove();
                            }
                            $('.kads-slider-loading').fadeOut();
                        }
                    });
                }
            });
        },
        /**
         * Load event for manager item
         * @returns {undefined}
         */
        loadEvents: function () {
            $('.yesno .button', this.c).on('click', function (event) {
                event.preventDefault();

                var yesno = $(this).closest('.yesno');
                if (yesno.hasClass('selected'))
                    return;

                $(yesno).find('input').prop("checked", true);
                var container = yesno.closest('.kads-slider-control-yesno');

                $('.yesno', container).removeClass('selected');
                yesno.addClass('selected');
            });
            
            $('<button class="button quantity-button quantity-up">+</button><button class="button quantity-button quantity-down">-</button>').insertAfter('.kads-slider-control-number .quantity input',this.c);


            $('.quantity .quantity-up',this.c).click(function () {
                var spinner = $(this).closest('.quantity'),
                        input = spinner.find('input'),
                        max = parseInt(input.attr('max')),
                        newVal = 0;
                var oldValue = parseFloat($(input).val());
                if (isNaN(oldValue))
                {
                    oldValue = 0;
                }
                if (oldValue >= max) {
                    newVal = oldValue;
                } else {
                    newVal = oldValue + 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
                return false;
            });
            $('.quantity .quantity-down',this.c).click(function () {
                var spinner = $(this).closest('.quantity'),
                        input = spinner.find('input'),
                        min = parseInt(input.attr('min')),
                        newVal = 0;
                var oldValue = parseFloat($(input).val());
                if (isNaN(oldValue))
                {
                    oldValue = 0;
                }
                if (oldValue <= min) {
                    newVal = oldValue;
                } else {
                    newVal = oldValue - 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
                return false;
            });
            
            
            var fnscTime = function () {
                clearTimeout(_m.ivtimer);
                _m.el = this;
                if ($(this).hasClass('input-main-change'))
                {
                    _m.ivtimer = setTimeout(_m.fnsmc(), _m.ivtyping);

                } else {
                    _m.ivtimer = setTimeout(_m.fnsc(), _m.ivtyping);
                }

            };
            var evaddl = function (e) {
                if (e.target.className !== 'add-layer')
                {
                    if ($('.add-layer ul').is(":visible")) {
                        $('.add-layer ul').fadeOut();
                    }
                    window.removeEventListener('click', evaddl);
                }
            };

            $('.add-layer', this.g).click(function () {
                $('.add-layer ul').fadeToggle();
                window.addEventListener('click', evaddl);
            });

            $('.color-picker-hex', this.c).wpColorPicker({
                change: function (event, ui) {
                    $(this).kadsSliderChangeVal(ui.color.toCSS());
                }
            });
            
            
            $('.wp-picker-default', this.c).after('<input class="button button-small wp-picker-remove" value="Remove" type="button">');

            $('.wp-picker-remove',this.c).click(function () {
                var warperedit = $(this).closest('.wp-picker-container');
                $('.wp-color-result', warperedit).css('background-color', '#ffffff');
                var input = $('.wp-color-picker', warperedit);
                $(input).kadsSliderChangeVal('');
                if ($(input).hasClass('input-main-change'))
                {
                    _m.gImage.css('background-color', '');
                }
            });
            if ($.isFunction($.fn.SumoSelect))
            {
                $('.select-style', this.g).SumoSelect();
            } else {
                this.loadScript('sumoselect/jquery.sumoselect.min.js', function () {
                    $('.select-style', this.g).SumoSelect();
                });
            }


            this.g.on('mousedown', 'div.box-layer', function () {
                var i = $(this).attr('current');
                _m.selectedbox(i);
            });
            this.g.on('mouseup', 'div.box-layer', function () {
                _m.setoplayer(this);
            });

            this.g.on('click', '.responsive-design .button', function (e) {
                _m.el = this;
                _m.rpdesign();
                e.preventDefault();
            });

            this.loadfnanimate('in', $('#html-animation-in', this.g));
            this.loadfnanimate('out', $('#html-animation-out', this.g));
            this.g.on('change', '.select-style', function () {
                var el = $(this);
                _m.el = el;
                switch (el.attr('data-key')) {
                    case 'animateIn':
                        _m.loadfnanimate('in', el);
                        break;
                    case 'animateOut':
                        _m.loadfnanimate('out', el);
                        break;
                    default:
                        break;
                }
                _m.fnselect();
            });

            this.g.on('click', '.add-actions-popup .popup-inner .close', function () {
                _m.cancelSliderPopup();
            });
            this.g.on('change keyup keydown', 'input.input-change', fnscTime);

            this.g.on('change', 'input.input-change', fnscTime);

            //-----
            this.c.on('click', '.manage-events', function (e) {
                var action = $(this).attr('actions');
                _m.kadsEvents(action, this);
                e.preventDefault();
            });
            this['add-video-layer'] = function (el) {
                this.el = el;
                this.addLayer();
                this.showSliderPopup();
            };
            this['slider-preview'] = function (el) {
                this.fnmPreview();
            };
            this['add-image-layer'] = function (el) {
                this.el = el;
                this.addLayer();
                this.showSliderPopup('Images: ');
            };
            this['add-html-layer'] = function (el) {
                this.el = el;
                this.addLayer();
            };
            this['add-button-layer'] = function (el) {
                this.el = el;
                this.addLayer();
                this.setButtonLayer();
            };
            this['button-popup-action'] = function (el) {
                this.el = el;
                switch ($(el).attr('data-action')) {
                    case 'save':
                        _m.saveEditButton();
                        break;
                        
                    case 'cancel':
                        _m.hidePopupButton();
                        break;
                    default:
                        
                        break;
                }
            };
            
            this['add-overplay'] = function (el) {
                this.el = el;
                this.addOverplay();
            };
            this['layer-overplay-remove'] = function (el) {
                this.el = el;
                this.removeOverplay();
            };
            this['upload-button'] = function (el) {
                this.el = el;
                this.openFrame();
            };
            this['add-style-position'] = function (el) {
                this.el = el;
                this.fnposition();
            };
            this['close-image'] = function (el) {
                this.el = el;
                this.g.fadeOut();
            };
            this['add-style-key'] = function (el) {
                this.el = el;
                this.fnkey();
            };
            this['preview-close'] = function (el) {
                this.el = el;
                this.p.fadeOut();
            };
            this['preview-all-close'] = function (el) {
                $('.slider-preview-all-warper').fadeOut();
                $('#slider-preview-all-warper').html('');
            };
            this['save-image'] = function (el) {
                this.el = el;
                this.saveSettings();
            };
            this['add-image'] = function (el) {
                this.el = el;
                this.resetAll();
                this.g.css('top', $(el).offset().top - 300);
                this.isEdit = 0;
                this.key = '';
                this.g.fadeIn();
                this.scrollEvents();
            };
            this['edit-image'] = function (el) {
                this.resetAll();
                this.lidx = 0;
                this.isEdit = 1;
                this.key = '';
                this.loadData(el);
                this.g.css('top', $(el).offset().top - 300);
                this.g.fadeIn();
                this.scrollEvents();
            };
            this['copy-image'] = function (el) {
                this.resetAll();
                this.lidx = 0;
                this.isEdit = 1;
                this.key = '';
                var item_img = $(el).closest('.box-item');
                this.sever.id = parseInt(this.sever.id) + 1;
                var item_copy =  item_img.clone( true ).attr('data-id',this.sever.id).attr('id','box-images-' + this.sever.id);
                
                item_copy.insertAfter(item_img);
                
            };
            this['remove-image'] = function (el) {
                var r = confirm("Do you really want to delete this picture?");
                if (r === true) {
                    $(el).closest('.box-item').remove();
                }
            };
            this['preview-image'] = function (el) {
                this.el = el;
                this.p.fadeIn();
                this.loadPreview();
            };
            this['layer-edit'] = function (el) {
                if(_m.currentData.type === 'button'){
                    _m.showEditButton();
                }
                else{
                    var warperedit = $(el).closest('.warper-edit'), layerEdit = $(el).closest('.box-layer');
                    $(layerEdit).css({"min-height": "60px"});
                    $(warperedit).addClass('edit-active');
                    $('.actions-group', warperedit).addClass('active');
                }
            };
            this['layer-delete'] = function (el) {
                var layer = $('#box-' + $(el).attr('current'),this.g);
                this.deleteLayer(layer);
            };
            this['layer-save'] = function (el) {
                var warperedit = $(el).closest('.warper-edit'), layerEdit = $(el).closest('.box-layer');
                $('.content-edit', layerEdit).html($('.content-edit-textarea textarea', layerEdit).val());
                $(layerEdit).css({"min-height": ""});
                this.setoplayer(layerEdit);
                $(warperedit).removeClass('edit-active');
                $('.actions-group', warperedit).removeClass('active');
            };
            this['layer-cancel'] = function (el) {
                var warperedit = $(el).closest('.warper-edit'), layerEdit = $(el).closest('.box-layer');
                $(layerEdit).css({"min-height": ""});
                $(warperedit).removeClass('edit-active');
                $('.actions-group', warperedit).removeClass('active');
                this.setoplayer(layerEdit);
            };
            this['layer-copy'] = function (el) {
                this.el = el;
                this.copyLayer($(el).attr('current'));
            };
            this.playing = false;
            this.lptime = 0;
            this['layer-play'] = function () {
                this.playLayer();
            };
            this['layer-pause'] = function () {
                this.pauseLayer();
            };
            $('.controls-data', this.g).each(function () {
                _m.updateControls($(this));
            });
            this.styles = {
                isPreview: false,
                k: '',
                'tags': function (el, v) {
                    el.attr(this.k, v);
                },
                'desktop': function (el, v) {
                    el.attr(this.k, v);
                },
                'laptop': function (el, v) {
                    el.attr(this.k, v);
                },
                'tablet': function (el, v) {
                    el.attr(this.k, v);
                },
                'smartphone': function (el, v) {
                    el.attr(this.k, v);
                },
                'animate': function (el, v) {
                    el.attr(this.k, v);
                    el.addClass(v);
                },
                'layout': function (el, v) {
                    el.attr(this.k, v);
                    el.addClass(v);
                },
                'class': function (el, v) {
                    el.attr(this.k, v);
                    el.addClass(v);
                },
                'html': function (el, v) {
                    if (!this.isPreview) {
                        $('.content-edit', el).html(v);
                        $('textarea', el).val(v);
                    } else {
                        $('.content-inner', el).html(v);
                    }
                },
                'type': function (el, v) {
                    el.attr(this.k, v);
                },
                'link': function (el, v) {
                    el.attr(this.k, v);
                },
                'wait': function (el, v) {
                    el.attr(this.k, v);
                },
                'speed': function (el, v) {
                    el.attr(this.k, v);
                },
                'animateIn': function (el, v) {
                    el.attr(this.k, v);
                },
                'animateInState': function (el, v) {
                    el.attr(this.k, v);
                },
                'animateOut': function (el, v) {
                    el.attr(this.k, v);
                },
                'animateOutState': function (el, v) {
                    el.attr(this.k, v);
                },
                setval: function (el, k, v,t) {
                    this.k = k;
                    if (this[k]) {
                        this[k].call(this, el, v);
                    }
                    else{
                       _m.setLayerCss(k, v);
                    }
                }
            };
        },
        /**
         * Play animaution
         * @returns {undefined}
         */
        playLayer: function () {
            if (this.playing)
            {
                return;
            }
            this.playAnimation.start();
            this.loadcss('animate.css');
            this.playing = true;
            var time = 0;
            var maxtime = this.maxTimeRun + 600;
            var mt = maxtime - 300;
            var framestimes = document.getElementById('kads-slider-frames-times');
            var delayindicator = document.getElementById('kads-slider-timeline-delayindicator');
            this.diLayer = function () {};
            this.lptime = setInterval(function () {
                setTimeout(function () {
                    var x = (time + 20) + 'px';
                    framestimes.style.left = x;
                    delayindicator.style.left = x;
                    framestimes.innerHTML = (time * 10);
                    time += 1;
                    switch (time) {
                        case 1:
                            _m.playAnimation.animateIn();
                            break;
                        case mt:
                            _m.playAnimation.animateOut();
                            break;
                        case maxtime:
                            time = 0;
                            break;
                        default:
                            break;
                    }
                }, 100);
            }, 10);
        },
        /**
         * Stop for Playing
         * @returns {undefined}
         */
        pauseLayer: function () {
            this.playing = false;
            clearInterval(this.lptime);
            this.playAnimation.stop();
            this.diLayer = this.diDefaults;
        },
        /**
         * Play State
         */
        playState: {
            aIn: '',
            aOut: '',
            setData: function (obj) {
                var o = _m.ldefault();
                var data = $.extend(true, {}, o, obj);
                this.aIn = data.animateIn + 'In';
                if (!_m.isu(data.animateInState))
                {
                    this.aIn += data.animateInState;
                }
                this.aOut = data.animateOut + 'Out';
                if (!_m.isu(data.animateOutState))
                {
                    this.aOut += data.animateOutState;
                }
            }
        },
        /**
         * Play Animation
         */
        playAnimation: {
            animateShow: true,
            layers: {
            },
            start: function () {
                var _this = this;
                $.each(_m.listLayer, function (k, layer) {
                    if (_m.item.layers[k])
                    {
                        var obj = _m.item.layers[k];
                        _m.playState.setData(obj);
                        var data = {};
                        data._class = $(layer).attr('class') + ' ';
                        data._style = $(layer).attr('style');
                        data._in = _m.playState.aIn + ' animated';
                        data._out = _m.playState.aOut + ' animated';
                        data._delay = (obj.wait * 10) + 'ms';
                        data._speed = ((obj.speed - 300) / 10) + 'ms';
                        data._layer = layer;
                        data._animateIn = function () {
                            $(this._layer).css(_m.getCss('animation-duration'), this._speed);
                            $(this._layer).css(_m.getCss('animation-delay'), this._delay);
                            $(this._layer).removeClassExcept(this._class + this._in);
                        };
                        data._animateOut = function () {
                            $(this._layer).css(_m.getCss('animation-delay'), '');
                            $(this._layer).removeClassExcept(this._class + this._out);
                        };
                        data._reset = function () {
                            $(this._layer).css(_m.getCss('animation-duration'), '');
                            $(this._layer).css(_m.getCss('animation-delay'), '');
                            $(this._layer).removeClassExcept(this._class.trim());
                        };
                        _this.layers[k] = data;
                        $(layer).css(_m.getCss('animation-delay'), '');
                        $(layer).removeClassExcept(data._class + data._out);
                    }
                });
            },
            /**
             * Stop Animation
             * @returns {undefined}
             */
            stop: function () {
                $.each(this.layers, function () {
                    this._reset();
                });
            },
            /**
             * Run Animation IN
             * @returns {undefined}
             */
            animateIn: function () {
                this.animateShow = false;
                $.each(this.layers, function () {
                    this._animateIn();
                });
            },
            /**
             * Run Animation out
             * @returns {undefined}
             */
            animateOut: function () {
                this.animateShow = true;
                $.each(this.layers, function () {
                    this._animateOut();
                });
            }
        },
        /**
         * Get css fix for Browser
         * @param {type} prop
         * @returns {String}
         */
        getCssFix: function (prop) {
            if (prop in this.div.style)
                return '';
            prop = prop.replace(/^[a-z]/, function (val) {
                return val.toBottomperCase();
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

        },
        /**
         * 
         * Get Css property
         * @param {type} prop
         * @returns {@this;@call;getCssFix}
         */
        getCss: function (prop) {
            return this.cssfix + prop;
        },
        /**
         * Check is css
         * @returns {Boolean}
         */
        checkCss: function () {
            var prop = 'transition';
            if (prop in this.div.style)
                return true;
            prop = prop.replace(/^[a-z]/, function (val) {
                return val.toBottomperCase();
            });
            var len = this.vendors.length;
            while (len--) {
                if (this.vendors[len] + prop in this.div.style) {
                    return true;
                }
            }
            return false;
        },
        /**
         * Load Animations
         * @param {type} t
         * @param {type} el
         * @returns {undefined}
         */
        loadfnanimate: function (t, el) {
            var animate = el.val();
            var animatefrom = $('.item-row.html-animation-' + t + '', this.g);
            var animatestate = $('#html-animation-' + t + '-state', animatefrom);
            animatefrom.hide();
            animatestate.html('');
            var op = this.animations[animate];
            if (op)
            {
                var option = '';
                if(this.isu(this.optionsanimate[animate]))
                {
                    $.each(op, function () {
                        option += '<option value="' + this + '">' + this.replace(/([A-Z])/g, ' $1').trim() + '</option>';
                    });
                    animatestate.html(option);
                    this.optionsanimate[animate] = option;
                }
                else{
                    option = this.optionsanimate[animate];
                    animatestate.html(option);
                }
            }
            animatestate[0].sumo.reload();
            setTimeout(function () {
                animatestate.trigger('change');
            }, 200);
            animatefrom.show();
        },
        /**
         * Update Controls
         * @param {type} el
         * @returns {undefined}
         */
        updateControls: function (el) {
            var control = {};
            control.key = el.attr('data-key');
            control.tags = el.attr('data-actions');
            control.el = el;
            switch (control.tags) {
                case 'color':
                    control.val = function (v) {
                        this.el.wpColorPicker('color', v);
                    };
                    break;
                case 'text':
                    control.val = function (v) {
                        this.el.val(v);
                    };
                    break;
                case 'select':
                    control.val = function (v) {
                        this.el[0].sumo.selectItem(v);
                    };
                    break;
                case 'button':
                    control.val = function (v) {
                        var eln = $('.responsive-design.no', this.el);
                        var ely = $('.responsive-design.yes', this.el);
                        if (v === '') {
                            eln.find('input').prop("checked", false);
                            ely.find('input').prop("checked", true);
                            eln.removeClass('selected');
                            ely.addClass('selected');
                            return;
                        } else {
                            ely.find('input').prop("checked", false);
                            eln.find('input').prop("checked", true);
                            eln.addClass('selected');
                            ely.removeClass('selected');
                        }
                    };
                    break;

                default:
                    control.val = function () {};
                    break;
            }
            this.controls[control.key] = control;
        },
        /**
         * Set Controls value
         * @param {type} k
         * @param {type} v
         * @returns {undefined}
         */
        setctrlval: function (k, v) {
            if (this.isu(k) || this.isu(v))
            {
                v = '';
                $.each(this.controls, function () {
                    var el = this;
                    if (el.tags)
                    {
                        el.val(v);
                    }
                });
                return;
            }
            if (this.controls[k])
            {
                var el = this.controls[k];
                if (el.tags)
                {
                    el.val(v);
                }
            }
        },
        /**
         * Add html template of item
         * @param {type} data
         * @returns {undefined}
         */
        htmlAddItem: function (data) {
            this.sever.id = parseInt(this.sever.id) + 1;
            var i = this.sever.id;
            var html = this.format(this.iaddtpl, i);
            $('.box-item.box-item-add', this.c).before(html);
            this.sever.boxSelected = $('#box-images-' + i, this.c);
            this.htmlEditItem(data);
        },
        /**
         * String format
         * @param {type} format
         * @returns {unresolved}
         */
        format: function (format) {
            var args = Array.prototype.slice.call(arguments, 1);
            return format.replace(/{(\d+)}/g, function (match, number) {
                return typeof args[number] !== 'undefined' ? args[number] : match;
            });
        },
        /**
         * Check if event
         * @param {type} event
         * @param {type} el
         * @returns {undefined}
         */
        kadsEvents: function (event, el) {
            if (!this[event]) {
                this.logs(event + ' => This is not a function');
                return ;
            }
            return this[event].call(this, el);
        },
        /**
         * Set Options Layer
         * @param {type} layer
         * @returns {undefined}
         */
        setoplayer: function (layer) {

            $(layer).each(function () {
                _m.addLayerData('top', this.offsetTop);
                _m.addLayerData('left', this.offsetLeft);
                _m.addLayerData('width', this.offsetWidth);
                _m.addLayerData('height', this.offsetHeight);
                _m.addLayerData('html', $('.content-edit', layer).html());
            });
        },
        /**
         * Set layer button
         * @returns {undefined}
         */
        setButtonLayer : function () {
            var item = {type: 'button',layout: '', link: '', html: 'Simple Button'};
            $('.content-edit', this.layerSelected).html(item.html);
            this.setValueLayer(item);
            $(this.layerSelected, this.g).addClass('kads-m-button');
            $(this.layerSelected, this.g).css('height', '138px');
            $(this.layerSelected, this.g).css('height', '33.4px');
        },
        /**
         * Show slider Popup
         * @param {type} text
         * @returns {undefined}
         */
        showSliderPopup: function (text) {
            if (!this.isu(text))
            {
                this.pl.html(text);
                this.pb.attr('data-type', 'image');
            }
            this.pm.css('top', this.g.offset().top);
            this.pmBg.fadeIn();
            this.pm.fadeIn();
        },
        /**
         * Hide Slider Popup
         * @returns {undefined}
         */
        hideSliderPopup: function () {
            this.pm.fadeOut();
            this.pmBg.fadeOut();
        },
        /**
         * Show slider Popup
         * @param {type} text
         * @returns {undefined}
         */
        saveEditButton: function () {
            var formdata = $('#button-form-popup',this.pmbutton);
            var data = formdata.serializeArray();
            
            var currentdata = this.currentData;
            var oldLayout = currentdata.layout;
            $.each(data,function (){
                _m.addLayerData(this.name, this.value);
            });
            if(typeof currentdata.layout !== 'undefined' && currentdata.layout !== ''){
                $(this.layerSelected, this.g).addClass(currentdata.layout);
            }
            else{
                $(this.layerSelected, this.g).removeClass(oldLayout);
            }
            this.hidePopupButton();
            formdata.trigger("reset");
        },
        showEditButton: function () {
            var data = this.currentData;
            if(typeof data.link !== 'undefined'){
                $('.input-link',this.pmbutton).val(data.link);
            }
            if(typeof data.html !== 'undefined'){
                $('.input-html',this.pmbutton).val(data.html);
            }
            if(typeof data.layout !== 'undefined'){
                if(data.layout === ''){
                    $('.bt-style-none',this.pmbutton).prop("checked", true);
                }
                else{
                    $('.bt-style-' + data.layout,this.pmbutton).prop("checked", true);
                }
            }
            
            _m.showPopupButton();
        },
        /**
         * Show slider Popup
         * @param {type} text
         * @returns {undefined}
         */
        showPopupButton: function () {
            this.pmbutton.css('top', this.g.offset().top);
            this.pmBg.fadeIn();
            this.pmbutton.fadeIn();
        },
        /**
         * Hide Slider Popup
         * @returns {undefined}
         */
        hidePopupButton: function () {
            this.pmbutton.fadeOut();
            this.pmBg.fadeOut();
        },
        /**
         * Cancel slider Popup
         * @returns {undefined}
         */
        cancelSliderPopup: function () {
            this.pm.fadeOut();
            this.pmBg.fadeOut();
            $('.hd-delete-html', this.layerSelected).trigger('click');
        },
        /**
         * HTML template for box-layer
         * @param {type} i
         * @param {type} html
         * @returns {String}
         */
        htmlpl: function (i, html) {
            return  '<div id="box-' + i + '" current="' + i + '" class="box-layer">' +
                    '<div class="content-inner">' + html + '</div>' +
                    '</div>';
        },
        /**
         * Save Setting for manager
         * @returns {undefined}
         */
        saveSettings: function () {
            var sJson = JSON.stringify(this.item);
            var data = encodeURI(sJson);
            if (this.isEdit)
            {
                this.htmlEditItem(data);
            } else {
                this.htmlAddItem(data);
            }
            this.g.fadeOut();
        },
        /**
         * Set html edit layer
         * @param {type} data
         * @returns {undefined}
         */
        htmlEditItem: function (data) {
            if (!this.sever.boxSelected)
            {
                return;
            }

            var item = {type: 'html'};
            $('.box-layer-list', this.sever.boxSelected).html('');
            $.each(this.item.layers, function () {
                var data = $.extend(true, {}, item, this);
                var html = '';
                switch (data.type) {
                    case 'html':
                        html += '<span class="layer">';
                        html += data.html;
                        html += '</span>';
                        break;
                    case 'image':
                        html += '<span class="layer">';
                        html += '<span class="dashicons dashicons-format-image"></span>';
                        html += '</span>';
                        break;
                    case 'button':
                        html += '<span class="layer">';
                        html += '<span class="dashicons dashicons-share"></span>';
                        html += '</span>';
                        break;
                    default :
                        html += '<span class="layer">';
                        html += '<span class="dashicons dashicons-format-video"></span>';
                        html += '</span>';
                        break;
                }
                $('.box-layer-list', _m.sever.boxSelected).append(html);
            });
            if (this.item.link)
            {
                $('.bg', this.sever.boxSelected).css('background-image', 'url(' + this.item.link + ')');
            }
            $('.bg', this.sever.boxSelected).css('background-color', this.item.color);
            $('.item', this.sever.boxSelected).val(data);
        },
        /**
         * Load preview item
         * @returns {undefined}
         */
        loadPreview: function () {
            var warperedit = $(this.el).closest('.box-item');

            var data = decodeURI($('.item', warperedit).val());
            var item = JSON.parse(data);
            this.viplay = 1;
            if (item)
            {
                $('.box-layer', this.p).remove();
                var o = {type: 'html', link: ''};
                $.each(item.layers, function (k, obj) {
                    var data = $.extend(true, {}, o, obj);
                    var layer = _m.addPreviewLayer(data);
                    _m.layerPreviewCurrent = layer;
                    switch (data.type) {
                        case 'html':
                        case 'image':
                        case 'button':
                            break;
                        default :
                            _m.setelmedia(data.link, data.type, 'layer-preview');
                            break;
                    }
                });
                var bg = $('.bg', this.p);
                bg.html('');
                this.setelmedia(item.link, item.type, 'main-preview');
                this.setelmedia(item.thumb, 'image', 'thumb-preview');
                if (item.color) {
                    bg.css('background-color', item.color);
                }
            }
            this.viplay = 0;
        },
        /**
         * Add preview item
         * @param {type} data
         * @returns {window.$|$}
         */
        addPreviewLayer: function (data) {
            $('.box-gird-preview', this.p).append(this.htmlpl(this.lidx, 'Future collections'));
            var layer = $('#box-' + this.lidx, this.p);
            this.styles.isPreview = true;
            $.each(data, function (key, value) {
                _m.styles.setval(layer, key, value);

            });
            this.styles.isPreview = false;
            this.lidx = this.lidx + 1;
            return layer;
        },
        /**
         * Set i default
         * @returns {kads-slider-manager_L7.$.kadsManager.idefault.kads-slider-managerAnonym$9}
         */
        idefault: function () {
            return {
                link: '', 
                color: '', 
                html: '', 
                animate: '', 
                thumb: '', 
                type: 'image', 
                layers: {}
            };
        },
        /**
         * Set layer default
         * @returns {kads-slider-manager_L7.$.kadsManager.ldefault.kads-slider-managerAnonym$10}
         */
        ldefault: function () {
            return {el: 0,type: 'html', link: '', wait: 0, speed: 3300, animateIn: 'fade', animateOut: 'fade'};
        },
        /**
         * Load data
         * @param {type} el
         * @returns {undefined}
         */
        loadData: function (el) {
            var warperedit = $(el).closest('.box-item');
            var currentid = $(warperedit).data('id');
            var data = decodeURI($('.item', warperedit).val());
            var item = JSON.parse(data);
            this.sever.boxSelected = warperedit;
            if (item) {
                this.listLayer = {};
                this.currentData = {};
                this.item = this.idefault();
                $('.box-layer').remove();
                this.key = currentid;
                this.isCreate = 0;
                var lidx = 0;
                var o = this.ldefault();
                $.each(item.layers, function (k, obj) {
                    var data = $.extend(true, {}, o, obj);
                    _m.addLayer();
                    $.each(data, function (a, b) {
                        switch (a){
                            case 'class':
                                break;
                            case 'top':
                                if(_m.isFullHeight){
                                    _m.styles.setval(_m.layerSelected, a, b);
                                }
                                else{
                                    if(b > 500){
                                        b = 400;
                                    }
                                     _m.styles.setval(_m.layerSelected, a, b);
                                }
                                break;
                            default :
                                _m.styles.setval(_m.layerSelected, a, b);
                                break;
                        }
                    });

                    switch (data.type) {
                        case 'html':
                        case 'image':
                            break;
                        case 'button':
                            break;
                        default :
                            _m.setelmedia(data.link, data.type, 'layer');
                            break;
                    }
                    if(data.type === 'button'){
                        $(_m.layerSelected, _m.c).addClass('kads-m-button');
                    }
                    _m.currentData = data;
                    _m.setDataLayer(lidx, _m.currentData);
                    _m.fnlayertldata(data);
                    lidx = lidx + 1;
                });
                this.gImage.html('');
                
                
                this.item.link = item.link;
                this.item.color = item.color;
                this.item.type = item.type;
                this.isCreate = 1;
                this.setelmedia(item.link, item.type, 'main');
                this.setelmedia(item.thumb, 'image', 'thumb');
                if (item.color) {
                    this.gImage.css('background-color', item.color);
                }
                setTimeout(function (){
                   $.each(_m.currentData, function (k, v) {
                        _m.setctrlval(k, v);
                    });
                },100);
                
                
            }
        },
        /**
         * Add new layer
         * @returns {undefined}
         */
        addLayer: function () {
            this.resetValue();
            $('.box-gird', this.g).append(this.format(this.ltpl, this.lidx, 'Future collections'));
            this.ll.append(this.format(this.htmlltllabel, this.lidx));
            this.ltl.append(this.format(this.htmlltl, this.lidx));

            var o = this.ldefault();
            this.currentData = o;
            this.setDataLayer(this.lidx, o);
            this.selectedbox(this.lidx);

            this.fnlayertldata(o);
            this.tlEvents();

            this.actionsLayer();
            var layer = this.layerSelected;
            this.setListLayer(this.lidx, layer);
            if (this.isCreate)
            {
                _m.setoplayer(layer);
                _m.addLayerData('type', 'html');
            }
            setTimeout(function () {
                $('#html-animation-out', _m.g).trigger('change');
            }, 10);
            setTimeout(function () {
                $('#html-animation-in', _m.g).trigger('change');
            }, 20);

            this.lidx = this.lidx + 1;
        },
        /**
         * Add layer overplay
         * @returns {undefined}
         */
        addOverplay:function (){
            this.resetValue();
            $('.box-gird', this.g).append('<div class="kads-box-overplay">');
            this.ltlOverPlay.fadeIn();
            this.item.overplay = true;
        },
        /**
         * Add layer overplay
         * @returns {undefined}
         */
        removeOverplay:function (){
            this.resetValue();
            $('.box-gird .kads-box-overplay', this.g).append('<div class="kads-box-overplay">').remove();
            this.ltlOverPlay.fadeOut();
            this.item.overplay = false;
        },
        /**
         * Add actions layer
         * @returns {undefined}
         */
        actionsLayer: function () {
            var layer = this.layerSelected;
            layer.draggable({containment: "parent", drag: function (event, ui) {
                    var snapTolerance = $(this).draggable('option', 'snapTolerance');
                    var topRemainder = ui.position.top % _m.gSize;
                    var leftRemainder = ui.position.left % _m.gSize;

                    if (topRemainder <= snapTolerance) {
                        ui.position.top = ui.position.top - topRemainder;
                    }
                    if (leftRemainder <= snapTolerance) {
                        ui.position.left = ui.position.left - leftRemainder;
                    }
                },
                stop: function () {
                    _m.setoplayer(this);
                }
            });
            layer.resizable({handles: this.handles, maxHeight: 800, stop: function (event, ui) {
                    _m.setoplayer(this);
                    $(this).trigger("dragstop");
                }});
        },
        /**
         * Delete layer
         * @param {type} layer
         * @returns {undefined}
         */
        deleteLayer: function (layer) {
            var index = layer.attr('current');
            layer.remove();
            $('#timelinelable-' + index,this.ll).remove();
            $('#timeline-' + index,this.ltl).remove();
            this.removelist(index);
            this.setLastAsCurrent();

        },
        /**
         * Remove item from list layer current edit
         * @param {type} k
         * @returns {undefined}
         */
        removelist: function (k) {
            this.setListLayer(k);
            this.setDataLayer(k);
        },
        /**
         * Set layer as current edit
         * @returns {undefined}
         */
        setLastAsCurrent: function () {
            var layer = this.getLastLayer();
            if (layer)
            {
                var i = $(layer).attr('current');
                this.selectedbox(i);
            }
        },
        /**
         * Get Last default layer
         * @returns {@this;}
         */
        getLastLayer: function () {
            var last;
            $.each(this.listLayer, function () {
                last = this;
            });
            return last;
        },
        /**
         * Reset all layer setting before.
         * @returns {undefined}
         */
        resetAll: function () {
            this.resetValue();
            this.im.val('');
            this.ithumb.val('');
            $('.box-layer', this.g).remove();
            this.gImage.html('');
            $('.box-gird-bg', this.g).removeClass('size-20').removeClass('size-10');
            this.gImage.css('background-color', '');
            this.gImage.css('background-image', '');
            $.each(this.listLayer, function (layer) {
                $(layer).remove();
            });
            this.listLayer = {};
            this.currentData = {};
            this.item = this.idefault();
            $(this.ltl).html('');
            $(this.ll).html('');
        },
        /**
         * Reset value layer
         * @returns {undefined}
         */
        resetValue: function () {
            this.isReset = 1;
            this.setctrlval('');
            this.isReset = 0;
        },
        /**
         * Check is Reset
         * @returns {Number}
         */
        checkac: function () {
            if (this.isReset) {
                return 1;
            }
            return 0;
        },
        /**
         * Control .responsive-design layer
         * @returns {undefined}
         */
        rpdesign: function () {
            if (this.checkac())
                return;
            var el = $(this.el);
            var control = el.closest('.responsive-design');
            if (control.hasClass('selected'))
                return;
            $(control).find('input').prop("checked", true);
            var container = control.closest('.hd-control-yesno');

            $('.selection .responsive-design', container).removeClass('selected');
            control.addClass('selected');
            var k = control.data('key');
            var v = control.data('value');
            if (v === '0')
            {
                $(this.layerSelected, this.g).attr(k, 1);
                this.addLayerData(k, 1);
            } else {
                $(this.layerSelected, this.g).removeAttr(k);
                this.addLayerData(k);
            }
        },
        /**
         * Controls for select
         * @returns {undefined}
         */
        fnselect: function () {
            if (this.checkac())
                return;
            var el = $(this.el);
            var k = el.data('key');
            var v = el.val();
            switch (k) {
                case 'tags':
                    if (!this.layerSelected) {
                        return;
                    }
                    $(this.layerSelected, this.g).attr('tags', v);
                    this.addLayerData('tags', v);
                    var current_text = $('.content-edit', this.layerSelected).html();
                    $('.content-edit', this.layerSelected).replaceWith($('<' + v + ' class="content-edit">' + current_text + '</' + v + '>'));
                    break;
                case 'mainanimate':
                    this.item.animate = v;
                    break;
                case 'gird':
                    $('.box-gird-bg', this.g).removeClass('size-20').removeClass('size-10');
                    $('.box-gird-bg', this.g).addClass('size-' + v);
                    break;
                default :
                    if (!this.layerSelected) {
                        return;
                    }
                    if ((v === null || v.isEmpty()))
                    {
                        $(this.layerSelected, this.g).removeAttr(k);
                        this.addLayerData(k);
                    } else {
                        $(this.layerSelected, this.g).attr(k, v);
                        this.addLayerData(k, v);
                    }
                    break;
            }
        },
        /**
         * Controls positions
         * @returns {undefined}
         */
        fnposition: function () {
            if (this.checkac())
                return;
            var el = $(this.el);
            var k = el.data('key');
            var left = 0;
            var witem = $('.box-gird', this.g).outerWidth();
            switch (k) {
                case 'center':
                    left = (witem / 2) - ($(this.layerSelected, this.g).width() / 2);
                    break;
                case 'right':
                    left = witem - $(this.layerSelected, this.g).width();
                    break;
                default :
                    left = 0;
                    break;
            }
            if (this.layerSelected)
            {
                this.setLayerCss('left',left);
                this.setoplayer(this.layerSelected);
            }
        },
        /**
         * Set value layer
         * @returns {undefined}
         */
        fnsc: function () {
            if (this.checkac())
                return;
            var el = $(this.el);
            var k = el.data('key'), v = el.val(), a = el.data('att');
            if (a === 'class')
            {
                if (k && this.layerSelected)
                {
                    $(this.layerSelected, this.g).addClass(v);
                    $(this.layerSelected, this.g).attr('custom-class', v);
                    this.addLayerData('class', v);
                }
            } else {
                if (k && this.layerSelected)
                {
                    this.setLayerCss(k,v);
                    this.addLayerData(k, v);
                }
            }
        },
        /**
         * set Value main item
         * @returns {undefined}
         */
        fnsmc: function () {
            if (this.checkac())
                return;
            var el = $(this.el);
            var k = el.data('key'), v = el.val();
            if (k && v)
            {
                switch (k) {
                    case 'main-images':
                        this.setelmedia(v, '', 'main');
                        break;
                    case 'color':
                        this.gImage.css('background-color', v);
                        this.item[k] = v;
                        break;
                    case 'thumb':
                        this.setelmedia(v, '', 'thumb');
                        break;
                    default:

                        break;
                }

            }
        },
        /**
         * Check is button layer css
         * 
         * @param {type} k
         * @returns {Boolean}
         */
        isButtonAceptKey: function (k){
            var ischeck = false;
            if(this.currentData.type === 'button'){
                switch (k) {
                    case 'width':
                    case 'height':
                    case 'color':
                    case 'font-weight':
                    case 'font-style':
                    case 'text-decoration':
                    case 'background-color':
                        ischeck = true;
                        break;

                    default:
                        break;
                }
            }
            
            return ischeck;
        },
        setLayerCss : function (k,v){
            $(this.layerSelected, this.g).css(k, v);
        },
        /**
         * Set value for controls with key
         * @returns {undefined}
         */
        fnkey: function () {
            if (this.checkac())
                return;
            var el = $(this.el);
            var k = el.data('key');
            var v = el.data('value');
            if (k && this.layerSelected)
            {
                this.setLayerCss(k,v);
                this.addLayerData(k, v);
            }
        },
        /**
         * Add layer data
         * @param {type} k
         * @param {type} v
         * @returns {undefined}
         */
        addLayerData: function (k, v) {
            if (this.checkac())
                return;
            if (this.isu(v))
            {
                delete this.currentData[k];
                this.saveCurrent();
                return;
            }
            if (this.isu(this.currentData))
            {
                this.currentData = this.ldefault();
            }
            this.currentData[k] = v;
            this.saveCurrent();
        },
        /**
         * Save current item
         * @returns {undefined}
         */
        saveCurrent: function () {
            this.setDataLayer(this.current, this.currentData);
        },
        /**
         * Set data layer
         * @param {type} n
         * @param {type} v
         * @returns {undefined}
         */
        setDataLayer: function (n, v) {
            var k = this.getLayerKey(n);
            if (this.isu(v))
            {
                delete this.item.layers[k];
                return;
            }
            this.item.layers[k] = v;
        },
        /**
         * Get layer key
         * @param {type} n
         * @returns {String}
         */
        getLayerKey: function (n) {
            var k = 'layer_' + n;
            return k;
        },
        /**
         * Get layer data
         * @param {type} n
         * @returns {@var;nItem|Boolean}
         */
        getDataLayer: function (n) {
            var k = this.getLayerKey(n);
            
            if(this.item.layers[k])
            {
                return this.item.layers[k];
            }
            return false;
        },
        /**
         * Set list layer
         * @param {type} n
         * @param {type} v
         * @returns {undefined}
         */
        setListLayer: function (n, v) {
            var k = this.getLayerKey(n);
            if (this.isu(v))
            {
                delete this.listLayer[k];
                return;
            }
            this.listLayer[k] = v;
        },
        /**
         * Get list layer
         * @param {type} n
         * @returns {kads-slider-manager_L7.$.kadsManager.listLayer|Boolean}
         */
        getListLayer: function (n) {
            var k = this.getLayerKey(n);
            
            if(this.listLayer[k])
            {
                return this.listLayer[k];
            }
            return false;
        },
        /**
         * Stop Player for video
         * @returns {undefined}
         */
        pausePlayer: function () {
            this.player && this.player.pause();
        },
        /**
         * Cleanup Player for video item
         * @returns {undefined}
         */
        cleanupPlayer: function () {
            this.player && wp.media.mixin.removePlayer(this.player);
        },
        /**
         * Open the media modal.
         */
        openFrame: function (event) {
            var el = $(this.el);
            this.mediaAction = el.data('action');
            this.type = el.data('type');
            if (!this.type)
            {
                this.type = 'image';
            }
            if (!this.frame[this.type]) {
                this.initFrame(this.type);
            }
            this.frame[this.type].open();
        },
        /**
         * Create a media modal select frame, and store it so the instance can be reused when needed.
         */
        initFrame: function () {

            this.frame[this.type] = wp.media({
                button: {
                    text: 'Select'
                },
                states: [
                    new wp.media.controller.Library({
                        title: 'Choose image or Videos',
                        library: wp.media.query({type: this.type}),
                        multiple: false,
                        date: false
                    })
                ]
            });

            // When a file is selected, run a callback.
            this.frame[this.type].on('select', function () {
                _m.select();
            });
        },
        /**
         * Callback handler for when an attachment is selected in the media modal.
         * Gets the selected image information, and sets it within the control.
         */
        select: function () {
            // Get the attachment from the modal frame.
            var attachment = this.frame[this.type].state().get('selection').first().toJSON();
            this.setelmedia(attachment.url, attachment.mime, this.mediaAction);
        },
        /**
         * Set valuve layer
         * @param {type} item
         * @returns {undefined}
         */
        setValueLayer: function (item) {
            $('.content-edit', this.layerSelected).html(item.html);
            $('textarea', this.layerSelected).val(item.html);
            this.addLayerData('html', item.html);
            this.addLayerData('type', item.type);
            this.addLayerData('link', item.link);
            this.setLayerCss('height', 'auto');
            $(this.layerSelected, this.g).trigger("dragstop");
        },
        /**
         * Set media layer
         * @param {type} url
         * @param {type} mime
         * @param {type} type
         * @returns {undefined}
         */
        setelmedia: function (url, mime, type) {
            if (this.checkac())
                return;
            var el = null;
            var item = {type: '', link: '', html: ''};
            var rmime = mime.split('/')[0];
            if (this.isu(rmime))
            {
                mime = this.getMime(url);
                switch (mime) {
                    case 'image':
                        item.type = 'image';
                        item.link = url;
                        item.html = '<img src="' + url + '">';
                        break;
                    case 'audio/mpeg':
                    case 'audio/wav':
                    case 'video/mp4':
                    case 'video/webm':
                    case 'video/ogg':
                        item.type = mime;
                        item.link = url;
                        item.html = '<video width="100%" height="100%" style="max-width:100%;" preload="none"><source src="' + url + '" type="' + mime + '"></video>';
                        break;
                    default:
                        item = this.setUrlVideo(item, url, mime);
                        break;
                }

            } else {
                if (rmime === 'image')
                {
                    item.type = 'image';
                    item.link = url;
                    item.html = '<img src="' + url + '">';
                } else {
                    item.type = mime;
                    item.link = url;
                    item.html = '<video width="100%" height="100%" style="max-width:100%;" preload="none"><source src="' + url + '" type="' + mime + '"></video>';
                }

            }
            switch (type) {
                case 'main':
                    el = this.gImage;
                    if (item.type === 'image')
                    {
                        item.html = '';
                        el.css('background-image', 'url(' + url + ')');
                    }
                    el.html(item.html);
                    this.item.link = item.link;
                    this.item.type = item.type;
                    this.item.html = item.html;
                    this.im.val(item.link);
                    break;
                case 'main-preview':
                    el = $('.bg', this.p);
                    el.css('background-image', '');
                    if (item.type === 'image')
                    {
                        item.html = '';
                        el.css('background-image', 'url(' + url + ')');
                    }
                    el.html(item.html);
                    break;
                case 'thumb':
                    item.type = '';
                    item.html = '';
                    this.item.thumb = item.link;
                    this.ithumb.val(item.link);
                    break;
                case 'thumb-preview':
                    el = $('.thumbs', this.p);
                    item.type = '';
                    item.html = '';
                    if (el)
                    {
                        el.css('background-image', 'url(' + url + ')');
                    }
                    break;
                case 'layer':
                    el = $('.content-edit', this.layerSelected);
                    el.html(item.html);
                    this.setValueLayer(item);
                    this.hideSliderPopup();
                    break;
                case 'layer-preview':
                    el = $('.content-inner', this.layerPreviewCurrent);
                    el.html(item.html);
                    break;
                default:
                    return;
            }
            var mejsSettings = {
                loop: false,
                alwaysShowControls: false,
                startVolume: 0,
                features: []
            };
            if (el && item.type !== '' && item.type !== 'image')
            {
                var node = el.find('audio, video').get(0);
                this.player = new MediaElementPlayer(node, mejsSettings);
                this.player.play();
                if (!this.viplay)
                {
                    this.pausePlayer();
                }
            }
        },
        /**
         * Set url of video
         * @param {type} item
         * @param {type} url
         * @param {type} mime
         * @returns {unresolved}
         */
        setUrlVideo: function (item, url, mime) {
            var urlPatterns = [
                {regex: /youtu\.be\/([\w\-.]+)/, type: 'iframe', w: 560, h: 314, url: '//www.youtube.com/embed/$1', allowFullscreen: true},
                {regex: /youtube\.com(.+)v=([^&]+)/, type: 'iframe', w: 560, h: 314, url: '//www.youtube.com/embed/$2', allowFullscreen: true},
                {regex: /youtube.com\/embed\/([a-z0-9\-_]+(?:\?.+)?)/i, type: 'iframe', w: 560, h: 314, url: '//www.youtube.com/embed/$1', allowFullscreen: true},
                {regex: /vimeo\.com\/([0-9]+)/, type: 'iframe', w: 425, h: 350, url: '//player.vimeo.com/video/$1?title=0&byline=0&portrait=0&color=8dc7dc', allowfullscreen: true},
                {regex: /vimeo\.com\/(.*)\/([0-9]+)/, type: "iframe", w: 425, h: 350, url: "//player.vimeo.com/video/$2?title=0&amp;byline=0", allowfullscreen: true},
                {regex: /maps\.google\.([a-z]{2,3})\/maps\/(.+)msid=(.+)/, type: 'iframe', w: 425, h: 350, url: '//maps.google.com/maps/ms?msid=$2&output=embed"', allowFullscreen: false},
                {regex: /dailymotion\.com\/video\/([^_]+)/, type: 'iframe', w: 480, h: 270, url: '//www.dailymotion.com/embed/video/$1', allowFullscreen: true}
            ];
            var html = '', data = {};
            switch (mime) {
                case 'application/x-shockwave-flash':
                    html = '<object data="' + url + '" width="100%" type="application/x-shockwave-flash">';
                    html += '</object>';
                    item.type = 'flash';
                    item.link = data.url;
                    break;
                default:
                    $.each(urlPatterns, function () {
                        var match, i, link;
                        if ((match = this.regex.exec(this.url))) {
                            link = this.url;

                            for (i = 0; match[i]; i++) {
                                link = this.url.replace('$' + i, function () {
                                    return match[i];
                                });
                            }
                            data.url = link;
                            data.type = this.type;
                            data.allowFullscreen = this.allowFullscreen;
                            data.width = this.w;
                            data.height = this.h;
                        }
                    });
                    var allowFullscreen = data.allowFullscreen ? ' allowFullscreen="1"' : '';
                    html = '<iframe src="' + data.url + '" width="' + data.width + '" height="' + data.height + '"' + allowFullscreen + '></iframe>';
                    item.type = 'iframe';
                    item.link = data.url;
                    break;
            }
            item.html = html;
            return item;
        },
        /**
         * Extract file extension from URL.
         * @param {String} url
         * @returns {String} File extension or empty string if no extension is present.
         */
        getMime: function (url) {
            url = url.toLowerCase();
            url = url.substr(1 + url.lastIndexOf("/"));
            url = url.split('?')[0];
            url = url.split('#')[0];
            url = url.split('.').pop();
            switch (url) {
                case 'mp3':
                    return 'audio/mpeg';
                case 'wav':
                    return 'audio/wav';
                case 'mp4':
                    return 'video/mp4';
                case 'webm':
                    return 'video/webm';
                case 'ogg':
                    return 'video/ogg';
                case 'swf':
                    return 'application/x-shockwave-flash';
                case 'jpg':
                case 'gif':
                case 'bmp':
                case 'png':
                case 'jpeg':
                    return 'image';
                default:
                    return '';
            }
        },
        /**
         * kads-slider-frames-rows default 
         * @param {type} el
         * @param {type} e
         * @param {type} layerwarpx
         * @returns {undefined}
         */
        diDefaults: function (el, e, layerwarpx) {
            var f = $('.kads-slider-frames-rows', el);
            var l = f[0].offsetLeft * -1;
            var x = e.pageX - layerwarpx + l;
            this.framestimes.css('left', x);
            var timesScorll = Math.round(x - 20);
            if (timesScorll >= 0)
            {
                this.timesScorll = timesScorll * 10;
                this.framestimes.html(this.timesScorll);
                this.delayindicator.css('left', x);
            }
        },
        diLayer: function () {},
        /**
         * Scroll events timeline
         * @returns {undefined}
         */
        scrollEvents: function () {
            this.timesScorll = 0;
            this.scrollframes = $('.kads-slider-layer-frames', this.g);
            this.scroll = $('.kads-slider-frames-rows', this.scrollframes);
            this.scrolldiffX = 0;
            this.scrollMW = 0;
            this.scrollMoving = function (e) {};
            this.scrollMW = parseInt(this.scroll.width());
            this.scrollw = this.scrollframes[0].offsetWidth;
            this.delayindicator = $('.kads-slider-timeline-delayindicator', this.scrollframes);
            this.framestimes = $('.kads-slider-frames-times', this.scrollframes);
            this.diLayer = this.diDefaults;
            this.scrollSkip = (this.scrollMW - this.scrollw) / this.scrollw;


            _m.scrollMove = function (xpos) {
                var t = xpos * _m.scrollSkip * -1,
                        y = _m.scrollMW - (xpos * _m.scrollSkip),
                        z = _m.scrollw - xpos + 2;
                if (z >= _m.scrollSkip)
                {
                    $(_m.scroll).css('left', xpos);
                }
                if (y >= _m.scrollw)
                {
                    _m.scroll.css('left', t);
                }
            };
            $('.kads-slider-scroll-timeline', this.scrollframes).css('width', (this.scrollw / this.scrollSkip / 5));
            $('.kads-slider-scroll-timeline', this.scrollframes).draggable({containment: "parent", drag: function (event, ui) {
                    var snapTolerance = $(this).draggable('option', 'snapTolerance');
                    var leftRemainder = ui.position.left % 1;
                    if (leftRemainder <= snapTolerance) {
                        ui.position.left = ui.position.left - leftRemainder;
                    }
                    ui.position.top = 0;
                    _m.scrollMove(this.offsetLeft);
                }
            });
            var layerwarpx = $('.kads-slider-layer-warp', this.g).offset().left;
            $('.kads-slider-layer-warp', this.g).mousemove(function (e) {
                _m.diLayer(this, e, layerwarpx);
            });

            this.tlEvents();
        },
        /**
         * Timeline events
         * @returns {undefined}
         */
        tlEvents: function () {
            var range = $('.kads-slider-range-show', this.layerrows);

            var setRangeDelay = function (obj) {
                var w = obj.offsetWidth,
                        l = obj.offsetLeft;
                _m.addLayerData('wait', l);
                _m.addLayerData('speed', (w * 100));
                _m.currentRangeValue.html((w - _m.refixpx) * 10);
                _m.currentRangeDelay.css('width', (l + w - 10));
            };

            $('.kads-slider-range-show,.kads-slider-range kads-slider-range-delay,.kads-slider-timeline-range', this.layerrows).mousedown(function () {
                var t = $(this).closest('.kads-slider-layer-item-timeline');
                if ($(this).attr('noat') !== '1')
                {
                    var id = t.data('id');
                    _m.selectedbox(id);
                }

            });
            $('.kads-slider-layer-label', this.ll).mousedown(function () {
                var t = $(this).closest('.kads-slider-layer-item-label');
                var id = t.data('id');
                _m.selectedbox(id);
            });

            range.draggable({containment: "parent", drag: function (event, ui) {
                    var snapTolerance = $(this).draggable('option', 'snapTolerance');
                    var leftRemainder = ui.position.left % 1;
                    if (leftRemainder <= snapTolerance) {
                        ui.position.left = ui.position.left - leftRemainder;
                    }
                    ui.position.top = 0;
                    setRangeDelay(this);
                }
            });
            range.resizable({handles: "e, w",
                resize: function (event, ui) {
                    setRangeDelay(this);
                }});

            var currentIndex = 0;
            var mItem = $('.kads-slider-ul-list-row').find('li').length - 1;
            this.ll.sortable({
                start: function (event, ui) {
                    currentIndex = ui.helper.index();
                },
                change: function (event, ui) {
                    var indexCount = ui.item.parent().find('li');
                    var sortClass = '.' + ui.item.attr('class').split(' ')[0];
                    var parent = $('.kads-slider-ul-list-row');

                    if (currentIndex > ui.placeholder.index()) {
                        parent.find('li').eq(indexCount.index(ui.placeholder)).before(parent.find(sortClass));
                    } else
                    {
                        parent.find('li').eq(indexCount.index(ui.placeholder)).after(parent.find(sortClass));
                    }
                    currentIndex = ui.placeholder.index();
                },
                stop: function (event, ui) {
                    var parent = $('.kads-slider-ul-list-row');
                    var sortClass = '.' + ui.item.attr('class').split(' ')[0];
                    currentIndex = ui.item.index();
                    if (currentIndex === mItem)
                    {
                        parent.find('li').eq(currentIndex).after(parent.find(sortClass));
                    } else {
                        parent.find('li').eq(currentIndex).before(parent.find(sortClass));
                    }

                    _m.saveLayerItem();
                }
            });
            this.ltl.sortable();
            this.ll.disableSelection();
        },
        /**
         * Set selected box for index
         * @param {type} i
         * @returns {undefined}
         */
        selectedbox: function (i) {
            var layer = $('#box-' + i, this.g);
            this.layerSelected = layer;

            this.resetValue();
            this.isReset = 1;
            $('.box-layer', this.g).removeClass('selected');

            layer.addClass('selected');
            this.layerSelected = layer;
            this.current = layer.attr('current');
            this.currentData = this.getDataLayer(this.current);


            $.each(this.currentData, function (k, v) {
                _m.setctrlval(k, v);
            });

            this.clabel = $('#timelinelable-' + i, this.ll);
            this.ctline = $('#timeline-' + i, this.ltl);

            $('.kads-slider-layer-item-timeline', this.ltl).removeClass('selected');
            $('.kads-slider-layer-item-label', this.ll).removeClass('selected');

            this.ctline.addClass('selected');
            this.clabel.addClass('selected');

            this.currentRangeDelay = $('.kads-slider-range-delay', this.ctline);
            this.currentRangeValue = $('.kads-slider-range-value', this.ctline);
            this.isReset = 0;
        },
        /**
         * Copy layer data
         * @param {type} n
         * @returns {undefined}
         */
        copyLayer : function (n){
            var layer = this.getListLayer(n);
            if(layer)
            {
                var d = this.getDataLayer(n);
                var data = {};
                $.each(d, function (k,v){
                    data[k] = v;
                });
                var lidx = this.lidx;
                this.addLayer();
                $.each(data, function (a, b) {
                    _m.styles.setval(_m.layerSelected, a, b);
                });
                switch (data.type) {
                    case 'html':
                    case 'image':
                        break;
                    case 'button':
                        break;
                    default :
                        this.setelmedia(data.link, data.type, 'layer');
                        break;
                }
                this.currentData = data;
                this.setDataLayer(lidx, this.currentData);
                this.fnlayertldata(data);
                
                this.styles.setval(this.layerSelected, 'top', 0);
                this.styles.setval(this.layerSelected, 'left', 0);
                this.addLayerData('top',0);
                this.addLayerData('left',0);
            }
        },
        /**
         * Set layer data of timeline
         * @param {type} data
         * @returns {undefined}
         */
        fnlayertldata: function (data) {
            switch (data.type) {
                case 'html':
                    if (this.currentData.html)
                    {
                        var html = this.currentData.html.removehtml();
                        $('.kads-slider-layer-labeltext', this.clabel).html(html);
                    }
                    break;
                case 'image':
                    $('.kads-slider-layer-labeltext', this.clabel).html('Image');
                    $('.kads-slider-layer-icons', this.clabel).html('<span class="dashicons dashicons-format-image"></span>');
                    break;
                case 'button':
                    $('.kads-slider-layer-labeltext', this.clabel).html('Button');
                    $('.kads-slider-layer-icons', this.clabel).html('<span class="dashicons dashicons-share-alt"></span>');
                    break;
                default :
                    $('.kads-slider-layer-labeltext', this.clabel).html('Video');
                    $('.kads-slider-layer-icons', this.clabel).html('<span class="dashicons dashicons-format-video"></span>');
                    break;
            }
            var w = data.speed / 100;
            var wd = data.wait + w;
            if (wd > this.maxTimeRun)
            {
                this.maxTimeRun = wd - this.refixpx;
            }
            $('.kads-slider-range-show', this.ctline).css({'left': data.wait, 'width': w});
            $('.kads-slider-range-delay', this.ctline).css('width', (wd - 10));
            this.currentRangeValue.html((w - this.refixpx) * 10);
        },
        /**
         * Save layer item
         * @returns {undefined}
         */
        saveLayerItem: function () {

            var nItem = {};
            $('.kads-slider-layer-item-label', this.layerframes).each(function () {
                var id = $(this).data('id');
                var k = _m.getLayerKey(id);
                var item = _m.getDataLayer(id);
                nItem[k] = item;
            });
            this.item.layers = nItem;
        },
        /**
         * Load javacript 
         * @param {type} js
         * @param {type} callback
         * @returns {undefined}
         */
        loadScript: function (js, callback) {
            if (this.scriptLoaded[js])
            {
                if(callback)
                {
                    callback.call(this);
                }
                return;
            }
            var url = this.sever.url + '/' + js;
            $.getScript(url, function () {
                _m.scriptLoaded[js] = true;
                if(callback)
                {
                    callback.call(this);
                }
            });
        },
        /**
         * Load css main
         * @param {type} css
         * @returns {undefined}
         */
        loadcss: function (css) {
            if (this.css3Loaded[css])
            {
                return;
            }
            var url = this.sever.url + '/css/' + css;
            var link = $("<link>", {
                rel: "stylesheet",
                type: "text/css",
                href: url
            });
            link.appendTo('head');
            this.css3Loaded[css] = true;
        },
        /**
         * Main item preview
         * @returns {undefined}
         */
        fnmPreview: function () {
            $('#actions-form', this.c).val('kads-slider-manager-preview');
            var params = $('#sliders_form', this.c).serializeArray();
            this.loadcss('preview-slider.css');
            $.ajax({
                url: this.sever.ajax,
                type: "post",
                data: params,
                success: function (response) {
                    if(response.success)
                    {
                        $('#slider-preview-all-warper').html(response.data);
                        $('.slider-preview-all-warper').fadeIn();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //console.log(textStatus, errorThrown);
                }


            });

            $('#actions-form', this.c).val('save-slider');
        }
    };
    /**
     * Changed value
     * @param {type} v
     * @returns {unresolved}
     */
    $.fn.kadsSliderChangeVal = function (v) {
        return $(this).val(v).trigger("change");
    };
    /**
     * Remove old class
     * @param {type} val
     * @returns {kads-slider-manager_L7.$.fn@call;each}
     */
    $.fn.removeClassExcept = function (val) {
        return this.each(function () {
            $(this).removeClass().addClass(val);
        });
    };
    $(document).ready(function () {
        $.kadsManager.init();
    });
})(jQuery);

