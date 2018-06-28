(function ($, window, document, undefined) {
    'use strict';
    if (typeof shortcodes_map_button != 'undefined' && shortcodes_map_button && shortcodes_map_button.length > 0) {

        tinymce.PluginManager.add('ptb_map', function (editor, url) {
            var $items = [];
            for (var k in shortcodes_map_button) {

                var $item = {
                    'text': shortcodes_map_button[k].name,
                    'body': {
                        'type': shortcodes_map_button[k].type
                    },
                    onclick: function (e) {
                        var $settings = this.settings.body;
                        $.ajax({
                            url: $ptb_map_url,
                            type: 'POST',
                            dataType: 'json',
                            data: {'post_type': $settings.type},
                            success: function (resp) {
                                if (resp) {
                                    var post_data = [];
                                    var $data = resp.data;
                                    for (var $key in $data) {
                                        if ($key === 'marker') {
                                            var $form_items = {
                                                'label': $data[$key].label,
                                                'name': $key,
                                                'fixedWidth': true,
                                                'html': $data[$key].html,
                                                'type': 'container'
                                            };
                                        }
                                        else {
                                            var $form_items = {
                                                'name': $key,
                                                'values': $data[$key].values ? $data[$key].values : '',
                                            };

                                            $form_items = $.extend(true, $form_items, $data[$key]);
                                        }
                                        post_data.push($form_items);
                                    }
                                    if (resp.taxes) {
                                        for (var i in resp.taxes) {
                                            var $list = {
                                                'label': resp.taxes[i].label,
                                                'name': 'ptb_tax_' + resp.taxes[i].name,
                                                'fixedWidth': true,
                                                'html': '<select class="ptb_map_select" id="ptb_tax_' + resp.taxes[i].name + '_select" style="width:100%;" multiple="multiple"><option value="">---</option>',
                                                'type': 'container'
                                            };
                                            for (var $i in resp.taxes[i].values) {
                                                $list.html += '<option value="' + resp.taxes[i].values[$i].slug + '">' + resp.taxes[i].values[$i].name + '</option>';
                                            }
                                            $list.html += '</select>';
                                            post_data.push($list);
                                        }
                                    }
                                    editor.windowManager.open({
                                        id: 'ptb_map_shortcode_dialog',
                                        body: post_data,
                                        title: resp.title,
                                        minWidth: 500,
                                        resizable: 'yes',
                                        onOpen: function () {
                                            $('#mce-modal-block').css('zIndex', 1000);
                                            $('#ptb_map_shortcode_dialog').css('zIndex', 1001);
                                        },
                                        onsubmit: function (e) {
                                            var $short = '';
                                            var $trigger_short = $.event.trigger("PTB_Map.insert_shortcode", {'shortcode': $short, 'setting': $settings, 'data': e.data});
                                            if ($trigger_short) {
                                                $short = $trigger_short;
                                            }
                                            else {
                                                $short = '[ptb_map_view  post_type="' + $settings.type + '"';

                                                for (var $k in e.data) {
                                                    if ($('#' + $k + '_select').length > 0) {
                                                        var $vals = $('#' + $k + '_select').val();
                                                        e.data[$k] = $vals ? $vals.join(', ') : false;
                                                    }
                                                    if (e.data[$k]) {
                                                        $short += ' ' + $k + '="' + e.data[$k] + '"';
                                                    }
                                                }
                                                if ($('#marker').val()) {
                                                    $short += ' marker="' + $('#marker').val() + '"';
                                                }
                                                $short += ']';
                                            }
                                            editor.insertContent($short);
                                        }
                                    }
                                    );
                                }
                            }
                        });
                    },
                    classes: shortcodes_map_button[k].classes
                };
                $items.push($item);
            }
            ;
            editor.addButton('ptb_map', {
                icon: 'ptb-map-favicon',
                type: 'menubutton',
                title: 'PTB Map View',
                menu: $items
            });
        });
        var ptb_cmb_image_file_frame;
        $('body').delegate('.ptb-icons-list a', 'click', function (e) {
            if ($('#ptb_row_wrapper').length === 0) {
                e.preventDefault();
                var $v = $(this).attr('href');
                $('input#marker').val($v);
                $('#ptb_marker_preview').css('background-image', 'none').prop('class', 'fa fa-' + $v);
                $('a.ptb_close_lightbox').trigger('click');
            }
        }).delegate('#ptb_marker_file', 'click', function (event) {

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (ptb_cmb_image_file_frame) {
                ptb_cmb_image_file_frame.open();
                return;
            }

            // Create the media frame.
            ptb_cmb_image_file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {
                    text: $(this).data('uploader_button_text')
                },
                library: {type: 'image'},
                multiple: false
            });
            ptb_cmb_image_file_frame.on('select', function () {
                var attachment = ptb_cmb_image_file_frame.state().get('selection').first().toJSON();
                $('input#marker').val(attachment.url);
                $('#ptb_marker_preview').css('background-image', 'url(' + attachment.url + ')').prop('class', '');
                ptb_cmb_image_file_frame.close();
            });

            // Finally, open the modal
            ptb_cmb_image_file_frame.open();
        });
    }
}(jQuery, window, document));
