(function ($, window, document, undefined) {
    "use strict";
    var SliderVideosFront = {
        isLoaded: false,
        init: function () {
            var self = SliderVideosFront;
            $('body').on('editing_row_option', function (e, type, settings, context) {
                setTimeout(function () {
                    self.bindLiveStyling(context);
                }, 400);
            });
            $('body').one('builder_load_module_partial', function () {
                self.loadFrontend(function () {
                    SliderVideos.init();
                });
            });
            self.loadFrontend(function () {
                SliderVideos.init();
            });
        },
        loadFrontend: function (callback) {
            if (!this.isLoaded && 'undefined' === typeof SliderVideos) {
                self.isLoaded = true;
                Themify.LoadCss(tb_slider_videos_vars.url + 'assets/frontend-styles.css');
                Themify.LoadAsync(
                        tb_slider_videos_vars.url + 'assets/frontend-scripts.js',
                        callback,
                        null,
                        null,
                        function () {
                            return ('undefined' !== typeof SliderVideos)
                        }
                );
            }
            else {
                callback();
            }
        },
        bindLiveStyling: function (context) {
            context = $(context);
            var api = themifybuilderapp,
                    self = this,
                    $repeater = context.find('#background_slider_videos');
            function removeVideos() {
                api.liveStylingInstance.$liveStyledElmt.find('.tb_slider_videos').first().remove();
            }
            function reInit() {
                function callback() {
                    removeVideos();
                    var data = JSON.stringify(api.Forms.parseSettings($repeater[0]).v);
                    api.liveStylingInstance.$liveStyledElmt.data('tb_slider_videos', data).attr('data-tb_slider_videos', data);
                    SliderVideos.init(null, api.liveStylingInstance.$liveStyledElmt, 'row');
                }
                self.loadFrontend(callback);

            }
            context.on('change', 'input[name="background_type"]', function () {
                var instance = api.liveStylingInstance.$liveStyledElmt;
                if ($(this).val() !== 'slidervideos') {
                    instance.css('z-index', '').data('tb_slider_videos', '').removeAttr('data-tb_slider_videos');
                    removeVideos();
                }
                else {
                    api.liveStylingInstance.removeBgSlider();
                    api.liveStylingInstance.removeBgVideo();
                    reInit();
                }
            });
            $repeater.on('duplicate delete reapeat_sortable', reInit).on('change', 'input', reInit);
            context.find('.tb-group-element-slidervideos select').on('change', function () {
                var v = $(this).val(),
                        key = $(this).prop('id').replace('background_slider_videos', 'tb_slider');
                api.liveStylingInstance.$liveStyledElmt.data(key, v).attr('data-' + key, v);
                reInit();
            });
        }
    };
    SliderVideosFront.init();
}(jQuery, window, document));
