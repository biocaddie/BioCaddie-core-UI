<?php

$pageTitle = "Repository List";
require_once 'Model/TrackActivity.php';
require_once dirname(__FILE__) . '/Model/Repositories.php';
$repositoryClass = new Repositories();
$repositories = $repositoryClass->getRepositories();

$nameIDs = [];
$datacites = [];
$commonFunds = [];
$command_funds_names=['lincs','epigenomics'];
foreach ($repositories as $repository) {
    $name = $repository->wholeName;
    if (strlen($repository->wholeName) == 0) {
        $name = $repository->repoShowName;
    }
    $id = $repository->id;
    if (strpos($repository->index, 'datacite') === false) {
        if(in_array($repository->index,$command_funds_names)){
            $commonFunds[$id] = [$name, $repository->description];
        }
        else{
            $nameIDs[$id] = [$name, $repository->description];
        }

    } else {
        $datacites[$id] = [$name, $repository->description];
    }
}
$nameIDs = sort_by_name($nameIDs);
$datacites = sort_by_name($datacites);
$commonFunds = sort_by_name($commonFunds);
$n = 1;


?>

<?php include 'views/header.php'; ?>


<div class="page-container" style="margin-bottom: 20px">

    <div class="container">
        <table class="table-striped">
            <?php foreach ($nameIDs as $id => $x): ?>

                <?php $name = $x[0]; ?>
                <tr>
                    <td><strong><?php echo $n . '.';
                            $n = $n + 1; ?></strong></td>
                    <td>
                        <img style="height: 40px ;width:100px; margin :20px"
                             src="./img/repositories/<?php echo $id; ?>.png">
                    </td>
                    <td><strong><a class="hyperlink"
                                   href="<?php echo "search-repository.php?query=%20&searchtype=data&repository=" . $id; ?>"><?php echo $name ?></a></strong>
                    </td>
                    <td><?php echo $x[1] ?></td>
                </tr>
            <?php endforeach; ?>
            <tr><td></td>
               <th colspan="2"><h3>Common Fund Repositories</h3></th>

                <td><strong><h4>The following repositories are part of NIH commons:</h4></strong></td></tr>
            <?php foreach ($commonFunds as $id => $x): ?>

                <?php $name = $x[0]; ?>
                <tr>
                    <td></td>
                    <td><strong><?php echo $n . '.';
                            $n = $n + 1; ?></strong>

                        <img style="height: 40px ;width:100px; margin :20px"
                             src="./img/repositories/<?php echo $id; ?>.png">
                    </td>
                    <td><strong><a class="hyperlink"
                                   href="<?php echo "search-repository.php?query=%20&searchtype=data&repository=" . $id; ?>"><?php echo $name ?></a></strong>
                    </td>
                    <td><?php echo $x[1] ?></td>
                </tr>

            <?php endforeach; ?>
           <tr><td></td>
               <td><img style="height: 40px ;width:100px; margin :20px"
                    src="./img/repositories/datacite_logo.png"></td>
               <td><strong><h2>DataCite</h2> </strong></td>
            <td><strong><h4>The following repositories are ingested through DataCite:</h4></strong></td></tr>
            <?php foreach ($datacites as $id => $x): ?>

                <?php $name = $x[0]; ?>
                <tr>
                    <td></td>
                    <td><strong><?php echo $n . '.';
                            $n = $n + 1; ?></strong>

                        <img style="height: 40px ;width:100px; margin :20px"
                             src="./img/repositories/<?php echo $id; ?>.png">
                    </td>
                    <td><strong><a class="hyperlink"
                                   href="<?php echo "search-repository.php?query=%20&searchtype=data&repository=" . $id; ?>"><?php echo $name ?></a></strong>
                    </td>
                    <td><?php echo $x[1] ?></td>
                </tr>

            <?php endforeach; ?>
        </table>
    </div><!--/.container-->
</div><!--/.page-container-->


<?php include 'views/footer.php'; ?>
