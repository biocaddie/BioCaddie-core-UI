<?php

error_reporting(E_ERROR); //this is for ignore error caoused by the non-existing index in a array
$datatypes = getDatatypes();
$show_flag = "none";
$show_flag_biosharing = "none";
$errors = '';
$ready = '';

if (sizeof($_POST) > 1) {

    if ($_POST['datarepo_datatype'] == "Other") {
        $show_flag = "block";
    }
    if (isset($_POST['overview'])) {
        show_table($errors, $ready);
    } elseif (isset($_POST['submit'])) {
        if (empty($_SESSION['6_letters_code']) ||
            strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0
        ) {
            //Note: the captcha code is compared case insensitively.
            //if you want case sensitive match, update the check above to
            // strcmp()
            $errors .= "\n The captcha code does not match!";
        } else {
            $ready = "yes";
        }
        show_table($errors, $ready);
        if (empty($errors)) {
            sendEmails_new();
            submit_repository_to_db_new();
            echo '<script type="text/javascript">';
            echo 'alert("You submission has been received.Thank you!");';
            echo 'document.location.href = "index.php";';
            echo '</script>';
        }
    }
}

$select1 = 'Selected';
$select2 = '';
$select3 = 'Selected';
$select4 = '';
$select5 = 'Selected';
$select6 = '';
if ($_POST['use_agreement'] == 'Yes') {
    $select2 = 'Selected';
}
if ($_POST['in_biosharing'] == 'Yes') {
    $select4 = 'Selected';
}
if ($_POST['metadata_in_format'] == 'Yes') {
    $select6 = 'Selected';
}
$checkflag_doi = '';
$checkflag_ARK= '';
$checkflag_access= '';
$checkflag_other= '';

if(in_array('DOI',$_POST['data_identifiers'])){
    $checkflag_doi = 'checked';
}if(in_array('ARK',$_POST['data_identifiers'])){
    $checkflag_ARK = 'checked';
}if(in_array('Accession numbers',$_POST['data_identifiers'])){
    $checkflag_access = 'checked';
}
if(in_array('Other',$_POST['data_identifiers'])){
    $checkflag_other = 'checked';
}

function submit_repository_to_db_new()
{
    $result = process_user_input_to_db();
    $objDBController = new DBController();
    $dbconn = $objDBController->getConn();
    submit_repository_new($dbconn, $result);
}

function process_user_input(){
    $showing_label = get_repository_showing_label_new();
    $result = [];
    foreach (array_keys($_POST) as $key){
        if($key=='data_identifiers'&& sizeof($_POST[$key])>0){
            array_push($result,[$showing_label[$key],implode(',',$_POST[$key])]);
        }
       else if (strlen($_POST[$key]) > 0 &&array_key_exists($key, $showing_label)) {
           if($key == "datarepo_datatype" && $_POST["datarepo_datatype"] == "Other"){
               array_push($result,[$showing_label[$key],$_POST['otherDatatype']]);
           }
           elseif($key == "in_biosharing" && $_POST["in_biosharing"] == "Yes"){
               array_push($result,[$showing_label[$key],$_POST['biosharing']]);
           }
           else{
               array_push($result,[$showing_label[$key],$_POST[$key]]);
           }
       }
    }
    return $result;
}
function process_user_input_to_db(){
    $result = [];
    foreach (array_keys($_POST) as $key){
        if($key=='6_letters_code'){
            continue;
        }
        if($key=='data_identifiers'&& sizeof($_POST[$key])>0){
            array_push($result,[$key,implode(',',$_POST[$key])]);
        }
        else if (strlen($_POST[$key]) > 0) {
            if($key == "datarepo_datatype" && $_POST["datarepo_datatype"] == "Other"){
                array_push($result,[$key,$_POST['otherDatatype']]);
            }
            elseif($key == "in_biosharing" && $_POST["in_biosharing"] == "Yes"){
                array_push($result,[$key,$_POST['biosharing']]);
            }
            else{
                array_push($result,[$key,$_POST[$key]]);
            }
        }
    }
    return $result;
}

