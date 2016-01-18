<?php include './templates/header.php';?>

<div class="container">
  <?php require_once("./templates/search.php"); ?>

  <div class="row">
    <!--Left column-->
    <div class="col-md-3" id="leftCol">
        <?php 
        require_once("./datasource/repo.php");
        require_once("./datasource/datatype.php");     
        require_once("./templates/feedback.php");    
        ?>          
    </div>  
            
    <div class="col-md-6">
        <?php require_once("./datasource/result.php");?>
    </div>
     
     <!-- end show result part -->   
     <div class="col-md-3">
        <?php 
           require_once ("./templates/recent_activity/recent_activity.php");
          // require_once './datasource/pubmed/pubmed_publication.php';
          // $pubmedPublication =new PubmedPublication;
          // $pubmedPublication -> show_publication($_REQUEST["query"]);
           require_once("./datasource/related.php");
        ?>    
    </div>   
  </div><!--/.row-->
</div><!--/.container-->

<?php include './templates/footer.php';?>

<!--Prevent user from submitting empty form-->
<script>
  function empty() {
    var x;
    x = document.getElementById("query").value;
    if (x == "") {
        location.reload();
        return false;
    };
}
</script>