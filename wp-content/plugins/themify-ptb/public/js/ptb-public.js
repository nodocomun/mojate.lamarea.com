var PTB;
(function ($) {
    'use strict';
    function triggerEvent(a, b) {
        var c;
        document.createEvent ? (c = document.createEvent("HTMLEvents"), c.initEvent(b, !0, !0)) : document.createEventObject && (c = document.createEventObject(), c.eventType = b), c.eventName = b, a.dispatchEvent ? a.dispatchEvent(c) : a.fireEvent && htmlEvents["on" + b] ? a.fireEvent("on" + c.eventType, c) : a[b] ? a[b]() : a["on" + b] && a["on" + b]()
    }

    PTB = {
        jsLazy:[],
        cssLazy:[],
        mobile:null,
        hash: function (str) {
            var hash = 0;
            for (var i = 0, len = str.length; i < len; ++i) {
                hash = ((hash << 5) - hash) + str.charCodeAt(i);
                hash = hash & hash; // Convert to 32bit integer
            }
            return hash;
        },
        LoadAsync: function (src, callback, version, test) {
            var id = this.hash(src), // Make script path as ID
                exist = this.jsLazy.indexOf(id) !== -1,
                existElemens = exist || document.getElementById(id);
            if(!exist){ 
                this.jsLazy.push(id);
            }
            if (existElemens) {
                if (callback) {
                    if (test) {
                        var callbackTimer = setInterval(function () {
                            var call = false;
                            try {
                                call = test.call();
                            } catch (e) {
                            }
                            if (call) {
                                clearInterval(callbackTimer);
                                callback.call();
                            }
                        }, 20);
                    } else {
                        callback();
                    }
                }
                return;
            }
            else if (test) {
                try {
                    if (test.call()) {
                        if (callback) {
                            callback.call();
                        }
                        return;
                    }
                } catch (e) {
                }
            }
            if (src.indexOf('.min.js') === -1 && typeof ptb.min!=='undefined') {
                var name = src.match(/([^\/]+)(?=\.\w+$)/);
                if (name && name[0]) {
                    name = name[0];
                    if (ptb.min.js[name]) {
                        src = src.replace(name + '.js', name + '.min.js');
                    }
                }
            }
            var s, r, t;
            r = false;
            s = document.createElement('script');
            s.type = 'text/javascript';
            s.id = id;
            if(!version && version!==false){
                version = ptb.ver;
            }
            s.src = version? src + '?ver=' + version : src;
            s.async = true;
            s.onload = s.onreadystatechange = function () {
                if (!r && (!this.readyState || this.readyState === 'complete'))
                {
                    r = true;
                    if (callback) {
                        callback();
                    }
                }
            };
            t = document.getElementsByTagName('script')[0];
            t.parentNode.insertBefore(s, t);
        },
        LoadCss: function (href, version, before, media,callback) {
            var id = this.hash(href),
                exist = this.cssLazy.indexOf(href)  !== -1,
                existElemens =exist || document.getElementById(id),
                fullHref =!version  ? href + '?ver=' + ptb.ver : href; 
            if(!exist){
                this.cssLazy.push(href);
            }
            if (existElemens || $("link[href='" + fullHref + "']").length > 0) {
                if(callback){
                    callback();
                }
                return;
            }
             if (href.indexOf('.min.css') === -1 && typeof ptb.min!=='undefined') {
                var name = href.match(/([^\/]+)(?=\.\w+$)/);
                if (name && name[0]) {
                    name = name[0];
                    if (ptb.min.css[name]) {
                        fullHref = fullHref.replace(name + '.css', name + '.min.css');
                    }
                }
            }
            var doc = window.document,
                ss = doc.createElement('link'),
                ref;
            if (before) {
                ref = before;
            }
            else {
                var refs = (doc.body || doc.getElementsByTagName('head')[ 0 ]).childNodes;
                ref = refs[ refs.length - 1];
            }

            var sheets = doc.styleSheets;
            ss.rel = 'stylesheet';
            ss.href = fullHref;
            // temporarily set media to something inapplicable to ensure it'll fetch without blocking render
            ss.media = 'only x';
            ss.async = 'async';
            ss.id = id;

            // Inject link
            // Note: `insertBefore` is used instead of `appendChild`, for safety re: http://www.paulirish.com/2011/surefire-dom-element-insertion/
            ref.parentNode.insertBefore(ss, (before ? ref : ref.nextSibling));
            // A method (exposed on return object for external use) that mimics onload by polling document.styleSheets until it includes the new sheet.
            var onloadcssdefined = function (cb) {
                var resolvedHref = ss.href,
                    i = sheets.length;
                while (i--) {
                    if (sheets[ i ].href === resolvedHref) {
                        if (callback) {
                            callback();
                        }
                        return cb();
                    }
                }
                setTimeout(function () {
                    onloadcssdefined(cb);
                });
            };

            // once loaded, set link's media back to `all` so that the stylesheet applies once it loads
            ss.onloadcssdefined = onloadcssdefined;
            onloadcssdefined(function () {
                ss.media = media || 'all';
            });
            return ss;
        },
        is_mobile:function(){
            if(this.mobile===null){
                this.mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) ||
                /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4));
            }
            return this.mobile;
        }
    };

