<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/admin/partials
 */
?>
<?php
global $post_type;
$message = !$post_type ? __('Import will overwrite all existed templates. Press OK to continue, Cancel to stop.', 'ptb-relation') :
        __('Import will overwrite all existed templates. Press OK to continue, Cancel to stop.', 'ptb-relation');
$extensions = array(
    array(
        'title' => __('Json file', 'ptb-relation'),
        'extensions' => "json"
    ),
    array(
        'title' => __('Archive file', 'ptb-relation'),
        'extensions' => "zip"
    )
);
?>
<form method="post" action="" id="<?php echo $this->plugin_name ?>-import-form"  enctype="multipart/form-data">
    <?php if ($post_type): ?>
        <input type="hidden" value="<?php echo $post_type ?>" name="post_type" />
    <?php endif; ?>
    <input type="hidden" value="ptb_relation_import" name="action" />
    <input type="hidden" value="<?php echo wp_create_nonce($this->plugin_name . '-import') ?>" name="_nonce" />
    <div class="ptb-relation-loader"></div>
    <a data-formats='<?php echo wp_json_encode($extensions) ?>' data-name="import" data-confirm="<?php echo $message ?>" id="<?php echo $this->plugin_name ?>-import-btn" class="<?php echo $this->plugin_name ?>-file-btn" href=""><?php _e('Import', 'ptb-relation') ?></a>
    <div class="ptb-relation-error"></div>
</form>
<script type="text/javascript">
    jQuery(document).ready(function () {
        ptb_create_pluploader(jQuery('#<?php echo $this->plugin_name ?>-import-btn'));
    });
</script>
