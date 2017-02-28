<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 1/20/16
 * Time: 11:02 AM
 */
require_once dirname(__FILE__) . '/../../Model/PubmedGrantService.php';

function partialGrantDetial($pubgrant_service){

    $grants_details = $pubgrant_service->searchGrantInfo();

    ?>

    <div class="dataset-info">
        <div class="heading">
            <div align="center">
                <span class="title"><br>Grant Support <br></span>
                <br>
            </div>
            <?php $i=0;?>
               <?php   foreach(array_keys($grants_details) as $key){ ?>
                  <?php $i=$i+1; ?>
                <div class="panel-group" id="accordion<?php echo $i;?>" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-info">
                        <div class="panel-heading" role="tab" id="heading-resource<?php echo $i;?>">
                            <h4 class="panel-title">
                                   <?php if(sizeof($grants_details[$key])>0){ ?>
                                    <a role="button" data-toggle="collapse" data-parent="#accordion<?php echo $i;?>" href="#collapse-resource<?php echo $i;?>"  aria-controls="collapse-resource<?php echo $i;?>">
                                      <i class="fa fa-chevron-up"></i>
                                   <?php }  ?>
                                <?php echo $key ;?>
                                </a>
                            </h4>
                        </div>
                        <?php if(sizeof($grants_details[$key])>0){ ?>
                          <div id="collapse-resource<?php echo $i;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-resource<?php echo $i;?>">

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
                                                <td><strong>Organization Name:</strong></td>
                                                <td><?php echo $results["ORG_name"]; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Institute & Center Name:</strong></td>
                                                <td><?php echo $results["IC_name"]; ?></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Fiscal Year:</strong></td>
                                                <td><?php echo $results["FY"]; ?></td>
                                            </tr>

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
<?php } ?>