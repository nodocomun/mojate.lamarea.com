<?php
$dir = dirname(dirname(__DIR__));
require_once($dir.'/includes/class-ptb-submissions-captcha.php');
if (!empty($_GET['ptb_captcha']) &&!empty($_GET['t'])) {
    PTB_Submission_Captcha::output($_GET['ptb_captcha']);
}