;
(function ($, window, document, undefined) {

    "use strict";

    var InfiniteBgFront = {
        horizontal: BuilderInfiniteBg.horizontal,
        vertical: BuilderInfiniteBg.vertical,
        speed: BuilderInfiniteBg.speed,
        init: function () {
            BuilderInfiniteBg = null;
            var is_init = false;
            if (themifybuilderapp !== undefined) {
                this.bind();
                is_init = true;
            }
            $('body').on('builder_load_module_partial', function (e, el, type) {
                if (is_init === false) {
                    InfiniteBgFront.bind();
                    is_init = true;
                }
                else if (type !== 'module' && themifybuilderapp.saving === false && themifybuilderapp.toolbar.undoManager.is_working === false) {
                    var batch = el ? el[0].querySelectorAll('[data-cid]') : document.querySelectorAll('[data-cid]'),
                            batch = Array.prototype.slice.call(batch);
                    if (el) {
                        batch.unshift(el[0]);
                    }
                    for (var i = 0, len = batch.length; i < len; ++i) {
                        InfiniteBgFront.setStyles(batch[i].getAttribute('data-cid'));
                    }
                }
            });
        },
        bind: function () {
            setTimeout(InfiniteBgFront.ready, 800);
            InfiniteBgFront.bindLiveStyling();
        },
        ready: function () {
            var settings = themifybuilderapp.Models.Registry.items;
            for (var cid in settings) {
                InfiniteBgFront.setStyles(cid);
            }
        },
        setStyles: function (cid) {
            var model = themifybuilderapp.Models.Registry.items[cid];
            if (model.get('elType') !== 'module') {
                var st = model.get('styling');
                if (st['background_type'] === 'image' && st['background_image'] && st['row_scrolling_background'] !== undefined && st['row_scrolling_background'] !== 'disable') {
                    var template = InfiniteBgFront.getStyle(st['row_scrolling_background'], cid, st['row_scrolling_background_speed'], st['row_scrolling_background_width'], st['row_scrolling_background_height']);
                    if (template !== null) {
                        $('.tb_element_cid_' + cid)[0].insertAdjacentHTML('afterbegin', template);
                    }
                }
            }
        },
        getStyle: function (type, id, speed, width, height) {
            var template = null;
            if (type !== '' && (width || height)) {
                template = type === 'bg-scroll-horizontally' ? this.horizontal : this.vertical;
                if (!speed) {
                    speed = this.speed;
                }
                template = template.replace(/\#unique_id\#/ig, id).replace(/\#speed\#/ig, speed.toString() + 's');
                if (type === 'bg-scroll-horizontally') {
                    template = template.replace(/\#width\#/ig, width);
                }
                else {
                    template = template.replace(/\#height\#/ig, height);
                }
                template = '<style scoped type="text/css" id="themify_builder_infinite_bg-' + id + '">.tb_element_cid_' + id + template + '</style>';
            }
            return template;
        },
        removeInfiniteBg: function (id) {
            $('#themify_builder_infinite_bg-' + id).remove();
        },
        bindLiveStyling: function (e) {
            var api = themifybuilderapp;
            function callback() {
                var self = InfiniteBgFront,
                        context = api.liveStylingInstance.$context,
                        cid = api.activeModel.cid;
                self.removeInfiniteBg(cid);
                var template = self.getStyle($('#row_scrolling_background input:checked', context).val(), cid, $('#row_scrolling_background_speed', context).val(), $('#row_scrolling_background_width', context).val(), $('#row_scrolling_background_height', context).val());
                if (template !== null) {
                    $('.tb_element_cid_' + cid)[0].insertAdjacentHTML('afterbegin', template);
                }
            }
            api.liveStylingInstance.$context
                    .on('change', '#row_scrolling_background input', callback)
                    .on('keyup', '#row_scrolling_background_speed,#row_scrolling_background_width,#row_scrolling_background_height', callback);

        }
    };

    InfiniteBgFront.init();


}(jQuery, window, document));
