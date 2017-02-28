<?php
$pageTitle = "Forgot Password";

require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';

?>
<?php include dirname(__FILE__) . '/views/header.php'; ?>

<?php include dirname(__FILE__) . '/views/account/forgot_password.php'; ?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>