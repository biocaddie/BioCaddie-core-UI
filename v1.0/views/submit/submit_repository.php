
<?php

$datatypes = getDatatypes();
$show_flag = "none";
$errors = '';
$ready='';

if (sizeof($_POST)>1) {

    if ($_POST['datarepo_datatype'] == "Other") {
        $show_flag = "block";
    }
    if (isset($_POST['overview'])) {
        show_table($errors,$ready);
    } elseif (isset($_POST['submit'])) {
        if (empty($_SESSION['6_letters_code']) ||
            strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0
        ) {
            //Note: the captcha code is compared case insensitively.
            //if you want case sensitive match, update the check above to
            // strcmp()
            $errors .= "\n The captcha code does not match!";
        }
        else{
            $ready="yes";
        }
        show_table($errors,$ready);
        if (empty($errors)) {
            sendEmails();
            submit_repository_to_db();
            echo '<script type="text/javascript">';
            echo 'alert("You submission has been received.Thank you!");';
            echo 'document.location.href = "index.php";';
            echo '</script>';
        }
    }
}

$select1='Selected';
$select2='';
$select3='Selected';
$select4='';
$select5='Selected';
$select6='';
if($_POST['sample_question']=='Yes'){
    $select2='Selected';
}
if($_POST['xml_question']=='Yes'){
    $select4='Selected';
}
if($_POST['work_question']=='Yes'){
    $select6='Selected';
}
function submit_repository_to_db(){
    $objDBController = new DBController();
    $dbconn=$objDBController->getConn();
    submit_repository($dbconn,$_POST);
}

function show_table($errors,$ready){?>
    <?php $showing_label=get_repository_showing_label();?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <h4>Please review your dataset information before submission</h4>
        <div class="panel panel-info">
            <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" >
            <table class="table table-striped">
                <tbody>
            <?php foreach(array_keys($_POST) as $key){?>
                  <?php if(strlen($_POST[$key])>0 && array_key_exists($key,$showing_label)){?>

                <tr>
                    <td style="width: 20%;"><?php echo $showing_label[$key];?></td>
                    <?php if($key=="datarepo_datatype" && $_POST["datarepo_datatype"]=="Other"):?>
                        <td><?php echo $_POST['otherDatatype'];?></td>
                    <?php else: ?>
                        <td> <?php echo $_POST[$key];?></td>
                     <?php endif; ?>
                </tr>
                <?php }}?>

                </tbody>
            </table>
             </div>
         </div>
        <?php $disable = "";?>
        <?php if(!empty($errors)) {
            echo "<p class='err' style='color:red'>" . nl2br($errors) . "</p>";
        }?>
        <?php if($ready=='yes'){
             $disable="disabled";
        }?>
        <p>
            <img src="html-contact-form-captcha/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
            <label for='message'>Enter the code above here :</label><br>
            <input id="6_letters_code" name="6_letters_code" type="text"><br>
            <small>Can't read the image? click <a href='javascript: refreshCaptcha();' class="hyperlink">here</a> to refresh</small>
        </p>
        <div class="panel-footer" style="height: 60px">
            <div>
                <button type="submit" class="btn btn-warning pull-right" id="btn-submit" name="submit" <?php echo $disable;?>>Submit</button>
            </div>
        </div><!--/.panel-footer-->
    </div>
 <?php } ?>

<?php function construct_message()
{   $message = "";
    $showing_label = get_repository_showing_label();
    foreach (array_keys($_POST) as $key) {
        if (strlen($_POST[$key]) > 0 && array_key_exists($key, $showing_label)) {

            $message = $message. $showing_label[$key].':';
        }
        if ($key == "datarepo_datatype" && $_POST["datarepo_datatype"] == "Other") {
            $message = $message. $_POST['otherDatatype'].'<br>';
        } else {
            $message = $message.  $_POST[$key].'<br>';
        }
    }
    return $message;
}

