<?php

function displayResult($service) {
    $repository = $service->getCurrentRepository();
    $results = $service->getSearchResults();

    ?>
    <div class="dataset-info">
        <div class="heading">
            <div>                
                <span class="title">
                    <img style="height: 30px;" src="./img/repositories/<?php echo $repository->id; ?>.png">
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
                        <td><strong>Keywords:</strong></td>
                        <td><?php echo $results["dataItem.keywords"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>NCBI ID:</strong></td>
                        <td><?php echo $results["organism.target.ncbiID"]; ?></td>
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
                                    <td style="width: 20%;"><strong>Release Date:</strong></td>
                                    <td>
                                    <?php if((bool)(strtotime($results["dataItem.releaseDate"]))){
                                        echo date("m-d-Y",strtotime($results["dataItem.releaseDate"]));
                                    }else{
                                        echo $results["dataItem.releaseDate"];
                                    }?>
                                    </td>

                                </tr>

                                <tr>
                                    <td><strong>Organism:</strong></td>
                                    <td><?php echo $results["organism.target.species"]; ?></td>
                                </tr>

                                <tr>
                                    <td><strong>Description:</strong></td>

                                    <td><div class="comment more">
                                        <?php echo $results["dataItem.description"]; ?>
                                    </div></td>
                                </tr>
                            </tbody>   
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
<?php } ?>
<?php

?>