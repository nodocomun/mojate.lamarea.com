/*FitText 1.1*/
!function (a) {
    a.fn.fitText = function (b) {
        var c, d = a.extend({minFontSize: Number.NEGATIVE_INFINITY, maxFontSize: Number.POSITIVE_INFINITY, lineCount: 1, scale: 100}, b);
        return this.each(function () {
            var b = a(this);
            b.css({"white-space": "nowrap", position: "absolute", width: "auto"}), c = parseFloat(b.width()) / parseFloat(b.css("font-size")), b.css({position: "", width: "", "white-space": ""});
            var e = function () {
                b.css("font-size", Math.max(Math.min(d.scale / 100 * d.lineCount * b.width() / c - d.lineCount, parseFloat(d.maxFontSize)), parseFloat(d.minFontSize)))
            };
            e(), a(window).on("resize.fittext orientationchange.fittext", e)
        })
    }
}(jQuery);

(function ($) {

    function do_fittext(el, type) {
        var items = $('.module.module-fittext', el);
        if(el && el.hasClass('module-fittext') && el.hasClass('module')){
            items = items.add(el);
        }
        function callback() {
            function apply_fittext(el) {
                el.find('span')
                        .fitText()
                        .fitText(); // applying it twice fixes the issue of text breaking with some fonts.
                el.css('visibility', 'visible');
            }
            items.each(function () {
                var thiz = $(this);
                if (thiz.data('font-family') === 'default' || $.inArray(thiz.data('font-family'), builderFittext.webSafeFonts) > -1) {
                    apply_fittext(thiz);
                } else {
					var _font = thiz.data('font-family');
                    WebFont.load({
                        google: {
                            families: [_font]
                        },
						fontactive: function () {
                            apply_fittext(thiz);
                        },
						fontinactive: function () { // fail-safe: in case font fails to load, use the fallback font and apply the effect.
							apply_fittext(thiz);
						}
                    });
                }
            });
        }
        if (items.length > 0) {
            if (typeof WebFont === 'undefined') {
                Themify.LoadAsync('//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', callback, '1.4.7', false, function () {
                    return typeof WebFont !== 'undefined';
                });
            }
            else {
                callback();
            }
        }
    }
    if (Themify.is_builder_active) {
		$('body')
			.on('builder_load_module_partial', function (e, el, type) { do_fittext(el, type); })
			.on( 'builder_dom_changed', function( e, type ) { do_fittext(); } );
    }
    do_fittext();
})(jQuery);