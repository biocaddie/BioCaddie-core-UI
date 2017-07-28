<?php

$pageTitle = "About DataMed";

//require_once 'config/config.php';
require_once dirname(__FILE__).'/Model/TrackActivity.php';
require_once dirname(__FILE__).'/Model/AboutService.php';
require_once dirname(__FILE__).'/vendor/autoload.php';

$errors = '';
$name = '';
$email = '';
$message = '';
$subject ='';

if(isset($_POST['submit']))
{
	$subject = $_POST['SUBJECT'];
	$name = $_POST["NAME"];
	$message = $_POST["MESSAGE"];
	$email = $_POST["EMAIL"];

	if(empty($_SESSION['6_letters_code'] ) ||
	  strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
	{
	//Note: the captcha code is compared case insensitively.
	//if you want case sensitive match, update the check above to
	// strcmp()
		$errors .= "\n The captcha code does not match!";
	}
	if(empty($errors))
	{
		//send the email
		sendEmails();
		postToGitHub();

		echo '<script type="text/javascript">';
		echo 'alert("You request has been received and posted to GitHub We will contact you soon.")';
		echo '</script>';
	}
}


?>

<?php include dirname(__FILE__) . '/views/header.php'; ?>
<?php include dirname(__FILE__) . '/views/about/about.php'; ?>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/about.js"];
?>
<?php include dirname(__FILE__) . '/views/footer.php'; ?>

