<?php

function displayResult($service) {
    $repository = $service->getCurrentRepository();
    $results = $service->getSearchResults();
    ?>
    <div class="dataset-info">
        <div class="heading">
            <div>                
                <span class="title">
                    <!--<a class="repository-logo" href="<?php echo $repository->source_main_page; ?>" target="_blank"><img src="./img/repositories/<?php echo $repository->id; ?>.png"></img></a>-->
                    <a class="repository-logo"><img style="height: 50px" src="./img/repositories/<?php echo $repository->id; ?>.png"></a>
                    <?php //echo $results[$repository->datasource_headers[0]]; ?>
                </span>
                <div class="pull-right" style="margin: 15px 5px">
                    <?php include dirname(__FILE__) . '../../share.php'; ?>
                </div>
            </div>

            <table class="table table-default">
                <tbody>
                <tr>
                    <td style="width: 20%;"><strong>Title:</strong></td>
                    <td><strong><?php echo $results["dataset.title"]; ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Cell Line:</strong></td>
                    <td><?php echo $results["cellLine.name"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Data Types:</strong></td>
                    <td><?php echo $results["dataset.dataTypes"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Biological Process:</strong></td>
                    <td><?php echo $results["biologicalProcess.name"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Dimension Name:</strong></td>
                    <td><?php echo $results["dimension.name"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Assay:</strong></td>
                    <td><?php echo $results["assay.name"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Dataset ID:</strong></td>
                    <td><?php echo $results["dataset.ID"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Protein Name:</strong></td>
                    <td><div class="comment more"><?php echo $results["protein.name"]; ?></div></td>
                </tr>
                <tr>
                    <td><strong>Internal Project Name:</strong></td>
                    <td><?php echo $results["internal.projectName"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Person:</strong></td>
                    <td><?php echo $results["person.name"]; ?></td>
                </tr>
                <tr>
                    <td><strong> download URL:</strong></td>
                    <td>
                        <a href="<?php echo $results["dataset.downloadURL"]; ?>">
                            <?php echo $results["dataset.downloadURL"]; ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td><strong> Release Date:</strong></td>
                    <td>
                        <?php if((bool)(strtotime($results["dataset.dateReleased"]))){
                          echo date("m-d-Y",strtotime($results["dataset.dateReleased"]));
                        }else{
                            echo $results["dataset.dateReleased"];
                        }?>

                        <?php //echo substr($results["dataset.dateReleased"],0,8); ?>
                        <?php //echo date("m-d-Y", strtotime($results["dataset.dateReleased"]))?>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-resource">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-resource" href="#collapse-resource" aria-expanded="true" aria-controls="collapse-resource">
                            <i class="fa fa-chevron-up"></i>
                            Organization.abbreviation
                        </a>
                    </h4>
                </div>
                <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Name:</strong></td>
                                <td><div class="comment more"><?php echo $results["organization.name"]; ?></div></td>
                            </tr>
                            <tr>
                                <td><strong>Abbreviation:</strong></td>
                                <td><?php echo $results["organization.abbreviation"]; ?></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
<?php } ?>