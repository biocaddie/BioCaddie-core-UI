<?php

require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/trackactivity.php';
require_once 'vendor/autoload.php';

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
		//echo 'alert("You request has been received and posted to <a class="hyperlink" href = "https://github.com/biocaddie/prototype_issues/issues">GitHub </a>. We will contact you soon.")';
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

function postToGitHub(){
	$client = new \Github\Client();
	$client->authenticate('biocaddie.mail@gmail.com', 'biocaddie4050@', Github\Client::AUTH_HTTP_PASSWORD);
	$client->api('issue')->create('biocaddie', 'prototype_issues', array('title' => $_POST['SUBJECT'], 'body' => $_POST["MESSAGE"]));
}

?>

<?php include dirname(__FILE__).'/views/header.php'; ?>
<div class="container" style="background-color:#DCDCDC">

	<h3 style="text-align:center"> OUR MISSION </h3>

	<p style="text-align:center">Develop a prototype of a data discovery index.</p>
	<div class="about_box" style="background-color:white">
		<br>

		<!-- <p style="text-align:center;font-size:20px">              About</p>-->

		<p class="about_style">DataMed is a prototype biomedical data search engine. Its goal is to discover data sets across data repositories or data aggregators.
			In the future it will allow searching outside these boundaries. DataMed supports the NIH-endorsed  <a class="hyperlink" href="http://www.nature.com/articles/sdata201618">FAIR</a> principles of Findability, Accessibility, Interoperability
			and Reusability of datasets with current functionality assisting in finding datasets and providing access information about them.</p>

		<p class="about_style">The data repositories covered in this initial release have been selected by the bioCADDIE team and represent only a small sample of biomedical data. Many highly
			relevant data sets have not yet been indexed. Please provide <a class='hyperlink' href="submit_repository.php">suggestions</a> on data repositories that DataMed should cover.</p>

		<p class="about_style">The translation of user queries into structured data used in the search is being refined. Advanced search allows users to structure their queries.
			Some DataMed search features are not yet available for all data. Please provide feedback about the quality of search results.</p>

		<p class="about_style">DataMed indexes the core metadata available for most datasets, but it offers enhanced search functions when repositories provide additional metadata.
			The Data Tag Suite <a class='hyperlink' href ="https://biocaddie.org/group/working-group/working-group-3-descriptive-metadata-datasets"> (DATS)</a> metadata specification
			has evolved with input from the community and will include a schema.org annotated serialization.</p>


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
					<div class="row" >
					<div class ="col-md-1">
			    <!--<input type="submit" value="Submit" name='submit'  class="btn btn-warning" onclick="process()" id="submitBtn" style="margin-bottom: 20px">-->
				<input type="submit" value="Submit" name='submit'  class="btn btn-warning"  id="submitBtn" style="margin-bottom: 20px">
						</div>
</div>
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


/*show loading sign on button click*/
/*function process(){
	$('#submitBtn').prop("disabled",true);
	//alert("Your request has been received.");

}*/

</script>
</body>
</html>