function sendEmails(){
    $subject = $_POST['datarepo_name'];

    require_once dirname(__FILE__) . '/../../vendor/swiftmailer/swiftmailer/lib/swift_required.php';

    $from = $_POST["contact_email"];
    $to = array("xiaoling.chen@uth.tmc.edu","ruiling.liu@uth.tmc.edu","Anupama.E.Gururaj@uth.tmc.edu","Saeid.Pournejati@uth.tmc.edu","jgrethe@ncmir.ucsd.edu","yul129@ucsd.edu");

    $body = 'DataMed submit repository request review<br>
    ----------------------------------------<br>
    NAME: '.$_POST["contact_people"].'<br>
    MESSAGE: '.construct_message().'<br>
    EMAIL: '.$_POST["contact_email"];
    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
    ->setUsername('biocaddie.mail@gmail.com')
    ->setPassword('biocaddie4050@');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance('bioCADDIE submit repo email:' . $subject)
    ->setFrom(array($from => 'bioCADDIE'))
    ->setTo($to)
    ->setBody($body)
    ->setContentType("text/html");
    $mailer->send($message);
}
?>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="heading-resource">
                <h4 class="panel-title">
                    <a role="button">
                        Submitter
                    </a>
                </h4>
            </div>
            <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" >
                <div class="panel-body" style="margin-bottom: -30px">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong>* Name</strong></td>
                            <td><span><input type="text" name="submitter_name" class="form-control" id="submitter_name" placeholder="Your name" value="<?php echo $_POST['submitter_name'];?>" required></span></td>
                        </tr>
                        <tr>
                            <td><strong>* Email</strong></td>
                            <td><input type="email" name="submitter_email" class="form-control" id="submitter_email" placeholder="Your email"  value="<?php echo $_POST['submitter_email'];?>"required></td>
                        </tr>
                        <tr>
                            <td><strong>* Submitting organization</strong></td>
                            <td><input type="text" name="submitter_organization" class="form-control" id="submitter_organization" value="<?php echo $_POST['submitter_organization'];?>" required ></td>
                        </tr>
                        <tr>
                            <td><strong>* Address</strong></td>
                            <td><input type="text" name="submitter_address" class="form-control" id="submitter_address" value="<?php echo $_POST['submitter_address'];?>" required ></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
       <div class="panel panel-info" id="accordion1">

           <div class="panel-heading" role="tab" id="heading-resource2">
               <h4 class="panel-title">
                       Data Repository
               </h4>
           </div>
           <div id="collapse-resource2"  role="tabpanel" aria-labelledby="heading-resource2">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 20%;"><strong>* Name</strong></td>
                           <td><span><input type="text" name="datarepo_name" class="form-control" id="datarepo_name" placeholder="" value="<?php echo $_POST['datarepo_name'];?>" required></span></td>
                       </tr>
                       <tr>
                           <td><strong>Abbreviation</strong></td>
                           <td><span><input type="text" name="datarepo_abbr" class="form-control" id="datarepo_abbr" placeholder="" value="<?php echo $_POST['datarepo_abbr'];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>* Homepage</strong></td>
                           <td><input type="text" name="datarepo_homepage" class="form-control" id="datarepo_homepage" value="<?php echo $_POST['datarepo_homepage'];?>" required></td>
                       </tr>
                       <tr>
                           <td><strong>* Datatype</strong></td>

                           <td>
                               <input style="display:<?php echo $show_flag;?>;float: right; width: 78%" type="text"  name="otherDatatype" class="form-control" id="otherDatatype" value="<?php echo $_POST['otherDatatype'];?>" >

                               <div style="overflow: hidden; padding-right: .5em;" class="dropdown">
                                   <select class="button" name="datarepo_datatype" onchange="showinputbox(this)" value="<?php echo $_POST['datarepo_datatype'];?>">
                                       <?php foreach($datatypes as $datatype):
                                           $select = "";
                                           if($_POST['datarepo_datatype']==$datatype) {
                                               $select = "selected";
                                           }?>
                                           <option <?php echo $select;?> value="<?php echo $datatype;?>"><?php echo $datatype;?></option>
                                       <?php endforeach;?>

                                       <?php $select = "";
                                       if($_POST['datarepo_datatype']=="Other") {
                                           $select = "selected";

                                       }?>
                                       <option <?php echo $select;?>  value="Other">Other</option>
                                   </select>

                               </div>
                           </td>
                       </tr>
                       <tr>
                           <td><strong>License</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="Relevant terms of usage and license" ></button></td>
                           <td><input type="text" name="datarepo_license" class="form-control" id="datarepo_license" value="<?php echo $_POST['datarepo_license'];?>" ></td>
                       </tr>
                       <tr>
                           <td><strong>Version</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="A release point for the repository when applicable" ></button></td>
                           <td><input type="text" name="datarepo_version" class="form-control" id="datarepo_version" value="<?php echo $_POST['datarepo_version'];?>" ></td>
                       </tr>
                       <tr>
                           <td><strong>Number of dataset</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="Size of the repository" ></button></td>
                           <td><input type="text" name="datarepo_size" class="form-control" id="datarepo_size" value="<?php echo $_POST['datarepo_size'];?>" ></td>
                       </tr>
                       </tbody>
                   </table>
               </div>
           </div>

           <div class="panel-heading" role="tab" id="heading-resource3">
               <h4 class="panel-title">
                   Contact
               </h4>
           </div>
           <div class="panel-collapse collapse in" role="tabpanel">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 50%;"><strong> * People to contact</strong></td>
                           <td><span><input type="text" name="contact_people" class="form-control" id="contact_people" placeholder="" value="<?php echo $_POST['contact_people'];?>" required ></span></td>
                       </tr>
                       <tr>
                           <td><strong>* Contact email</strong></td>
                           <td><span><input type="email" name="contact_email" class="form-control" id="contactEmail" placeholder="" value="<?php echo $_POST['contact_email'];?>" required></span></td>
                       </tr>
                       <tr>
                           <td>
                               <strong>Can you provide a sample metadata file of the dataset?</strong>
                           </td>
                           <td>
                             <select class="btn btn-default" name="sample_question">
                                 <option <?php echo $select1;?> value="No">No</option>
                                 <option <?php echo $select2;?> value="Yes">Yes</option>
                             </select>
                           </td>
                       </tr>
                       <tr><td>
                               <strong>Do you have a XML file which describes the metadata scheme?</strong>
                           </td>
                           <td>
                               <select class="btn btn-default" name="xml_question">
                                   <option <?php echo $select3;?> value="No">No</option>
                                   <option <?php echo $select4;?> value="Yes">Yes</option>
                               </select>
                           </td>
                       </tr>
                       <tr>
                           <td><strong>Do you have time to work with us?</strong></td>
                           <td>
                               <select class="btn btn-default" name="work_question">
                                   <option <?php echo $select5;?> value="No">No</option>
                                   <option <?php echo $select6;?> value="Yes">Yes</option>
                               </select>
                           </td>
                       </tr>
                       <!--<tr>
                           <td><strong>Sample XML files:</strong></td>
                           <td><input type="file" class ='btn' name="uploadfile"  /></td>
                       </tr-->
                       </tbody>
                   </table>
               </div>
           </div>
        </div>

        <div class="panel-footer" style="height: 60px">
            <div>
                <button type="submit" class="btn btn-warning pull-right" id="btn-overview" name="overview">Review</button>
            </div>
        </div><!--/.panel-footer-->

    </div>


<script>

    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    });

    function refreshCaptcha()
    {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
    }

</script>



