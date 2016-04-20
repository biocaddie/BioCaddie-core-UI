<?php

require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/trackactivity.php';

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

		echo '<script type="text/javascript">';
		echo 'alert("You request has been received and we will contact you soon")';
		echo '</script>';
	}
}
function sendEmails(){
	$subject = $_POST['SUBJECT'];

	require_once dirname(__FILE__) . '/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

	$from = $_POST["EMAIL"];
	$to = array("xiaoling.chen@uth.tmc.edu","ruiling.liu@uth.tmc.edu","Anupama.E.Gururaj@uth.tmc.edu","Saeid.Pournejati@uth.tmc.edu");
	$body = 'bioCADDIE contact request<br>
        ----------------------------------------<br>
        NAME: '.$_POST["NAME"].'<br>
        MESSAGE: '.$_POST["MESSAGE"].'<br>
        EMAIL: '.$_POST["EMAIL"];

	$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
			->setUsername('biocaddie.mail@gmail.com')
			->setPassword('biocaddie4050@');

	$mailer = Swift_Mailer::newInstance($transport);

	$message = Swift_Message::newInstance('bioCADDIE Contact us email:' . $subject)
			->setFrom(array($from => 'bioCADDIE'))
			->setTo($to)
			->setBody($body)
			->setContentType("text/html");
	$mailer->send($message);
}

?>

<?php include dirname(__FILE__).'/views/header.php'; ?>
<div class="container" style="background-color:#DCDCDC">

	<h3 style="text-align:center"> OUR MISSION </h3>

	<p style="text-align:center">Develop a prototype of a data discovery index.</p>
	<div class="about_box" style="background-color:white">
		<br>

		<!-- <p style="text-align:center;font-size:20px">              About</p>-->
		<p class="about_style">   We at the bioCADDIE (biomedical and healthcare Data Discovery Index Ecosystem) are developing a prototype of a data discovery index (DDI) to help you search and find datasets you’re interested in. Our prototype aims to provide:</p>

		<p class="about_style">   1.         A free, user-friendly means for you to locate data sets of interest.</p>
		<p class="about_style">   2.         Standardized, searchable information (metadata) about the contents of a data set.</p>

		<p class="about_style">   You can use the web interface and provide us  feedback <a class="hyperlink" href="./feedback.php">here </a> about your search experience. This is a prototype in development and your valuable suggestions and comments will help us to make the system better.</p>

		<p class="about_style">   For more information about bioCADDIE, click <a class="hyperlink" href="https://biocaddie.org/" target="_blank"> here </a></p>

		<p class="about_style" style="color: red; font-size: 12px;"> * We respect your privacy and will not share your personal information except as needed for access to DataMed. </p>

		<p class="about_style" >   Contact us:</p>

		<div id='contact_form_errorloc' class='err'></div>
		<form method="POST" name="contact_form"
			  action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" class="about_style" data-toggle="validator">
			    <div class="about_style" >
                    <label for="name">Name *</label>
                    <input type="text" name="NAME" class="form-control" id="pwd" placeholder="Your name" value="<?php echo $name;?>" required>


                    <label for="email">E-mail address *</label>
                    <input type="email" name="EMAIL" class="form-control" id="email" placeholder="Your email"  value="<?php echo $email;?>"required>
                    <div class="form-group">
                        <label for="">Subject *</label>
                        <input type="text" name="SUBJECT" class="form-control" id="subject" value="<?php echo $subject;?>" required >
                    </div>
                    <div class="form-group">
                        <label for="">Message * </label>
                        <textarea type="text" name="MESSAGE" class="form-control" id="message" rows="4" required><?php echo htmlspecialchars($message)?></textarea>
                    </div>
					<?php if(!empty($errors)) {
					echo "<p class='err' style='color:red'>" . nl2br($errors) . "</p>";
					}	?>

			<p>
				<img src="html-contact-form-captcha/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
				<label for='message'>Enter the code above here :</label><br>
				<input id="6_letters_code" name="6_letters_code" type="text"><br>
				<small>Can't read the image? click <a href='javascript: refreshCaptcha();' class="hyperlink">here</a> to refresh</small>
			</p>
			    <input type="submit" value="Submit" name='submit'>
			</div>
		</form>
    </div>
</div>


<?php include dirname(__FILE__) . '/views/footer.php'; ?>
<script language='JavaScript' type='text/javascript'>
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
</body>
</html>