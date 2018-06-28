<?php

class PTB_Submission_Payment {

    protected static $plugin_name;
    protected static $version;
    protected static $logo;

    public function __construct($plugin, $version) {
      
		self::$plugin_name = $plugin;
		self::$version = $version;
		self::$logo = apply_filters('ptb_submission_payment_logo', get_header_image());
     
    }
    

    public function success(array $post_data, array $data, $post = false) {
        ?>
        <div class="ptb_submission_payment_success ptb_submission_paypal_success"><?php echo esc_textarea(PTB_Utils::get_label($data['m'])) ?></div>
        <?php
    }

    public function fail(array $post_data, array $data, $post = false) {
        ?>
        <div class="ptb_submission_payment_fail ptb_submission_paypal_fail"><?php _e('You refuse payment', 'ptb-submission') ?></div>
        <?php
    }

}
