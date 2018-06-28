<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Audio
 */

class TB_Audio_Module extends Themify_Builder_Component_Module {

    function __construct() {
        parent::__construct(array(
            'name' => __('Audio', 'builder-audio'),
            'slug' => 'audio'
        ));
    }

    function get_assets() {
        $instance = Builder_Audio::get_instance();
        $options = array(
            'selector' => '.module-audio, .module-type-audio',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/script.js'),
            'ver' => $instance->version
        );
        if(Themify_Builder_Model::is_front_builder_activate()){
           
            $options['external'] = Themify_Builder_Model::localize_js('builderAudio', wpview_media_sandbox_styles());
        }
        return $options;
    }

    public function get_options() {
        return array(
            array(
                'id' => 'mod_title_playlist',
                'type' => 'text',
                'label' => __('Module Title', 'builder-audio'),
                'class' => 'large',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'music_playlist',
                'type' => 'builder',
                'options' => array(
                    array(
                        'id' => 'audio_name',
                        'type' => 'text',
                        'label' => __('Audio Name', 'builder-audio'),
                        'class' => 'large',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'music_playlist'
                        )
                    ),
                    array(
                        'id' => 'audio_url',
                        'type' => 'audio',
                        'label' => __('Audio File URL', 'builder-audio'),
                        'class' => 'xlarge',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'music_playlist',
                            'control_type' => 'change'
                        )
                    ),
                    array(
                        'id' => 'audio_buy_button_text',
                        'type' => 'text',
                        'label' => __('Buy Button Text', 'builder-audio'),
                        'class' => 'large',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'music_playlist'
                        )
                    ),
                    array(
                        'id' => 'audio_buy_button_link',
                        'type' => 'text',
                        'label' => __('Buy Button Link', 'builder-audio'),
                        'class' => 'large',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'music_playlist'
                        )
                    )
                ),
                'render_callback' => array(
                    'binding' => 'live',
                    'control_type' => 'repeater'
                )
            ),
            array(
                'id' => 'hide_download_audio',
                'type' => 'select',
                'label' => __('Hide Download Link', 'builder-audio'),
                'options' => array(
                    'yes' => __('Yes', 'builder-audio'),
                    'no' => __('No', 'builder-audio')
                ),
                'render_callback' => array(
                    'binding' => 'live'
                )
			),
			array(
				'id' => 'buy_button_new_window',
				'type' => 'checkbox',
				'label' => __( 'Buy Button', 'builder-audio' ),
				'options' => array(
					array( 'name' => 'buy_button_target', 'value' => __('Open Buy Button in new window', 'builder-audi') )
				)
			),
            // Additional CSS
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr/>')
            ),
            array(
                'id' => 'add_css_audio',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-audio'),
                'class' => 'large exclude-from-reset-field',
                'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-audio')),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
        );
    }

    public function get_default_settings() {
        return array(
            'music_playlist' => array(array(
                    'audio_name' => __('Song Title', 'builder-audio'),
                    'audio_url' => 'https://themify.me/demo/themes/themes/wp-content/uploads/addon-samples/sample-song.mp3'
                ))
        );
    }

    public function get_styling() {
        $general = array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_color('.module-audio', 'background_color', __('Background Color', 'themify'), 'background-color'),
            // Font
            self::get_seperator('font', __('Font', 'themify')),
            self::get_font_family('.module-audio'),
            self::get_color(array('.module-audio', '.module-audio a', '.module-audio .album-playlist .mejs-controls .mejs-playpause-button button'), 'font_color', __('Font Color', 'themify')),
            self::get_font_size('.module-audio'),
            self::get_line_height('.module-audio'),
            self::get_letter_spacing('.module-audio'),
            self::get_text_align('.module-audio'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-audio'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-audio'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-audio')
        );

        $button_link = array(
            // Background
            self::get_seperator('audio_buy_button', __('Background', 'themify'), false),
            self::get_color('.module-audio .track-buy', 'audio_button_background_color', __('Background Color', 'themify'), 'background-color'),
            self::get_color('.module-audio .track-buy:hover', 'audio_button_hover_background_color', __('Background Hover', 'themify'), 'background-color'),
            // Link
            self::get_seperator('link', __('Link', 'themify')),
            self::get_color('.module-audio .track-buy', 'audio_link_color'),
            self::get_color('.module-audio .track-buy:hover', 'audio_link_color_hover', __('Color Hover', 'themify')),
            self::get_text_decoration('.module-audio .track-buy', 'audio_text_decoration'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-audio .track-buy'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-audio .track-buy'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-audio .track-buy')
        );

        return array(
            array(
                'type' => 'tabs',
                'id' => 'module-styling',
                'tabs' => array(
                    'general' => array(
                        'label' => __('General', 'themify'),
                        'fields' => $general
                    ),
                    'button_link' => array(
                        'label' => __('Buy Button', 'themify'),
                        'fields' => $button_link
                    )
                )
            )
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args();
        ?>
        <div class="module module-<?php echo $this->slug; ?> {{ data.add_css_audio }}">

            <# if( data.mod_title_playlist ) { #>
        <?php echo $module_args['before_title']; ?>
            {{{ data.mod_title_playlist }}}
        <?php echo $module_args['after_title']; ?>
            <# } 

            if( data.music_playlist ) { #>
            <div class="album-playlist">
                <div class="jukebox">
                    <ol class="tracklist">
                        <# _.each( data.music_playlist, function( item ) { #>
                        <li class="track is-playable" itemprop="track" itemscope="" itemtype="http://schema.org/MusicRecording">
                            <# if( item.audio_buy_button_link || item.audio_buy_button_text ) { #>
                            <a class="ui builder_button default track-buy" href="{{ item.audio_buy_button_link || '#' }}">{{ item.audio_buy_button_text || 'Buy Now' }}</a>
                            <# } #>

                            <a class="track-title" href="#" itemprop="url">
                                <# if( item.audio_name ) { #>
                                <span itemprop="name">{{{ item.audio_name }}}</span>
                                <# } #>
                            </a>
                            <# if( 'no' == data.hide_download_audio && item.audio_url ) { #>
                            <a href="{{ item.audio_url }}" class="builder-audio-download" download><i class="fa fa-download"></i></a>
                            <# } #>
                            <# if( item.audio_url ) { #>
                            <audio src="{{ item.audio_url }}"></audio>
                            <# } #>
                        </li>
                        <# } ); #>
                    </ol>
                </div><!-- .jukebox -->
            </div><!-- .album-playlist -->
            <# } #>
        </div>
        <?php
    }

}

Themify_Builder_Model::register_module('TB_Audio_Module');
