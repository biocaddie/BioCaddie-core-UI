<?php

function partialResult($service)
{
    $data = $service->getDisplayItemData();
    if($data['title']==null){
     $data['title'] = $data["ID"];
    }

    ?>
    <div class="dataset-info">
        <div class="heading">
            <div>
                <span  class="title">
                    <img style="height: 50px" src="./img/repositories/<?php echo $data['repo_id']; ?>.png">
                </span>

            </div>

            <table class="table table-default">
                <tbody>
                <tr>
                    <td style="width: 20%;"><strong>Title:</strong></td>
                    <td><strong><?php echo $data['title'][1] ;?></strong>
                            <?php if(strlen($data['title'][2])>0){?>
                             <a class="hyperlink" target="_blank" href="<?php echo $data['title'][2]; ?>"><?php echo trim($data['logo']); ?></a>
                           <?php } ?>


                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php foreach ($data['show_order'] as $subtitle): ?>
            <?php if (is_empty_field($data[$subtitle])){continue;}?>
        <div class="panel-group" id="accordion-<?php echo  $subtitle; ?>" role="tablist" aria-multiselectable="true">
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-dataset-<?php echo $subtitle; ?>">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent = '#accordion-<?php echo  $subtitle; ?>' data-target="#collapse-dataset-<?php echo $subtitle; ?>"
                           href="#collapse-dataset-<?php echo $subtitle; ?>"
                           aria-expanded="true" aria-controls="collapse-dataset-<?php echo $subtitle; ?>">
                            <i class="fa fa-chevron-up"></i>
                            <?php echo ucfirst(preg_replace("/([a-z])([A-Z])([a-z])/", "$1 $2$3", $subtitle)); ?>
                        </a>
                    </h4>
                </div>

                <div id="collapse-dataset-<?php echo $subtitle; ?>" class="panel-collapse collapse in" role="tabpanel"
                     aria-labelledby="heading-dataset-<?php echo $subtitle; ?>">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <?php for ($i = 0; $i < sizeof($data[$subtitle]); $i++):?>
                                <?php if(strlen($data[$subtitle][$i][1])==0 or trim($data[$subtitle][$i][1])===',' or trim($data[$subtitle][$i][1])==='NULL'){continue;}?>
                                <tr>
                                    <td style="width: 20%;"><strong><?php echo $data[$subtitle][$i][0] ?>:</strong></td>
                                    <?php if(strlen(($data[$subtitle][$i][2]))>0):?>
                                        <td> <a class='hyperlink' target="_blank" href="<?php echo $data[$subtitle][$i][2]; ?>"><?php echo $data[$subtitle][$i][1]; ?></a></td>
                                    <?php else:?>
                                        <td><div class="comment more"> <?php echo $data[$subtitle][$i][1]; ?></div></td>
                                    <?php endif;?>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>



        </div>
        <?php endforeach; ?>
    </div>


    <?php } ?>
