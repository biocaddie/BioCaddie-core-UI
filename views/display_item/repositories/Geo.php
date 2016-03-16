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
                    <?php include dirname(__FILE__) . '/../../share.php'; ?>
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
                        <td><?php echo $results['dataItem.ID']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td><?php echo $results["dataItem.Type"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td><div class="comment more"><?php echo $results["dataItem.description"]; ?></div></td>
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
                            Data Resource
                        </a>
                    </h4>
                </div>
                <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>                                
                                <tr>
                                    <td style="width: 20%;"><strong>Link:</strong></td>
                                    <td><?php echo $results["dataItem.link"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>GEO Accession:</strong></td>
                                    <td><?php echo $results["dataItem.geo_accession"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Platform:</strong></td>
                                    <td><?php echo $results["dataItem.platform"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Series:</strong></td>
                                    <td><?php echo $results["dataItem.series"]; ?></td>
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
                                    <td style="width: 20%;"><strong>Organism:</strong></td>
                                    <td><?php echo $results["dataItem.organism"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Source Name:</strong></td>
                                    <td><?php echo $results["dataItem.source_name"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Entry Type:</strong></td>
                                    <td><?php echo $results["dataItem.entry_type"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Assays:</strong></td>
                                    <td><?php echo $results["dataItem.assays"]; ?></td>
                                </tr>                                
                            </tbody>   
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
<?php } ?>