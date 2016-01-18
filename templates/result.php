<?php include './templates/header.php';?>

<div class="container">
  <?php require_once("./templates/search_result.php"); ?>

  <div class="row">
    <div class="col-md-3" id="leftCol">
      <?php require_once("./result/repo.php"); 
            require_once("./result/filter.php"); 
            require_once("./templates/feedback.php");  ?> 
    </div> 
    
    <div class="col-md-7">
      <?php require_once("./result/center-result.php"); ?>
    </div>    

    <div class="col-md-2">
      <?php require_once("./result/pilot.php"); ?>
    </div>
  </div>  <!--/.row-->        
</div> <!--/.container--> 
</div>

<?php include './templates/footer.php';?>