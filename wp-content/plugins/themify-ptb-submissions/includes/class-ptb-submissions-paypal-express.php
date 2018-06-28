<?php

class PTB_Submission_PayPal_Express extends PTB_Submission_Payment {

    public function __construct($pluginname, $version) {
        parent::__construct($pluginname, $version);
    }
    private $_credentials = array(
      'USER' => 'v.petrosyan-facilitator_api1.gmail.com',
      'PWD' => 'MHNB5ZFGLW2BUMZW',
      'SIGNATURE' => 'AeFcdD.Jn7JOCBZmRTGXQCAX.l13AtNkkqgPO4cUAuKiEO0tCYj6Pr4U'
    );
    
    public function form($post_type, $post_id, array $args, $success_page, $cancel_page) {
        $options = PTB_Submissiion_Options::get_settings();
        if(empty($options['paypal-email'])){
             $options['paypal-email'] = get_option('admin_email');
        }
        if (!is_email($options['paypal-email'])) {
            return false;
        }
        
        $paypal_url = !empty($options['paypal-sandbox']) ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
        $amount = floatval($args['amount']);
        $post = array(
            'METHOD'=>'SetExpressCheckout',
            'RETURNURL'=>$success_page,
            'CANCELURL'=>$cancel_page,
            'PAYMENTREQUEST_0_AMT'=>$amount,
            'L_PAYMENTREQUEST_0_AMT0'=>$amount,
            'PAYMENTREQUEST_0_ITEMAMT'=>$amount,
            'L_PAYMENTREQUEST_0_NAME0'=> __('PTB Submission Fee', 'ptb-submission'),
            'L_PAYMENTREQUEST_0_QTY0' => 1,
            'PAYMENTREQUEST_0_CURRENCYCODE'=>$options['currency'],
            'PAYMENTREQUEST_0_INVNUM'=>$post_id,
            'PAYMENTREQUEST_0_PAYMENTACTION'=>'sale',
            'LOCALECODE'=>PTB_Utils::get_current_language_code(),
            'BRANDNAME'=>get_bloginfo('name'),
            'NOSHIPPING'=>1,
            'VERSION'=>95
        );
        if (is_user_logged_in()){
            $user =  wp_get_current_user();
            $post['EMAIL']=$user->user_email;
        }
        if (self::$logo){
            $post['LOGOIMG']=self::$logo;
        }
        $post+=$this->_credentials;
        $response = wp_remote_post($paypal_url, array(
            'method' => 'POST',
            'body' => $post,
            'httpversion' => '1.0',
            'sslverify' => false
        ));
     
        if (!is_wp_error($response) && !empty($response['body'])) {
            if(!is_array($response['body'])){
                parse_str($response['body'],$output);
            }
            else{
                $output = $response['body'];
            }
       
            if(isset($output['ACK']) && isset($output['TOKEN']) && $output['ACK']==='Success'){
                header( 'Location: https://www.paypal.com/webscr?'.$paypal_url.'?cmd=_express-checkout&token=' . urlencode($output['TOKEN']) );
                exit;
            }
        }
        _e('Something goes wrong, please try again.','ptb-submission');
       
    }

    private function sandbox($post_id, array $post_data, array $data) {
        if (function_exists('fsockopen')) {
            $req = 'cmd=_notify-validate';
            $get_magic_quotes_exists = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1;
            foreach ($post_data as $key => $value) {
                $value = $get_magic_quotes_exists ? urlencode(stripslashes($value)) : urlencode($value);
                $req.= "&$key=$value";
            }
            // post back to PayPal system to validate
            $status = !empty($data['approve']) ? 'publish' : 'pending';
            $header.= "POST /cgi-bin/webscr HTTP/1.0\r\n";
            $header.= "Host: www.sandbox.paypal.com\r\n";
            $header.= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header.= 'Content-Length: ' . strlen($req) . "\r\n\r\n";
            $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
            if ($fp) {
                fputs($fp, $header . $req);
                while (!feof($fp)) {
                    $res = fgets($fp, 1024);
                    if (strcmp($res, 'VERIFIED') === 0) {
                        fclose($fp);
                        return wp_update_post(array('ID' => $post_id, 'post_status' => $status));
                    } else if (strcmp($res, 'INVALID') === 0) {
                        fclose($fp);
                        return false;
                    }
                }
                fclose($fp);
            }
        }
        return false;
    }

    private function live($post_id, array $post_data, array $data) {
        /*
          $plugin_url = plugin_dir_path( __FILE__ ).'logs/';
          $myfile = fopen($plugin_url."logs.txt", "a") or die("Unable to open file!");
          fwrite($myfile,'live');
         */
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $post = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) === 2)
                $post[$keyval[0]] = $keyval[1];
        }
        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $post['cmd'] = '_notify-validate';
        $response = wp_remote_post('https://www.paypal.com/cgi-bin/webscr', array(
            'method' => 'POST',
            'body' => $post,
            'httpversion' => '1.0',
            'sslverify' => false
        ));
        if (!is_wp_error($response)) {
            // inspect IPN validation result and act accordingly
            if (strcmp($response['body'], 'VERIFIED') === 0) {
                $status = !empty($data['approve']) ? 'publish' : 'pending';
                return wp_update_post(array('ID' => $post_id, 'post_status' => $status));
            } else {
                //fwrite($myfile,$response['body']);
                return FALSE;
            }
        } else {
            /*
              fwrite($myfile,'error');
              fwrite($myfile,$response->get_error_message());
             * 
             */
        }
        //fclose($myfile);
        return FALSE;
    }

    public function result(array $post_data) {

        if (isset($post_data['item_number'])) {
            $post_id = (int)$post_data['item_number'];
            $post = get_post($post_id);
            if ($post && $post->post_status === 'draft') {
                $post_type = $post->post_type;
                $template = PTB_Submissiion_Options::get_submission_template($post_type);
                if (!isset($template['frontend']) || !isset($template['frontend']['data'])) {
                    global $wp_query;
                    $wp_query->set_404();
                    status_header(404);
                    wp_die();
                }
                $data = $template['frontend']['data'];
                $options = PTB_Submissiion_Options::get_settings();
                if (isset($post_data['receiver_email']) && $options['paypal-email'] === $post_data['receiver_email'] && floatval($data['amount']) <= floatval($post_data['mc_gross'])) {
                    return !empty($options['paypal-sandbox']) ?$this->sandbox($post_id, $post_data, $data):$this->live($post_id, $post_data, $data);
                }
            }
        }
    }
}
