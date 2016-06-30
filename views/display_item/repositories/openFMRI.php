<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/26/16
 * Time: 11:04 AM
 */

function displayResult($service) {
    $repository = $service->getCurrentRepository();
    $results = $service->getSearchResults();

    ?>
    <div class="dataset-info">
        <div class="heading">
            <div>
                <span class="title">
                   <!-- <a class="repository-logo" href="<?php echo $repository->source_main_page; ?>" target="_blank"><img style="height: 60px" src="./img/repositories/<?php echo $repository->id; ?>.png"></a>-->
                    <a class="repository-logo"><img style="height: 60px" src="./img/repositories/<?php echo $repository->id; ?>.png"></a>
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
                    <td><strong><?php echo $results['dataset.title']; ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Download URL:</strong></td>
                    <td><?php echo '<a class="hyperlink" href="'.$results["dataset.downloadURL"].'">'.$results["dataset.downloadURL"]."</a>"; ?></td>
                </tr>
                <tr>
                    <td><strong>Description:</strong></td>
                    <td><div><?php echo $results["dataset.description"]; ?></div></td>
                </tr>
                <tr>
                    <td><strong>ID:</strong></td>
                    <td><?php echo $results["dataset.id"]; ?></td>
                </tr>
                <tr>
                    <td><strong>Data Acquisition:</strong></td>
                    <td><?php echo $results["dataAcquisition.title"]; ?></td>
                </tr>
                </tbody>
            </table>
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
                            <td style="width: 20%;"><strong>Name</strong></td>
                            <td><?php echo $results["organism.name"]; ?></td>
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