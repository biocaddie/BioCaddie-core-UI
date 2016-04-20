<?php
function displayResult($service) {
    $repository = $service->getCurrentRepository();
    $results = $service->getSearchResults();


    ?>
    <div class="dataset-info">
        <div class="heading">
            <div>
                <span class="title">
                    <img style="height: 50px;margin: 5px" src="./img/repositories/<?php echo $repository->id; ?>.png">
                </span>
                <div class="pull-right" style="margin: 15px 5px">
                    <?php include dirname(__FILE__) . '../../share.php'; ?>
                </div>
            </div>

            <table class="table table-default">
                <tbody>
                <tr>
                    <td style="width: 20%;"><strong>Title:</strong></td>
                    <td><strong><?php echo $results['dataset.title']; ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Creator:</strong></td>
                    <td><?php echo $results["dataset.creator"]; ?></td>
                </tr>
                <tr>
                    <td><strong>License:</strong></td>
                    <td><?php echo $results["dataset.license"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Date LastUpdate:</strong></td>
                    <td><?php echo date("m-d-Y", strtotime($results["dataset.dateLastUpdate"])); ?></td>
                </tr>
                <tr>
                    <td><strong>Status: </strong></td>
                    <td><?php echo $results["dataset.status"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Download URL:</strong></td>
                    <td><?php echo "<a class='hyperlink' target=_blank href='".$results["dataset.downloadURL"]."'>".$results["dataset.downloadURL"]."</a>"; ?></td>
                </tr>
                <tr>
                    <td><strong>Size:</strong></td>
                    <td><?php echo $results["dataset.size"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Related Dataset:</strong></td>
                    <td><?php echo $results["dataset.relatedDataset"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Disease: </strong></td>
                    <td><?php echo $results["disease.name"]; ?></td>
                </tr>

                </tbody>
            </table>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="heading-study">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-target="#collapse-study" href="#collapse-study" aria-expanded="true" aria-controls="collapse-study">
                        <i class="fa fa-chevron-up"></i>
                        Organism
                    </a>
                </h4>
            </div>
            <div id="collapse-study" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-study">
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong>Name</strong></td>
                            <td><?php echo $results["organism.name"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Scientific Name</strong></td>
                            <td><?php echo $results["organism.scientificName"]; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="heading-study">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-target="#collapse-study" href="#collapse-study" aria-expanded="true" aria-controls="collapse-study">
                        <i class="fa fa-chevron-up"></i>
                        Repository
                    </a>
                </h4>
            </div>
            <div id="collapse-study" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-study">
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong>Name</strong></td>
                            <td><?php echo $results["dataRepository.name"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Abbreviation</strong></td>
                            <td><?php echo $results["dataRepository.abbreviation"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Homepage:</strong></td>
                            <td><?php echo "<a class='hyperlink' target=_blank href='".$results["dataRepository.homePage"]."'>".$results["dataRepository.homePage"]."</a>"; ?></td>
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
                        Organization
                    </a>
                </h4>
            </div>
            <div id="collapse-treatment" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-treatment">
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong>Name</strong></td>
                            <td><?php echo $results["organization.name"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Abbreviation</strong></td>
                            <td><?php echo $results["organization.abbreviation"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Homepage:</strong></td>
                            <td><?php echo "<a class='hyperlink' target=_blank href='".$results["organization.homePage"]."'>".$results["organization.homePage"]."</a>"; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>


<?php } ?>