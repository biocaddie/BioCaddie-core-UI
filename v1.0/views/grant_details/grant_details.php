<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 1/20/16
 * Time: 11:02 AM
 */
require_once dirname(__FILE__) . '/../../search/PubmedGrantService.php';

function displayResult($grants_details) { ?>
    <div class="dataset-info">
        <div class="heading">
            <div align="center">
                <span class="title"><br>Grant Support <br></span>
                <br>
            </div>
            <?php $i=0;?>
               <?php   foreach(array_keys($grants_details) as $key){ ?>
                  <?php $i=$i+1; ?>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-info">
                        <div class="panel-heading" role="tab" id="heading-resource">
                            <h4 class="panel-title">
                                   <?php if(sizeof($grants_details[$key])>0){ ?>
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-resource<?php echo $i;?>" aria-expanded="true" aria-controls="collapse-resource">
                                      <i class="fa fa-chevron-up"></i>
                                   <?php }  ?>
                                <?php echo $key ;?>
                                </a>
                            </h4>
                        </div>
                        <?php if(sizeof($grants_details[$key])>0){ ?>
                          <div id="collapse-resource<?php echo $i;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-resource">

                            <?php foreach($grants_details[$key] as $results){?>
                                    <div class="panel-body">
                                        <table class="table table-striped">
                                            <tbody>
                                            <tr>
                                                <td><strong>Title:</strong></td>

                                                    <td><strong><a class='hyperlink' href="https://projectreporter.nih.gov/project_info_details.cfm?aid=<?php echo $results["ID"];?>" target="_blank"><?php echo $results["title"]; ?></a></strong></td>

                                            </tr>
                                            <tr>
                                                <td><strong>Project Num:</strong></td>
                                                <td><?php echo $results["project_num"]; ?></td>
                                            </tr>

                                            <tr>
                                                <td><strong>PI:</strong></td>
                                                <td><?php echo $results["PI"]; ?></td>
                                            </tr>

                                            <tr>
                                                <td><strong>ORG Name:</strong></td>
                                                <td><?php echo $results["ORG_name"]; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>IC Name:</strong></td>
                                                <td><?php echo $results["IC_name"]; ?></td>
                                            </tr>
                                            <!--<tr>
                                                <td><strong>Total Cost:</strong></td>
                                                <?php if(strlen($results["total_cost"])>0){?>
                                                    <td><?php //echo "$".$results["total_cost"]; ?></td>
                                                <?php }
                                                     else { ?>
                                                    <td><?php //echo $results["total_cost"]; ?></td>
                                                <?php } ?>
                                            </tr>-->
                                            <tr>
                                                <td><strong>Fiscal Year:</strong></td>
                                                <td><?php echo $results["FY"]; ?></td>
                                            </tr>
                                            <!--<tr>
                                                <td><strong>ID:</strong></td>
                                                <td><a href="https://projectreporter.nih.gov/project_info_details.cfm?aid=<?php //echo $results["ID"];?>" target="_blank"><?php //echo $results["ID"]; ?></a></td>
                                            </tr>-->
                                            </tbody>
                                        </table>
                                    </div>

                            <?php } ?>
                          </div>
                       <?php }?>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
<?php }

function partialGrantDetial($pubgrant_service){
    /* $result = $service->getSearchResults();
     if (isset($result['citation.PMID'])) {
         $pmid= substr($result['citation.PMID'],5);
     } else {
         return ;
     }*/
    //$pubgrant_serivce = new PubmedGrantService();
    //$pubgrant_service->getPubmedGrant();
    $grants_details = $pubgrant_service->search_grant_info();
    displayResult($grants_details);
}
/*function show_link_or_not($ID){
    //some project are only in database but not in Reporter search result, here to check if show the link or not
    $url= "https://projectreporter.nih.gov/project_info_details.cfm?aid=".$ID;
    $resultPage = file_get_contents($url);

    if(strpos($resultPage,"This project doesn't exist in RePORTER yet. Please check your query or check back after the start date. ")!==false){
        echo false;
    }
    else{
        echo true;
    }
}*/
?>