function show_table($errors, $ready)
{
    ?>
    <?php $result = process_user_input();?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <h4>Please review your dataset information before submission</h4>

        <div class="panel panel-info">
            <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel">
                <table class="table table-striped">
                    <tbody>
                    <?php foreach ($result as $item) { ?>
                            <tr>
                                <td style="width: 20%;"><?php echo $item[0]; ?></td>
                                    <td> <?php echo $item[1]; ?></td>
                            </tr>
                        <?php }?>

                    </tbody>
                </table>
            </div>
        </div>
        <?php $disable = ""; ?>
        <?php if (!empty($errors)) {
            echo "<p class='err' style='color:red'>" . nl2br($errors) . "</p>";
        } ?>
        <?php if ($ready == 'yes') {
            $disable = "disabled";
        } ?>
        <p>
            <img src="lib/html-contact-form-captcha/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg'><br>
            <label for='message'>Enter the code above here :</label><br>
            <input id="6_letters_code" name="6_letters_code" type="text"><br>
            <small>Can't read the image? click <a href='javascript: refreshCaptcha();' class="hyperlink">here</a> to
                refresh
            </small>
        </p>
        <div class="panel-footer" style="height: 60px">
            <div>
                <button type="submit" class="btn btn-warning pull-right" id="btn-submit"
                        name="submit" <?php echo $disable; ?>>Submit
                </button>
            </div>
        </div><!--/.panel-footer-->
    </div>
<?php } ?>

<?php function construct_message_new()
{
    $message = "";
    $result = process_user_input();
    foreach($result as $item){
        $message = $message . $item[0] . ':'.$item[1] . '<br>';

    }
    return $message;
}

