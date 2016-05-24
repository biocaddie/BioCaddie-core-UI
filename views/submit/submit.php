
<?php

$datatypes = getDatatypes();
$show_flag = "none";
if (sizeof($_POST)>1) {
    if($_POST['dataset_datatype']=="Other"){
        $show_flag = "block";
    }
    if(isset($_POST['overview'])){
        show_table();
    }
    elseif(isset($_POST['submit'])){
        submit_to_db();
        echo '<script type="text/javascript">';
        echo 'alert("You submission has been received.Thank you!")';
        echo '</script>';
    }
}
function submit_to_db(){
    $objDBController = new DBController_submit();
    $dbconn=$objDBController->getConn();
    submit($dbconn,$_POST);
}

function show_table(){?>
   <?php $showing_label = get_showing_label();?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <h4>Please review your dataset information before submission</h4>
        <div class="panel panel-info">
            <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" >
            <table class="table table-striped">
                <tbody>
            <?php foreach(get_ids() as $key){?>
                  <?php if(strlen($_POST[$key])>0 && array_key_exists($key,$showing_label)){?>

                <tr>
                    <td style="width: 20%;"><?php echo $showing_label[$key];?></td>
                    <?php if($key=="dataset_datatype" && $_POST["dataset_datatype"]=="Other"):?>
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

       <div class="panel-footer" style="height: 60px">
            <div>
                <button type="submit" class="btn btn-warning pull-right" id="btn-submit" name="submit">Submit</button>
            </div>
        </div><!--/.panel-footer-->
        </div>
 <?php } ?>



    <div class="panel-group" id="Submitter" role="tablist" aria-multiselectable="true">
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
                            <td><span><input type="text" name="submitter_name" class="form-control" id="submitter_name" placeholder="Your name" value="<?php echo $_POST["submitter_name"];?>" required></span></td>
                        </tr>
                        <tr>
                            <td><strong>* Email</strong></td>
                            <td><input type="email" name="submitter_email", class="form-control" id="submitter_email" placeholder="Your email"  value="<?php echo $_POST["submitter_email"];?>"required></td>
                        </tr>
                        <tr>
                            <td><strong>* Submitting organization</strong></td>
                            <td><input type="text" name="submitter_organization" class="form-control" id="submitter_organization" value="<?php echo $_POST["submitter_organization"];?>" required ></td>
                        </tr>
                        <tr>
                            <td><strong>* Address</strong></td>
                            <td><input type="text" name="submitter_address" class="form-control" id="submitter_address" value="<?php echo $_POST["submitter_address"];?>" required ></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
       <div class="panel panel-info" id="dataset">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a role="button">
                        Dataset
                    </a>
                </h4>
            </div>
            <div id="collapse-resource1" class="panel-collapse collapse in" role="tabpanel">
                <div class="panel-body" style="margin-bottom: -30px">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong>* Title</strong></td>
                            <td><span><input type="text" name='dataset_title' class="form-control" id='dataset_title' placeholder="" value="<?php echo $_POST['dataset_title'];?>" required></span></td>
                        </tr>
                        <tr>
                            <td><strong>* Description</strong></td>
                            <td><textarea type="text" name="dataset_description" class="form-control" id='dataset_description' rows="4" required><?php echo $_POST['dataset_description'];?></textarea></td>
                        </tr>
                        <tr>
                            <td><strong>* Datatype</strong></td>

                            <td>

                                <input style="display:<?php echo $show_flag;?>;float: right; width: 78%" type="text"  name="otherDatatype" class="form-control" id="otherDatatype" value="<?php echo $_POST['otherDatatype'];?>" >

                                <div style="overflow: hidden; padding-right: .5em;" class="dropdown">
                                    <select class="button" name="dataset_datatype" onchange="showinputbox(this)" value="<?php echo $_POST["dataset_datatype"];?>">
                                        <?php foreach($datatypes as $datatype):
                                            $select = "";
                                            if($_POST["dataset_datatype"]==$datatype) {
                                                $select = "selected";
                                            }?>
                                            <option <?php echo $select;?> value="<?php echo $datatype;?>"><?php echo $datatype;?></option>
                                        <?php endforeach;?>

                                        <?php $select = "";
                                        if($_POST["dataset_datatype"]=="Other") {
                                            $select = "selected";

                                        }?>
                                        <option <?php echo $select;?>  value="Other">Other</option>
                                    </select>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>* Download URL</strong></td>
                            <td><input type="text" name="dataset_downloadurl" class="form-control" id="dataset_downloadurl" value="<?php echo $_POST["dataset_downloadurl"];?>"  required></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Identifier</strong>
                                <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="A code uniquely identifying the dataset" ></button>
                            </td>

                            <td><input type="text" name="dataset_identifier" class="form-control" id="dataset_identifier" value="<?php echo $_POST["dataset_identifier"];?>" ></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Identifier Scheme</strong>
                                <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="information about how identifiers are formed,maintained and minted" ></button>
                            </td>
                            <td><input type="text" name='dataset_idscheme' class="form-control" id='dataset_idscheme' value="<?php echo $_POST['dataset_idscheme'];?>" ></td>
                        </tr>

                        <tr>
                            <td><strong>Size</strong>
                                <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="Size of the dataset" ></button></td>
                            <td><input type="text" name="dataset_size" class="form-control" id="dataset_size" value="<?php echo $_POST["dataset_size"];?>"></td>
                        </tr>

                        <tr>
                            <td><strong>Version</strong>
                                <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="A release point for the dataset when applicable" ></button></td>
                            <td><input type="text" name='dataset_version' class="form-control" id='dataset_version' value="<?php echo $_POST['dataset_version'];?>" ></td>
                        </tr>

                        <tr>
                            <td><strong>Release Date</strong></td>
                            <td><input type="text" name="dataset_date" class="form-control" id="dataset_date" value="<?php echo $_POST["dataset_date"];?>" ></td>
                        </tr>
                        <tr>
                            <td><strong>License</strong>
                                <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="Relevant terms of usage and license" ></button>
                            </td>

                            <td><input type="text" name="dataset_license" class="form-control" id="dataset_license" value="<?php echo $_POST["dataset_license"];?>" ></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
           <div class="panel-heading" role="tab" >
               <h4 class="panel-title">
                   Study
               </h4>
           </div>
           <div class="panel-collapse collapse in" role="tabpanel">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 20%;"><strong> Title</strong></td>
                           <td><span><input type="text" name="study_title" class="form-control" id="study_title" placeholder="" value="<?php echo $_POST["study_title"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Study Type</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="The type of study, e.g. intervention or observation or meta-analysis" ></button>
                           </td>
                           <td><span><input type="text" name="study_studytype" class="form-control" id="study_studytype" placeholder="" value="<?php echo $_POST["study_studytype"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Keywords</strong></td>
                           <td><span><input type="text" name="study_keywords" class="form-control" id="study_keywords" placeholder="" value="<?php echo $_POST["study_keywords"];?>" ></span></td>
                       </tr>
                       </tbody>
                   </table>
               </div>
           </div>
           <div class="panel-heading" role="tab" id="heading-resource4">
               <h4 class="panel-title">
                   Organism

               </h4>
           </div>
           <div id="collapse-resource4" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource4">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 20%;"><strong> Name</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="The scientific name of an organism" ></button></td>
                           <td><span><input type="text" name="organism_name" class="form-control" id="organism_name" placeholder="" value="<?php echo $_POST["organism_name"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Strain</strong></td>
                           <td><span><input type="text" name="organism_strain" class="form-control" id="organism_strain" placeholder="" value="<?php echo $_POST["organism_strain"];?>" ></span></td>
                       </tr>

                       </tbody>
                   </table>
               </div>
           </div>


           <div class="panel-heading" role="tab" id="heading-resource2">
               <h4 class="panel-title">Publication</h4>
           </div>
           <div id="collapse-resource2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource2">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 20%;"><strong> Title</strong></td>
                           <td><span><input type="text" name="publication_title" class="form-control" id="publication_title" placeholder="" value="<?php echo $_POST["publication_title"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Journal</strong></td>
                           <td><span><input type="text" name="publication_journal" class="form-control" id="publication_journal" placeholder="" value="<?php echo $_POST["publication_journal"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Authors</strong></td>
                           <td><input type="text" name="publication_authors" class="form-control" id="publication_authors" value="<?php echo $_POST["publication_authors"];?>" ></td>
                       </tr>
                       <tr>
                           <td><strong>Pubmed ID</strong></td>
                           <td><input type="text" name="publication_pmid" class="form-control" id="publication_pmid" value="<?php echo $_POST["publication_pmid"];?>" ></td>
                       </tr>
                       <tr>
                           <td><strong>Date</strong></td>
                           <td><input type="text" name='publication_date' class="form-control" id='publication_date' value="<?php echo $_POST['publication_date'];?>" ></td>
                       </tr>
                       </tbody>
                   </table>
               </div>
           </div>
           <div class="panel-heading" role="tab" id="heading-resource2">
               <h4 class="panel-title">
                   <a role="button">
                       Data Standard
                   </a>
               </h4>
           </div>

           <div id="collapse-resource2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource2">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 20%;"><strong> Name</strong></td>
                           <td><span><input type="text" name="datastandard_name" class="form-control" id="datastandard_name" placeholder="" value="<?php echo $_POST["datastandard_name"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Homepage</strong></td>
                           <td><span><input type="text" name="datastandard_homepage" class="form-control" id="datastandard_homepage" placeholder="" value="<?php echo $_POST["datastandard_homepage"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Identifier</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="A code uniquely identifiying an entity locally to a system or globally" ></button></td>
                           <td><input type="text" name="datastandard_id" class="form-control" id="datastandard_id" value="<?php echo $_POST["datastandard_id"];?>" ></td>
                       </tr>
                       <tr>
                           <td><strong>Identifier Scheme</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="Information about how identifiers are formed,maintained and minted" ></button></td>
                           <td><input type="text" name="datastandard_idscheme" class="form-control" id="datastandard_idscheme" value="<?php echo $_POST["datastandard_idscheme"];?>" ></td>
                       </tr>
                       </tbody>
                   </table>
               </div>
           </div>
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
                           <td style="width: 20%;"><strong> Name</strong></td>
                           <td><span><input type="text" name="datarepo_name" class="form-control" id="datarepo_name" placeholder="" value="<?php echo $_POST["datarepo_name"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Abbreviation</strong></td>
                           <td><span><input type="text" name="datarepo_abbr" class="form-control" id="datarepo_abbr" placeholder="" value="<?php echo $_POST["datarepo_abbr"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Identifier</strong></td>
                           <td><input type="text" name="datarepo_id" class="form-control" id="datarepo_id" value="<?php echo $_POST["datarepo_id"];?>" ></td>
                       </tr>
                       <tr>
                           <td><strong>Homepage</strong></td>
                           <td><input type="text" name="datarepo_homepage" class="form-control" id="datarepo_homepage" value="<?php echo $_POST["datarepo_homepage"];?>" ></td>
                       </tr>
                       </tbody>
                   </table>
               </div>
           </div>

           <div class="panel-heading" role="tab" id="heading-resource2">
               <h4 class="panel-title">Grant</h4>
           </div>
           <div id="collapse-resource2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource2">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 20%;"><strong> Name</strong></td>
                           <td><span><input type="text" name="grant_name" class="form-control" id="grant_name" placeholder="" value="<?php echo $_POST["grant_name"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Funder</strong>
                               <button type="button" class = "glyphicon glyphicon-question-sign" style="color:gray; border: none; background-color:#f9f9f9" data-toggle="popover" data-placement="right" data-content="The organization(s) which has awarded the funds supporting the project" ></button></td>
                           <td><span><input type="text" name="grant_funder" class="form-control" id="grant_funder" placeholder="" value="<?php echo $_POST["grant_funder"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Identifier</strong></td>
                           <td><input type="text" name="grant_id" class="form-control" id="grant_id" value="<?php echo $_POST["grant_id"];?>" ></td>
                       </tr>

                       </tbody>
                   </table>
               </div>
           </div>


            <div class="panel-heading" role="tab" id="heading-resource3">
               <h4 class="panel-title">
                   Organization
               </h4>
           </div>
           <div class="panel-collapse collapse in" role="tabpanel">
               <div class="panel-body" style="margin-bottom: -30px">
                   <table class="table table-striped">
                       <tbody>
                       <tr>
                           <td style="width: 20%;"><strong> Name</strong></td>
                           <td><span><input type="text" name="organization_name" class="form-control" id="organization_name" placeholder="" value="<?php echo $_POST["organization_name"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Homepage</strong></td>
                           <td><span><input type="text" name="organization_homepage" class="form-control" id="organization_homepage" placeholder="" value="<?php echo $_POST["organization_homepage"];?>" ></span></td>
                       </tr>
                       <tr>
                           <td><strong>Abbreviation</strong></td>
                           <td><input type="text" name="organization_abbr" class="form-control" id="organization_abbr" value="<?php echo $_POST["organization_abbr"];?>" ></td>
                       </tr>

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
</script>


