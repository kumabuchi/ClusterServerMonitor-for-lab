(function($) {
    $.fn.slider = function(options) {
        var $this = this;
        var settings = {
            'width': this.width(),
            'height': this.height(),
            'wait': 4000,
            'fade': 750,
            'direction': 'left',
            'showControls': true,
            'showProgress': true,
            'hoverPause': true,
            'autoplay': true,
            'randomize': false,
            'slidebefore': function() {},
            'slideafter': function() {},
            'rewind': function() {}
        };
        var _timer = false;
        var _last = false;
        var _this = false;
        var _cycle = function() {
                clearTimeout(_timer);
                _last = _this;
                if (settings.direction == 'right') {
                    _this = _this.prev('.jquery-slider-element');
                } else {
                    _this = _this.next('.jquery-slider-element');
                }
                if (!_this.length) {
                    _rewind();
                }
                _draw();
                if (!$this.hasClass('jquery-slider-paused') && settings.autoplay) {
                    _timer = setTimeout(_cycle, settings.wait);
                }
            };
        var _rewind = function() {
                if (settings.direction == 'right') {
                    _this = $this.children('.jquery-slider-element').last();
                } else {
                    _this = $this.children('.jquery-slider-element').first();
                }
                settings.rewind(_this, $this);
            };
        var _reset = function() {
		if( $(".jquery-slider-page").last().hasClass("jquery-slider-page-current") ){
			return;
		}
		console.log( $(".jquery-slider-pages").first().html() );
		_last = _this;
                _this = $this.children('.jquery-slider-element').first();
		_draw();
            };
        var _draw = function() {
                $this.addClass('jquery-slider-sliding');
                if (settings.showProgress) {
                    $this.find('.jquery-slider-page').removeClass('jquery-slider-page-current');
                    $this.find('.jquery-slider-page:eq(' + (_this.nextAll('.jquery-slider-element').length) + ')').addClass('jquery-slider-page-current');
                }
                settings.slidebefore(_this, $this);
                if (settings.direction == 'right') {
                    _this.show().css('left', -settings.width);
                } else {
                    _this.show().css('left', settings.width);
                }
                _this.stop(true, true).animate({
                    'left': (settings.direction == 'right' ? '+=' : '-=') + settings.width + 'px'
                }, {
                    'duration': settings.fade,
                    'complete': function() {
                        settings.slideafter(_this, $this);
                        $this.removeClass('jquery-slider-sliding');
                    }
                });
                if (_last) {
                    _last.stop(true, true).animate({
                        'left': (settings.direction == 'right' ? '+=' : '-=') + settings.width + 'px'
                    }, {
                        'duration': settings.fade
                    });
                }
            };
        var _next = function() {
                if ($this.hasClass('jquery-slider-sliding')) return;
                var direction = settings.direction;
                $this.addClass('jquery-slider-paused');
                settings.direction = 'left';
                _cycle();
                settings.direction = direction;
            };
        var _prev = function() {
                if ($this.hasClass('jquery-slider-sliding')) return;
                var direction = settings.direction;
                $this.addClass('jquery-slider-paused');
                settings.direction = 'right';
                _cycle();
                settings.direction = direction;
            };
        var _init = function() {
                if (options) {
                    $.extend(settings, options);
                }
                if (settings.hoverPause) {
                    $this.bind({
                        'mouseenter': function() {
                            $this.addClass('jquery-slider-paused');
                            clearTimeout(_timer);
                        },
                        'mouseleave': function() {
                            $this.removeClass('jquery-slider-paused');
                            if (settings.autoplay) {
                                _timer = setTimeout(_cycle, settings.wait);
                            }
                        }
                    });
                }
                var positionEls = $('<span class="jquery-slider-pages"></span>');
                $this.addClass('jquery-slider').width(settings.width).height(settings.height);
                $this.children().each(function() {
                    var $tmp = $(this);
                    _this = $(this).addClass('jquery-slider-element');
                    positionEls.prepend($('<span class="jquery-slider-page"></span>').bind('click', function() {
                        if ($this.hasClass('jquery-slider-sliding')) return;
                    	if ($(this).hasClass("jquery-slider-page-current") ) return;
                        _last = _this;
                        _this = $tmp;
                        _draw();
                    }));
                });
                if (settings.showProgress) {
                    $this.append(positionEls);
                }
                if (settings.showControls) {
                    var controlCycle = $('.slide-controls').bind('click', function() {
                        _cycle();
                    });
                }
                if (settings.randomize) {
                    _this = $this.children('.jquery-slider-element').eq(parseInt($this.children('.jquery-slider-element').length * Math.random(),10));
                }
                _cycle();
            };
        $("#slider").fadeOut(0);
	$("#page-title").click(_reset);
        _init();
        init();
        setTimeInfo(0);
    };
})(jQuery);

