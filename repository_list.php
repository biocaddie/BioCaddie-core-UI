<?php
require_once 'Model/TrackActivity.php';
require_once dirname(__FILE__) . '/Model/Repositories.php';
$repositoryClass = new Repositories();
$repositories = $repositoryClass->getRepositories();

$nameIDs = [];
foreach($repositories as $repository){
    $name = $repository->wholeName;
    if(strlen($repository->wholeName)==0) {
        $name = $repository->repoShowName;
    }
    $id = $repository->id;
    $nameIDs[$id]=[$name,$repository->description];
}
$nameIDs=sort_by_name($nameIDs);
$n=1;


?>

<?php include 'views/header.php'; ?>


<div class="page-container" style="margin-bottom: 20px">

    <div class="container">
    <table class="table-striped">
   <?php foreach($nameIDs as $id=>$x): ?>
       <?php $name = $x[0];?>
       <tr>
           <td><strong><?php echo $n.'.';$n=$n+1;?></strong></td>
           <td>
           <img style="height: 40px ;width:100px; margin :20px" src="./img/repositories/<?php echo $id; ?>.png">
           </td>
           <td><strong><a class="hyperlink" href="<?php echo "search-repository.php?query=%20&searchtype=data&repository=".$id;?>"><?php echo $name?></a></strong></td>
           <td><?php echo $x[1]?></td>
       </tr>
   <?php endforeach;?>
    </table>
    </div><!--/.container-->
</div><!--/.page-container-->


<?php include 'views/footer.php'; ?>
