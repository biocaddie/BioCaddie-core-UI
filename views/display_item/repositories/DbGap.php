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
                        <td style="width: 20%;"><strong>Title:</strong></td>
                        <td><strong><?php echo $results[$repository->datasource_headers[0]]; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td><?php echo $results["IDName"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Topic:</strong></td>
                        <td><?php echo $results["topic"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Path:</strong></td>
                        <td><?php echo $results["path"]; ?></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <!--Dataset-->
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-resource">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-resource" href="#collapse-resource" aria-expanded="true" aria-controls="collapse-resource">
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
                                    <td style="width: 20%;"><strong>Stored In:</strong></td>
                                    <td>dbGap</td>
                                </tr>

                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td><?php echo $results["category"]; ?></td>
                                </tr>

                                <tr>
                                    <td><strong>Consent Type:</strong></td>
                                    <td><?php echo $results["ConsentType"]; ?></td>
                                </tr>
                                <!--<tr>
                                    <td><strong>Mesh Term:</strong></td>
                                    <td><?php echo $results["MESHterm"]; ?></td>
                                </tr>
                                -->
                                <tr>
                                    <td><strong>IRB:</strong></td>
                                    <td><?php echo $results["IRB"]; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Measurement:</strong></td>
                                    <td><?php echo $results["measurement"]; ?></td>
                                </tr>
                            </tbody>   
                        </table>
                    </div>
                </div>
            </div>
            <!--/. end of Dataset-->

            <!--Study-->
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-resource">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-study" href="#collapse-study" aria-expanded="true" aria-controls="collapse-resource">
                            <i class="fa fa-chevron-up"></i>
                            Study
                        </a>
                    </h4>
                </div>
                <div id="collapse-study" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Disease:</strong></td>
                                <td><?php echo $results["disease"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Cohort:</strong></td>
                                <td><?php echo $results["cohort"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td><?php echo $results["Type"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>platform:</strong></td>
                                <td><?php echo $results["platform"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Study Inclusion/Exclusion Criteria: </strong></td>
                                <td><div class="comment more"><?php
                                    $temp = str_replace('Study Inclusion/Exclusion Criteria ','',$results["inexclude"]);
                                    $temp = str_replace('  ','<br>',$temp);

                                    echo $temp;
                                    ?></div></td>
                            </tr>
                            <tr>
                                <td><strong>Study Description:</strong></td>
                                <td><div class="comment more"><?php
                                    $temp = str_replace('Study Description  ','',$results["desc"]);
                                    $temp = str_replace('  ','<br>',$temp);

                                    echo $temp;
                                   ?></div></td>
                            </tr>
                            <tr>
                                <td><strong>Study Attribution:</strong></td>
                                <td><div class="comment more"><?php
                                    $temp = str_replace('Study Attribution  ','',$results["attributes"]);
                                    $temp = str_replace('**','<br>',$temp);
                                    $temp = str_replace('*','<br>',$temp);

                                    echo $temp;

                                    ?></div></td>
                            </tr>
                            <tr>
                                <td><strong>Study History:</strong></td>
                                <td><div class="comment more"><?php

                                    $temp = str_replace('Study History  ','',$results["history"]);
                                    $temp = str_replace('  ','<br><br>',$temp);
                                    echo $temp; ?></div></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/. end of Study-->


            <!--Demographics-->
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-demographics">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-demographics" href="#collapse-demographics" aria-expanded="true" aria-controls="collapse-demographics">
                            <i class="fa fa-chevron-up"></i>
                            Demographics
                        </a>
                    </h4>
                </div>


            <div id="collapse-demographics" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-demographics">
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td style="width: 20%;"><strong>Demographics:</strong></td>
                            <td><?php echo $results["Demographics"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Age:</strong></td>
                            <td><?php echo $results["age"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Age Max:</strong></td>
                            <td><?php echo $results["AgeMax"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Age Min:</strong></td>
                            <td><?php echo $results["AgeMin"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Female Number:</strong></td>
                            <td><?php echo $results["FemaleNum"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Male Number:</strong></td>
                            <td><?php echo $results["MaleNum"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Other Gender Number:</strong></td>
                            <td><?php echo $results["OtherGenderNum"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Unknown Gender Number:</strong></td>
                            <td><?php echo $results["UnknownGenderNum"]; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Gender:</strong></td>
                            <td><?php echo $results["gender"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Geography:</strong></td>
                            <td><?php echo $results["geography"]; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            <!--/. end of Demographics-->

            <!--Phenotype-->
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-resource">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-target="#collapse-phenotype" href="#collapse-phenotype" aria-expanded="true" aria-controls="collapse-resource">
                            <i class="fa fa-chevron-up"></i>
                            Phenotype
                        </a>
                    </h4>
                </div>
                <div id="collapse-phenotype" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-resource">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Phenotype:</strong></td>
                                <td><div class="comment more"><?php echo str_replace(' phv','<br><br>phv',$results["phen"]); ?></div></td>
                            </tr>
                            <tr>
                                <td><strong>Phenotype CUI:</strong></td>
                                <td><?php echo $results["phenCUI"]; ?></td>
                            </tr>
                            <!--<tr>
                                <td><strong>phenID:</strong></td>
                                <td><?php echo $results["phenID"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>phenMap:</strong></td>
                                <td><?php echo $results["phenMap"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>phenName:</strong></td>
                                <td><?php echo $results["phenName"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Pheno Description:</strong></td>
                                <td><?php echo $results["phenDesc"]; ?></td>
                            </tr>-->
                            <tr>
                                <td><strong>Pheno Type:</strong></td>
                                <td><?php echo $results["phenType"]; ?></td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/. end of Phenotype-->
        </div>
    </div>
<?php } ?>