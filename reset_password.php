<?php

$pageTitle = "Reset Password";

require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';
include dirname(__FILE__) . '/views/header.php';
 ?>

<?php include dirname(__FILE__) . '/views/account/reset_password.php'; ?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>