function sendEmails_new(){
    $subject = $_POST['datarepo_name'];
    require_once dirname(__FILE__) . '/../../vendor/swiftmailer/swiftmailer/lib/swift_required.php';

    $from = $_POST["submitter_email"];
    $to = array("xiaoling.chen@uth.tmc.edu");//,"ruiling.liu@uth.tmc.edu","Anupama.E.Gururaj@uth.tmc.edu","Saeid.Pournejati@uth.tmc.edu","jgrethe@ncmir.ucsd.edu","yul129@ucsd.edu");

    $body = 'DataMed submit repository request review<br>
    ----------------------------------------<br>
    MESSAGE: '.construct_message_new();


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



<h5>* indicates required fields</h5>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-info">
        <div class="panel-heading" role="tab" id="heading-resource">
            <h4 class="panel-title">
                <a role="button">
                    Submitter
                </a>
            </h4>
        </div>
        <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel">
            <div class="panel-body" style="margin-bottom: -30px">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td style="width: 20%;"><strong>* Name</strong></td>
                        <td><span><input type="text" name="submitter_name" class="form-control" id="submitter_name"
                                         placeholder="Your name" value="<?php echo $_POST['submitter_name']; ?>"
                                         required></span></td>
                    </tr>
                    <tr>
                        <td><strong>* Email</strong></td>
                        <td><input type="email" name="submitter_email" class="form-control" id="submitter_email"
                                   placeholder="Your email" value="<?php echo $_POST['submitter_email']; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>* Submitting organization</strong></td>
                        <td><input type="text" name="submitter_organization" class="form-control"
                                   id="submitter_organization" value="<?php echo $_POST['submitter_organization']; ?>"
                                   required></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-info">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                Contact Person (if different than above)
            </h4>
        </div>

        <div class="panel-collapse collapse in" role="tabpanel">
            <div class="panel-body" style="margin-bottom: -30px">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td style="width: 20%;"><strong>  Contact Person</strong></td>
                        <td><span><input type="text" name="contact_person" class="form-control"
                                         placeholder="" value="<?php echo $_POST['contact_person']; ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong> Contact email</strong></td>
                        <td><span><input type="email" name="contact_email" class="form-control" id="contactEmail"
                                         placeholder=""
                                         value="<?php echo $_POST['contact_email']; ?>" ></span></td>
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
    <div id="collapse-resource2" role="tabpanel" aria-labelledby="heading-resource2">
        <div class="panel-body" style="margin-bottom: -30px">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td style="width: 20%;"><strong>* Repository Full Name</strong></td>
                    <td><span><input type="text" name="datarepo_name" class="form-control" id="datarepo_name"
                                     placeholder="" value="<?php echo $_POST['datarepo_name']; ?>"
                                     required></span></td>
                </tr>
                <tr>
                    <td><strong>Repository Abbreviation</strong></td>
                    <td><span><input type="text" name="datarepo_abbr" class="form-control" id="datarepo_abbr"
                                     placeholder="" value="<?php echo $_POST['datarepo_abbr']; ?>"></span></td>
                </tr>
                <tr>
                    <td><strong>* Homepage URL </strong></td>
                    <td><input type="text" name="datarepo_homepage" class="form-control" id="datarepo_homepage"
                               value="<?php echo $_POST['datarepo_homepage']; ?>" required></td>
                </tr>
                <tr>
                    <td><strong>* Type of content</strong></td>

                    <td>
                        <input style="display:<?php echo $show_flag; ?>;float: right; width: 78%" type="text"
                               name="otherDatatype" class="form-control" id="otherDatatype"
                               value="<?php echo $_POST['otherDatatype']; ?>">

                        <div style="overflow: hidden; padding-right: .5em;" class="dropdown">
                            <select class="button" name="datarepo_datatype" onchange="showinputbox(this)"
                                    value="<?php echo $_POST['datarepo_datatype']; ?>">
                                <?php foreach ($datatypes as $datatype):
                                    $select = "";
                                    if ($_POST['datarepo_datatype'] == $datatype) {
                                        $select = "selected";
                                    } ?>
                                    <option <?php echo $select; ?>
                                        value="<?php echo $datatype; ?>"><?php echo $datatype; ?></option>
                                <?php endforeach; ?>

                                <?php $select = "";
                                if ($_POST['datarepo_datatype'] == "Other") {
                                    $select = "selected";

                                } ?>
                                <option <?php echo $select; ?> value="Other">Other</option>
                            </select>

                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            Background Information
        </h4>
    </div>
    <div class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body" style="margin-bottom: -30px">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td style="width: 50%;"><strong> How much data (bytes, number of objects) are available to the scientific community?</strong></td>
                    <td><span><input type="text" name="datarepo_size" class="form-control" id="contact_people"
                                     placeholder="" value="<?php echo $_POST['datarepo_size']; ?>"></span>
                    </td>
                </tr>
                <tr>
                    <td><strong> Is a data use agreement required?</strong></td>
                    <td>
                        <select class="btn btn-default" name="use_agreement">
                            <option <?php echo $select1; ?> value="No">No</option>
                            <option <?php echo $select2; ?> value="Yes">Yes</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>When was the repository created?
                            </strong>
                    </td>
                    <td><span><input type="text" name="created_time" class="form-control" id="created_time"
                                     placeholder="" value="<?php echo $_POST['created_time']; ?>"></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>How is the repository funded?
                        </strong>
                    </td>
                    <td><span><input type="text" name="repo_funded" class="form-control" id="repo_funded"
                                     placeholder="" value="<?php echo $_POST['repo_funded']; ?>"></span>
                    </td>
                </tr>

                <tr>
                    <td>
                    <strong> Is the repository listed on biosharing.org? </strong> <br>If yes, please provide a biosharing ID. <br> If not, we recommend that you visit <a class='hyperlink' href="https://biosharing.org/databases">https://biosharing.org/databases.</a>
                    </td>
                    <td>
                        <input style="display:<?php echo $show_flag_biosharing; ?>;float: right; width: 78%" type="text"
                               name="biosharing" class="form-control" id="biosharing"
                               value="<?php echo $_POST['biosharing']; ?>">

                            <select class="btn btn-default" name="in_biosharing" onchange="showinputbox_biosharing(this)">
                                <option <?php echo $select3; ?> value="No">No</option>
                                <option <?php echo $select4; ?> value="Yes">Yes</option>
                            </select>


                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>What dataset identifiers are used?   </strong>

                    </td>
                    <td><input type="checkbox" name="data_identifiers[]" value="DOI" <?php echo $checkflag_doi;?>>&nbsp;DOI<br>
                        <input type="checkbox" name="data_identifiers[]" value="ARK" <?php echo $checkflag_ARK;?>>&nbsp;ARK<br>
                        <input type="checkbox" name="data_identifiers[]" value="Accession numbers" <?php echo $checkflag_access;?> >&nbsp;Accession numbers<br>
                        <input type="checkbox" name="data_identifiers[]" value="Other" <?php echo $checkflag_other;?>>&nbsp;Other
                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>What metadata standards are used?   </strong> <br>(Please provide biosharing ids.)

                    </td>
                    <td><span><input type="text" name="metadata_standards" class="form-control" id="metadata_standards"
                                     placeholder="" value="<?php echo $_POST['metadata_standards']; ?>"></span>
                    </td>
                </tr>


                <tr>
                    <td><strong>Is metadata accessible in a machine-actionable format (XML, RDM, etc.)? </strong></td>
                    <td>
                        <select class="btn btn-default" name="metadata_in_format">
                            <option <?php echo $select5; ?> value="No">No</option>
                            <option <?php echo $select6; ?> value="Yes">Yes</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel-footer" style="height: 60px">
    <div>
        <button type="submit" class="btn btn-warning pull-right" id="btn-overview" name="overview">Review
        </button>
    </div>
</div><!--/.panel-footer-->

</div>


<script>

    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });

    function refreshCaptcha() {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
    }

</script>



