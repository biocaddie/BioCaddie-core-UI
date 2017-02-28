<?php

function displayResult($service) {
    $repository = $service->getCurrentRepository();
    $results = $service->getSearchResults();
    ?>
    <div class="dataset-info">
        <div class="heading">
            <div>                
                <span class="title">
                    <img src="./img/repositories/<?php echo $repository->id; ?>.png">

                </span>
                <div class="pull-right" style="margin: 15px 5px">
                    <?php include dirname(__FILE__) . '../../share.php'; ?>
                </div>
            </div>           

            <table class="table table-default">
                <tbody>
                    <tr>
                        <td style="width: 20%;"><strong>Title:</strong></td>
                        <td><strong><?php echo $results[$repository->datasource_headers[0]]; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td><?php echo $results["dataset.ID"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td><div class="comment more">
                             <?php   echo $results["dataset.description"];?>
                         </div></td>


                    </tr>
                </tbody>
            </table>
        </div>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-dataset">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-dataset" href="#collapse-dataset" aria-expanded="true" aria-controls="collapse-dataset">
                            <i class="fa fa-chevron-up"></i>
                            Dataset
                        </a>
                    </h4>
                </div>
                <div id="collapse-dataset" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-dataset">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td style="width: 20%;"><strong>Data Type:</strong></td>
                                    <td><?php echo $results["dataset.dataType"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Date Modified:</strong></td>
                                    <td><?php echo $results["dataset.dateModified"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Date Released:</strong></td>
                                    <td><?php echo $results["dataset.dateReleased"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Date Submitted:</strong></td>
                                    <td><?php echo $results["dataset.dateSubmitted"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Download URL:</strong></td>
                                    <td><a class="hyperlink" href="<?php echo $results["dataset.downloadURL"];?>"><?php echo $results["dataset.downloadURL"];?></a> </td>
                                </tr>
                                <tr>
                                    <td><strong>Keywords:</strong></td>
                                    <td><?php echo $results["dataset.keywords"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Provider:</strong></td>
                                    <td><?php echo $results["dataset.provider"]; ?></td>
                                </tr>
                            </tbody>   
                        </table>
                    </div>
                </div>
            </div>




            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-acquisition">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-acquisition" href="#collapse-acquisition" aria-expanded="true" aria-controls="collapse-acquisition">
                            <i class="fa fa-chevron-up"></i>
                            Data Acquisition
                        </a>
                    </h4>
                </div>
                <div id="collapse-acquisition" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-acquisition">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>ID:</strong></td>
                                <td><?php echo $results["dataAcquisition.ID"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Title:</strong></td>
                                <td><?php echo $results["dataAcquisition.title"]; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-repo">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-repo" href="#collapse-repo" aria-expanded="true" aria-controls="collapse-repo">
                            <i class="fa fa-chevron-up"></i>
                            Repository
                        </a>
                    </h4>
                </div>
                <div id="collapse-repo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-repo">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>ID:</strong></td>
                                <td><?php echo $results["dataRepository.ID"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Homepage:</strong></td>
                                <td><?php echo $results["dataRepository.homePage"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td><?php echo $results["dataRepository.name"]; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-organization">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-organization" href="#collapse-organization" aria-expanded="true" aria-controls="collapse-organization">
                            <i class="fa fa-chevron-up"></i>
                            Organization
                        </a>
                    </h4>
                </div>
                <div id="collapse-organization" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-organization">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>ID:</strong></td>
                                <td><?php echo $results["organization.ID"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Homepage:</strong></td>
                                <td><?php echo $results["organization.homePage"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td><?php echo $results["organization.name"]; ?></td>
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

            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-organism">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-organism" href="#collapse-organism" aria-expanded="true" aria-controls="collapse-organism">
                            <i class="fa fa-chevron-up"></i>
                            Organism
                        </a>
                    </h4>
                </div>
                <div id="collapse-organism" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-organism">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Name:</strong></td>
                                <td><?php echo $results["organism.name"]; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-treatment">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-treatment" href="#collapse-treatment" aria-expanded="true" aria-controls="collapse-treatment">
                            <i class="fa fa-chevron-up"></i>
                            Treatment
                        </a>
                    </h4>
                </div>
                <div id="collapse-treatment" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-treatment">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Title:</strong></td>
                                <td><?php echo $results["treatment.title"]; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
<?php } ?>