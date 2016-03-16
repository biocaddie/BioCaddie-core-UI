<?php
require_once dirname(__FILE__) . '/trackactivity.php';
require_once dirname(__FILE__) . '/search/Repositories.php';
$repositoryClass = new Repositories();
$repositories = $repositoryClass->getRepositories();
$nameIDs = [];
foreach($repositories as $repository){
    $name = $repository->whole_name;
    if(strlen($repository->whole_name)==0) {
        $name = $repository->show_name;
    }
    $id = $repository->id;
    $mainpage=$repository->source_main_page;
    $nameIDs[$id]=[$name,$mainpage];
}
$n=1;
?>

<?php include dirname(__FILE__) . '/views/header.php'; ?>


<div class="page-container" style="margin-bottom: 20px">

    <div class="container">
    <table>
   <?php foreach($nameIDs as $id=>$x): ?>
       <?php $name = $x[0];$mainpage = $x[1];?>
       <tr>
           <td><strong><?php echo $n.'.';$n=$n+1;?></strong></td>
           <td>
           <img style="height: 40px ;width:100px; margin :20px" src="./img/repositories/<?php echo $id; ?>.png">
           </td>
           <td><strong><a class="hyperlink" target="_blank" href="<?php echo $mainpage;?>"><?php echo $name?></a></strong></td>
       </tr>
   <?php endforeach;?>
    </table>
    </div><!--/.container-->
</div><!--/.page-container-->


<?php include dirname(__FILE__) . '/views/footer.php'; ?>
