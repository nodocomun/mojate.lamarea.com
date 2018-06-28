(function ($) {
    var languages = [];
    ThemifyStories = {
        init: function (config) {
            this.loadLanguage(config.lang);
            this.createDiv(config);
            this.build(config);
        },
        createDiv: function (config) {
            var t = document.createElement("div"),
                    te = document.getElementById(config.embed_id),
                    embed_classname = "storyjs-embed full-embed";
            te.appendChild(t);
            t.setAttribute("id", config.id);
            te.style.width = config.width.split("%")[0] + "%";
            te.style.height = config.height - 16 + "px";
            te.setAttribute("class", embed_classname);
            te.setAttribute("className", embed_classname);

        },
        loadLanguage: function (lng) {
            if (lng !== 'en' && languages[lng] !== 1) {
                languages[lng] = 1;
                Themify.LoadAsync(builder_timeline.url + 'timeline/locale/' + lng + '.min.js');
            }
        },
        build: function (config) {
            VMM.debug = false;
            var storyjs_embedjs = new VMM.Timeline(config.id);
            storyjs_embedjs.init(config);
        }
    };

    function create_stories(e, el, type) {
        var items = $('.layout-graph.module-timeline', el);
        if (el && el.hasClass('module-timeline') && el.hasClass('layout-graph')) {
            items = items.add(el);
        }
        if (items.length > 0) {
            function callback() {
                items.each(function () {
                    if ($(this).find('.storyjs-embed').length === 0) {
                        var embed = $(this).find('.timeline-embed');
                        if (embed.length > 0) {
                            var config = embed.data('config');
                            config.source = JSON.parse(window.atob(embed.data('data')));
                            ThemifyStories.init(config);
                        }
                    }
                });
            }
            if (typeof VMM === 'undefined') {
                Themify.LoadCss(builder_timeline.url + 'timeline/css/timeline.min.css', '2.33.1');
                Themify.LoadAsync(builder_timeline.url + 'timeline/js/timeline.min.js', callback, '2.33.1', null, function () {
                    return ('undefined' !== typeof VMM);
                });

            }
            else {
                callback();
            }
        }
    }
    function wload() {
        if (window.loaded) {
            create_stories();
        }
        else {
            $(window).on('load', create_stories);
        }
    }
    if (Themify.is_builder_active) {
        $('body').on('builder_load_module_partial', create_stories);
        if (Themify.is_builder_loaded) {
            wload();
        }
    }
    else {
        wload();
    }


}(jQuery));