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
                    <img style="height: 25px;margin: 10px" src="./img/repositories/<?php echo $repository->id; ?>.png">
                </span>
                <div class="pull-right" style="margin: 15px 5px">
                    <?php include dirname(__FILE__) . '../../share.php'; ?>
                </div>
            </div>

            <table class="table table-default">
                <tbody>
                    <tr>
                        <td style="width: 20%;"<strong>Title:</strong></td>
                        <td><strong><?php echo $results['Dataset.briefTitle']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>NCT ID:</strong></td>
                        <td><?php echo $results["DataSet.identifier"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Creator:</strong></td>
                        <td><?php echo $results["Dataset.creator"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td><div class="comment more">
                            <?php echo $results["Dataset.description"]; ?>
                        </div></td>
                    </tr>
                    <tr>
                        <td><strong>Deposition Date:</strong></td>
                        <td><?php echo $results["Dataset.depositionDate"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Verification Date:</strong></td>
                        <td><?php echo $results["Dataset.verificationDate"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Has Expanded Access:</strong></td>
                        <td><?php echo $results["Dataset.has_expanded_access"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Keywords:</strong></td>
                        <td><?php echo $results["Dataset.keyword"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Release Date:</strong></td>
                        <td><?php echo $results["Dataset.releaseDate"]; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Is Fda Regulated:</strong></td>
                        <td><?php echo $results["Dataset.is_fda_regulated"]; ?></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="heading-study">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-target="#collapse-study" href="#collapse-study" aria-expanded="true" aria-controls="collapse-study">
                        <i class="fa fa-chevron-up"></i>
                        Study
                    </a>
                </h4>
            </div>
            <div id="collapse-study" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-study">
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Homepage</strong></td>
                                <td><?php echo '<a class="hyperlink" href="' . $results["Study.homepage"] . '">' . $results["Study.homepage"] . "</a>"; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Disease</strong></td>
                                <td><?php echo $results["Disease.name"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Study Type:</strong></td>
                                <td><?php echo $results["Study.studyType"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><?php echo $results["Study.status"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phase:</strong></td>
                                <td><?php echo $results["Study.phase"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phase:</strong></td>
                                <td><?php echo $results["Study.phase"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Location</strong></td>
                                <td><?php echo $results["Study.location"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Recruits</strong></td>
                                <td><?php
                    echo 'Maximum Age : ' . $results["Study.recruits.maximum_age"] . '<br>' .
                    'Minimum Age : ' . $results["Study.recruits.minimum_age"] . '<br>' .
                    'Gender : ' . $results["Study.recruits.gender"] . '<br>' .
                    'criteria' . $results["Study.recruits.criteria"] . '<br>';
                    ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="heading-treatment">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-target="#collapse-treatment" href="#collapse-treatment" aria-expanded="true" aria-controls="collapse-treatment">
                        <i class="fa fa-chevron-up"></i>
                        Treatment
                    </a>
                </h4>
            </div>
            <div id="collapse-treatment" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-treatment">
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Title</strong></td>
                                <td><?php echo $results["Treatment.title"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Description</strong></td>
                                <td><?php echo $results["Treatment.description"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Agent</strong></td>
                                <td><?php echo $results["Treatment.agent"]; ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="heading-others">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-target="#collapse-others" href="#collapse-others" aria-expanded="true" aria-controls="collapse-others">
                        <i class="fa fa-chevron-up"></i>
                        Others
                    </a>
                </h4>
            </div>
            <div id="collapse-others" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-others">
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td style="width: 20%;"><strong>Oversight info of Clinical Study</strong></td>
                                <td><?php echo $results["clinical_study.oversight_info"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Grant Funder</strong></td>
                                <td><?php echo $results["Grant.funder"]; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<?php } ?>