
<?php

if (sizeof($_POST)>1) {
    $showing_label = [
        'NAME'=>"Submitter's Name",
        'EMAIL'=>"Submitter Email",
        'ORGANIZATION'=>"Submitter's Organization",
        'address'=>"Submitter's address",
        "title"=>'Dataset Title',
        "description"=>'Dataset Description',
        'id'=>"Dataset Identifier",
        "datatype"=>"Dataset datatype",
        "downloadURL"=>"Dataset download URL",
        "Keywords"=>"Dataset Keywords",
        "releasedate"=>"Dataset Release Date",
        "pubtitle"=>"Publication Title",
        "journal"=>"Publication Journal",
        "author"=>"Authors",
        "pmid"=>"Pubmed ID",
        "organism"=>"Organism Name",
        "Strain"=>"Organism Strain",
        "orgname"=>"Organization Name",
        "homepage"=>"Organization Homepage",
        "abbreviation"=>"Organization Abbreviation"
    ];
    $index_label=[
        "title"=>'dataset.title',
        "description"=>'dataset.description',
        'id'=>"dataset.ID",
        "datatype"=>"dataset.datatype",
        "downloadURL"=>"dataset.downloadURL",
        "Keywords"=>"dataset.keywords",
        "releasedate"=>"dataset.dataReleased",
        "pubtitle"=>"publication.title",
        "journal"=>"publication.journal",
        "author"=>"publication.authors",
        "pmid"=>"publication.pmid",
        "organism"=>"organism.name",
        "Strain"=>"organism.strain",
        "orgname"=>"organization.name",
        "homepage"=>"organization.homePage",
        "abbreviation"=>"organization.abbreviation"
    ];
    if(isset($_POST['overview'])){
        show_table($showing_label);
    }
    elseif(isset($_POST['submit'])){
        index_to_ES($index_label);
        echo '<script type="text/javascript">';
        echo 'alert("You submission has been received.Thank you!")';
        echo '</script>';
    }
}
function index_to_ES($index_label){
    global $es;
    $body = [];
    foreach(array_keys($_POST) as $key){
        if(strlen($_POST[$key])>0 && array_key_exists($key,$index_label)){
            $newkey = $index_label[$key];
            $ids = explode('.',$newkey);
            $body[$ids[0]][$ids[1]]=$_POST[$key];
        }
    }
    $params = [
        'index' => 'datamed',
        'type' => 'dataset',
        'body' => $body
    ];
    $es->index($params);
}
function show_table($showing_label){?>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <h4>Please review your dataset information before submission</h4>
        <div class="panel panel-info">
            <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" >
            <table class="table table-striped">
                <tbody>
            <?php foreach(array_keys($_POST) as $key){?>
                  <?php if(strlen($_POST[$key])>0){?>
            <tr>
                <td style="width: 20%;"><?php echo $showing_label[$key];?></td>
                <td><?php echo $_POST[$key];?></td>
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
                            <td style="width: 20%;"><strong>* Name:</strong></td>
                            <td><span><input type="text" name="NAME" class="form-control" id="pwd" placeholder="Your name" value="<?php echo $_POST['NAME'];?>" required></span></td>
                        </tr>
                        <tr>
                            <td><strong>* Email:</strong></td>
                            <td><input type="email" name="EMAIL" class="form-control" id="email" placeholder="Your email"  value="<?php echo $_POST['EMAIL'];?>"required></td>
                        </tr>
                        <tr>
                            <td><strong>* Submitting organization</strong></td>
                            <td><input type="text" name="ORGANIZATION" class="form-control" id="subject" value="<?php echo $_POST['ORGANIZATION'];?>" required ></td>
                        </tr>
                        <tr>
                            <td><strong>* Adress:</strong></td>
                            <td><input type="text" name="address" class="form-control" id="address" value="<?php echo $_POST['address'];?>" required ></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
       <div class="panel panel-info" id="accordion1">
            <div class="panel-heading" role="tab" id="heading-resource1">
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
                            <td style="width: 20%;"><strong>* Title:</strong></td>
                            <td><span><input type="text" name="title" class="form-control" id="title" placeholder="" value="<?php echo $_POST['title'];?>" required></span></td>
                        </tr>
                        <tr>
                            <td><strong>* Description:</strong></td>
                            <td><textarea type="text" name="description" class="form-control" id="description" rows="4" required><?php echo $_POST['description'];?></textarea></td>
                        </tr>
                        <tr>
                            <td><strong>Identifier</strong></td>
                            <td><input type="text" name="id" class="form-control" id="id" value="<?php echo $_POST['id'];?>" ></td>
                        </tr>
                        <tr>
                            <td><strong>Datatype</strong></td>
                            <td><input type="text" name="datatype" class="form-control" id="datatype" value="<?php echo $_POST['datatype'];?>" ></td>
                        </tr>
                        <tr>
                            <td><strong>Download URL:</strong></td>
                            <td><input type="text" name="downloadURL" class="form-control" id="downloadURL" value="<?php echo $_POST['downloadURL'];?>" ></td>
                        </tr>
                        <tr>
                            <td><strong>Keywords:</strong></td>
                            <td><input type="text" name="Keywords" class="form-control" id="Keywords" value="<?php echo $_POST['Keywords'];?>" ></td>
                        </tr>
                        <tr>
                            <td><strong>Release Date:</strong></td>
                            <td><input type="text" name="releasedate" class="form-control" id="releasedate" value="<?php echo $_POST['releasedate'];?>" ></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-heading" role="tab" id="heading-resource2">
                <h4 class="panel-title">
                    <a role="button">
                        Publication
                    </a>
                </h4>
            </div>
            <div id="collapse-resource2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource2">
                <div class="panel-body" style="margin-bottom: -30px">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong> Title:</strong></td>
                            <td><span><input type="text" name="pubtitle" class="form-control" id="pubtitle" placeholder="" value="<?php echo $_POST['pubtitle'];?>" ></span></td>
                        </tr>
                        <tr>
                            <td><strong>Journal:</strong></td>
                            <td><span><input type="text" name="journal" class="form-control" id="journal" placeholder="" value="<?php echo $_POST['journal'];?>" ></span></td>
                        </tr>
                        <tr>
                            <td><strong>Authors</strong></td>
                            <td><input type="text" name="author" class="form-control" id="author" value="<?php echo $_POST['author'];?>" ></td>
                        </tr>
                        <tr>
                            <td><strong>Pubmed ID:</strong></td>
                            <td><input type="text" name="pmid" class="form-control" id="pmid" value="<?php echo $_POST['pmid'];?>" ></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-heading" role="tab" id="heading-resource4">
                <h4 class="panel-title">
                    <a role="button">
                        Organism
                    </a>
                </h4>
            </div>
            <div id="collapse-resource4" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource4">
                <div class="panel-body" style="margin-bottom: -30px">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong> Name:</strong></td>
                            <td><span><input type="text" name="organism" class="form-control" id="organism" placeholder="" value="<?php echo $_POST['organism'];?>" ></span></td>
                        </tr>
                        <tr>
                            <td><strong>Strain:</strong></td>
                            <td><span><input type="text" name="Strain" class="form-control" id="Strain" placeholder="" value="<?php echo $_POST['Strain'];?>" ></span></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-heading" role="tab" id="heading-resource3">
                <h4 class="panel-title">
                    <a role="button">
                        Organization
                    </a>
                </h4>
            </div>
           <div class="panel-collapse collapse in" role="tabpanel">
                <div class="panel-body" style="margin-bottom: -30px">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong> Name:</strong></td>
                            <td><span><input type="text" name="orgname" class="form-control" id="orgname" placeholder="" value="<?php echo $_POST['orgname'];?>" ></span></td>
                        </tr>
                        <tr>
                            <td><strong>Homepage:</strong></td>
                            <td><span><input type="text" name="homepage" class="form-control" id="homepage" placeholder="" value="<?php echo $_POST['homepage'];?>" ></span></td>
                        </tr>
                        <tr>
                            <td><strong>Abbreviation</strong></td>
                            <td><input type="text" name="abbreviation" class="form-control" id="abbreviation" value="<?php echo $_POST['abbreviation'];?>" ></td>
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





