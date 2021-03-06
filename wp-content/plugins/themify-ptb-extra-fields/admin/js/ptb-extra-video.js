(function ($) {
    'use strict';
    /* Custom Meta Box Video*/
    
    $(document).on('ptb_add_metabox_video', function (e) {
        return {};
    }).on('ptb_post_cmb_video_body_handle', function (e) {
        var $optionsWrapper = e.cmbItemBody.find('.ptb_cmb_options_wrapper');

        if ($optionsWrapper.length === 0) {
            return false;
        }

        var $option = $optionsWrapper.children().first().clone();

        $optionsWrapper.sortable({
            placeholder: "ui-state-highlight"
        });

        e.cmbItemBody.find('.ptb_cmb_option_add')
                .click(
                        {
                            wrapper: $optionsWrapper
                        },
                function (event) {
                    var $newOption = $option.clone();
                    $newOption.appendTo($optionsWrapper).hide().show('blind', 500);
                    $newOption.find('[name^="' + e.id + '"]').val('');
                    $newOption.find('.ptb_post_cmb_image').removeClass('ptb_uploaded');
                    $newOption.find('.' + e.id + '_remove').click({item: $newOption}, removeOption);

                    event.data.wrapper.sortable("refresh");
                });

        $optionsWrapper.children().each(function () {
            var $self = $(this);
            $self.find('.' + e.id + '_remove').click({item: $self}, removeOption);
        });

        // remove option
        function removeOption(e) {
            e.preventDefault();
            e.data.item.hide('blind', 500, function () {
                $(this).remove();
            });
        }

        $('.ptb_post_cmb_item_video').on('click','.' + e.id + '_option_wrapper .ptb_post_cmb_image', function (event) {
            event.preventDefault();
            var $item = $(this),
             $parent = $item.closest('li'),
            // Create the media frame.
             ptb_cmb_image_file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {
                    text: $(this).data('uploader_button_text')
                },
                library: {type: 'video'},
                multiple: false  // Set to true to allow multiple files to be selected
            }).on('select', function () {
                // We set multiple to false so only get one image from the uploader
                var attachment = ptb_cmb_image_file_frame.state().get('selection').first().toJSON(),
                    $new = $('#auto_draft').length > 0,
                    $title = $parent.find('input[name^="' + e.id + '[title]"]');
                if ($new || !$.trim($title.val())) {
                    $title.val(attachment.title);
                }
                var $desc = $parent.find('textarea');
                if ($new || $.trim($desc.val())) {
                    $desc.val(attachment.caption);
                }
                $parent.find('input[name^="' + e.id + '[url]"]').val(attachment.url);
                $parent.find('textarea').val(attachment.caption);
                $item.addClass('ptb_uploaded');
            }).open();
        });
    });

}(jQuery));
 