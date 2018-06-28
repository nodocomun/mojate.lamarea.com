<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Music Playlist
 * 
 * Access original fields: $mod_settings
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):

    $fields_default = array(
        'mod_title_playlist' => '',
        'music_playlist' => array(),
        'hide_download_audio' => 'yes',
        'add_css_audio' => '',
        'animation_effect' => '',
        'audio_buy_button_text' => '',
		'audio_buy_button_link' => '',
		'buy_button_new_window' => ''
    );

    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
	$animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);

    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, $fields_args['add_css_audio'], $animation_effect
                    ), $mod_name, $module_ID, $fields_args)
    );

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>
    <!-- module audio -->
    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php do_action('themify_builder_before_template_content_render'); ?>

        <?php if ($fields_args['mod_title_playlist'] !== ''): ?>
            <?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title_playlist'], $fields_args) . $fields_args['after_title']; ?>
        <?php endif; ?>
        <?php if (!empty($fields_args['music_playlist'])): ?>
            <div class="album-playlist">
                <div class="jukebox">
                    <ol class="tracklist">
                        <?php $default_text = __('Buy now', 'builder-audio'); ?>
                        <?php foreach ($fields_args['music_playlist'] as $item) : ?>
                            <li class="track is-playable" itemprop="track" itemscope="" itemtype="http://schema.org/MusicRecording">
                                <?php if (!empty($item['audio_buy_button_link']) || !empty($item['audio_buy_button_text'])) : ?>
									<a
										class="ui builder_button default track-buy"
										href="<?php echo !empty($item['audio_buy_button_link']) ? $item['audio_buy_button_link'] : '#' ?>"
										<?php echo ! empty( $fields_args['buy_button_new_window'] ) ? 'target="_blank"' : ''; ?>>
										<?php echo !empty($item['audio_buy_button_text']) ? $item['audio_buy_button_text'] : $default_text; ?>
                                    </a>
                                <?php endif; ?>
                                <a class="track-title" href="#" itemprop="url"><span itemprop="name"><?php echo isset($item['audio_name']) ? $item['audio_name'] : ''; ?></span></a>
                                <?php if ($fields_args['hide_download_audio'] !== 'yes') : ?>
                                    <a href="<?php echo $item['audio_url']; ?>" class="builder-audio-download" download><i class="fa fa-download"></i></a>
                                    <?php endif; ?>
                                    <?php echo wp_audio_shortcode(array('src' => $item['audio_url'])); ?>
                            </li>
                        <?php endforeach; ?>

                    </ol>
                </div><!-- .jukebox -->
            </div><!-- .album-playlist -->
        <?php endif; ?>
        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module audio -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>