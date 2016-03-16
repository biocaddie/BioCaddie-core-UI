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
                        <td><strong><?php echo $results[$repository->datasource_headers[0]]; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td><?php echo $results["dataItem.ID"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Data Types:</strong></td>
                        <td><?php echo $results["dataItem.dataTypes"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td><div class="comment more"><?php echo $results["dataItem.description"]; ?></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>