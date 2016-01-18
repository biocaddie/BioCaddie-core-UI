 <?php if($datasource=='0002'){?>
<div class="panel panel-success pilot">
  <div class="panel-heading"><h4> Explore in<h4></div>
  <div class="panel-body">
   
    <a href="http://129.106.31.108/pdb_v2/?q=<?php echo $_GET["query"];?>">
      <img src="./img/id.png" alt="Mountain View" class="pilot-logo">
    </a>

      </div>
</div>  
    <?php }?>
    <?php if($datasource=='0001'){?>


<div class="panel panel-success pilot">
  <div class="panel-heading"><h4> Explore in<h4></div>
  <div class="panel-body">
    <a href="./gwas/gwas_result.php?query1=<?php echo $_GET["query"];?>&query2=&query3=">
      <img src="./img/gf.png" alt="Mountain View" class="pilot-logo">
    </a>
    
  </div>
</div>  

<?php }?>  
