<?php

$pageTitle = "Data Types";

require_once 'Model/TrackActivity.php';
include 'views/header.php';
require_once dirname(__FILE__) . '/config/datasources.php';
require_once 'Model/Repositories.php';

$datatypesMapping = getDatatypesMapping();
$repositoryClass = new Repositories();
$repositories = $repositoryClass->getRepositories();

$mapIndexToName = [];



foreach($repositories as $repository){
    $mapIndexToName[$repository->index] = $repository->repoShowName;
}

?>
<div class="container">
    <div class="row">

<?php
$count = 0;
foreach($datatypesMapping as $type=>$repos){
    $count++;
    ?>
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <strong><?php echo $type?> </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>

                        <?php foreach($repos as $repo){
                            ?>

                        <tr>
                            <td><?php echo $mapIndexToName[$repo];?></td>
                        </tr>
                            <?php
                        }?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    if($count%4 == 0){
        echo "</div><div class='row'>";
    }
    } ?>


</div>

<?php include 'views/footer.php'; ?>
