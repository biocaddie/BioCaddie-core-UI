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
                        <td><strong>ID: </strong></td>
                        <td><?php echo $results["dataItem.ID"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Keywords:</strong></td>
                        <td><?php echo $results["dataItem.keywords"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td><div class="comment more"><?php echo $results["dataItem.description"]; ?></div></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
            foreach (array_keys($results) as $key) {
                if ($key !== $repository->datasource_headers[0] && strpos($key, 'dataItem') === false && strlen($results[$key]) == 0) {
                    $metadataCount = 0;
                    foreach (array_keys($results) as $element) {
                        if ($element != $repository->datasource_headers[0] && strpos($element, 'dataItem') == false) {
                            if (strlen($results[$element]) != 0 && substr(strtolower($element), 0, strlen($key)) == strtolower($key)) {
                                $metadataCount++;
                            }
                        }
                    }
                    if ($metadataCount > 0) {
                        ?> 
                        <div class="panel panel-info">
                            <div class="panel-heading" role="tab" id="heading-<?php echo $key; ?>">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-target="#collapse-<?php echo $key; ?>" href="#collapse-<?php echo $key; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $key; ?>">
                                        <i class="fa fa-chevron-up"></i>
                                        <?php echo $repository->core_fields_show_name[$key]; ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse-<?php echo $key; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-<?php echo $key; ?>">
                                <div class="panel-body">
                                    <table class="table table-striped">
                                        <tbody>  
                                            <?php
                                            foreach (array_keys($results) as $element) {
                                                if ($element != $repository->datasource_headers[0] && strpos($element, 'dataItem') == false) {
                                                    if (strlen($results[$element]) != 0 && substr(strtolower($element), 0, strlen($key)) == strtolower($key)) {
                                                        ?>
                                                        <tr>
                                                            <td style="width: 20%;"><strong><?php echo $repository->core_fields_show_name[$element]; ?>:</strong></td>

                                                            <td>
                                                                <?php
                                                                if (strpos($results[$element], ':')) {
                                                                    if($repository->core_fields_show_name[$element]=='PMID'){?>

                                                                        <a class='hyperlink' href="https://www.ncbi.nlm.nih.gov/pubmed/<?php echo substr($results[$element],5);?>" target="_blank"><?php echo substr($results[$element],5);?></a>
                                                                    <?php }else{
                                                                    echo str_replace(',', '<br />', $results[$element]);
                                                                } }else {
                                                                    echo $results[$element];
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>   
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>
<?php } ?>
