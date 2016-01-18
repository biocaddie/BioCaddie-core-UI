<?php include './templates/header.php';?>
<div class="container">
<!-- go back to previsous page-->
<!--<div class="row">
<a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="btn btn-info btn-primary">
  <span class="glyphicon glyphicon-arrow-left"></span> Previous page
</a>
</div>-->

<div class="col-lg-9">
  <?php include './single_item/single_result.php';?>
</div>

  <div class="col-lg-3">
    <?php 

      //For similar dataset
      require_once './single_item/similar/pdb_similar_dataset.php';
      $pdbSimilarData = new PDBSimilarData;
      $pdbSimilarData -> show_similar_dataset($_GET['id']);
    	
    	//For related publications
    	require_once './single_item/pubmed/pubmed_publication.php';
    	$pubmedPublication =new PubmedPublication;
      if(isset($result['dataItem'])){
        $pubmedPublication -> show_publication($result['dataItem']['title']);
      }else{
        $pubmedPublication -> show_publication($result['title']);
      }
  

    	//For recent activities
    	include("./templates/recent_activity/recent_activity.php");?>
    
  </div>
</div>

<?php include './templates/footer.php';?>