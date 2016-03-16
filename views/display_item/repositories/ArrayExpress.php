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
                        <td style="width: 20%;"><strong>Full Title:</strong></td>
                        <td><strong><?php echo $results[$repository->datasource_headers[0]]; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td><?php echo $results["dataItem.ID"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Experiment Type:</strong></td>
                        <td><?php echo $results["dataItem.experimentType"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td><div class="comment more">
                             <?php   echo $results["dataItem.description"];?>
                         </div></td>


                    </tr>
                </tbody>
            </table>
        </div>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-resource">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-resource" aria-expanded="true" aria-controls="collapse-resource">
                            <i class="fa fa-chevron-up"></i>
                            Dataset
                        </a>
                    </h4>
                </div>
                <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td style="width: 20%;"><strong>Submission Date:</strong></td>
                                    <td><?php echo $results["dataItem.submissionDate"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Release Date:</strong></td>
                                    <td><?php echo $results["dataItem.releaseDate"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Last Update:</strong></td>
                                    <td><?php echo $results["dataItem.lastUpdateDate"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Experiment Species:</strong></td>
                                    <td><?php echo $results["organism.experiment.species"]; ?></td>
                                </tr>
                            </tbody>   
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
<?php } ?>