$(document).ready(function () {
        var $body = $('body'),
            single = $('.ptb_single_content');
        function ptb_lightbox_position() {
            $('#lightcase-case').find('.ptb_single_lightbox').css('max-height', $(window).height() - 100);
        }
        if(single.length>0){
            single.replaceWith(single.find('.ptb_post').first());
        }
        $(document).trigger('ptb_loaded',false)
        .on('ptb_ligthbox_close', function () {
          $('#lightcase-case').removeClass('ptb_is_single_lightbox');
          $body.removeClass('ptb_hide_scroll');
          $(window).unbind('resize', ptb_lightbox_position);
        });
        PTB_Lightbox();
        
        //Single Page Lightbox
       
        function PTB_Lightbox(){
            var items =  $('a.ptb_open_lightbox'),
                $lightbox = $('a.ptb_lightbox');
            if(items.length>0 || $lightbox.length>0){
                PTB.LoadCss(ptb.url + 'css/lightbox.css');
                PTB.LoadAsync(ptb.url + 'js/lightbox.min.js', function() {
                    if(items.length>0){
                        items.lightcase({
                            type: 'ajax',
                            maxWidth: $(window).width() * 0.8,
                            onFinish: {
                                bar: function () {
                                    $body.addClass('ptb_hide_scroll');
                                },
                                baz: function () {
                                    var $container = $('#lightcase-case');
                                    $container.addClass('ptb_is_single_lightbox').find('.ptb_post img').css('display', 'block');
                                    $(document).trigger('ptb_loaded',true);
                                    triggerEvent(window, 'resize');
                                    ptb_lightbox_position();
                                    $(window).resize(ptb_lightbox_position);
                                }
                            },
                            onClose: {
                                qux: function () {
                                    $.event.trigger({type: "ptb_ligthbox_close"});
                                }
                            }
                        });
                    }
                    //Page Lightbox
                    if($lightbox.length>0){
                        $lightbox.lightcase({
                            type: 'iframe',
                            onFinish: {
                                bar: function () {
                                    $.event.trigger({type: "ptb_ligthbox_close"});
                                    $body.addClass('ptb_hide_scroll');
                                }
                            },
                            onClose: {
                                qux: function () {
                                    $.event.trigger({type: "ptb_ligthbox_close"});
                                }
                            }
                        });
                    }
                },null,function(){
                    return ('undefined' !== typeof $.fn.lightcase);
                });
            }
        }

        //Isotop Filter

        var $filter = $('.ptb-post-filter');
        $filter.each(function () {
            var $entity = $(this).next('.ptb_loops_wrapper');
            $(this).on('click', 'li', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $posts = $entity.find('.ptb_post');
                $posts.removeClass('ptb-isotop-filter-clear');

                if ($(this).hasClass('ptb_filter_active')) {
                    $filter.find('li.ptb_filter_active').removeClass('ptb_filter_active');
                    $entity.removeClass('ptb-isotop-filter');
                    $posts.stop().fadeIn('normal');
                }
                else {
                    $filter.find('li.ptb_filter_active').removeClass('ptb_filter_active');
                    $(this).addClass('ptb_filter_active');
                    $entity.addClass('ptb-isotop-filter');
                    var $tax = '.ptb-tax-' + $(this).data('tax'),
                            $child = $(this).find('li');
                    if ($child.length > 0) {
                        $child.each(function () {
                            $tax += ' ,.ptb-tax-' + $(this).data('tax');
                        });
                    }
                    var $items = $posts.filter($tax),
                        $grid = $entity.hasClass('ptb_grid4') ? 4 : ($entity.hasClass('ptb_grid3') ? 3 : ($entity.hasClass('ptb_grid2') ? 2 : 1));
                    if ($grid > 1) {
                        $items.each(function ($i) {
                            if ($i % $grid === 0) {
                                $(this).addClass('ptb-isotop-filter-clear');
                            }
                        });
                    }
                    $posts.hide();
                    $items.not('visible').stop().fadeIn('normal');
                }
            });
        });

    });
}(jQuery));