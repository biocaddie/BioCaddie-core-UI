<?php include '../gwas/gwas_header.php';?>
 <div class="container"> 

  <div class="row">
    <div  class="jumbotron search-block-2">
      <form action='./gwas_result.php' method='get' autocomplete='off' class="js-ajax-php-json">
        <div class="row">
        <div class="input-group">
          <div class="col-md-4">
            <input type="text" class="form-control"  placeholder="Search by traits" name='query1' value="<?php echo $q;?>">
          </div>
          <div class="col-md-4">
             <input type="text" class="form-control"  placeholder="Search by platform" name='query2' value="<?php echo $r;?>">
          </div>
           <div class="col-md-4">
           <input type="text" class="form-control"  placeholder="Filter by case size" name='query3' value="<?php echo $s;?>">
         </div>
          <div class="input-group-btn">
            <div class="col-md-4">
              <input class="btn-primary btn btn-default" type='submit' value='Search'>
          </div>
          </div><!-- /btn-group -->
        </div><!--input-group-->
      </div>
      </form>

      <p class="search-text-sm">Search examples(breast cancer, genetic analysis software, gene EGFR)</p>
    </div> <!-- jumbotron -->
  </div>

<div class="row">


  <div class="col-md-3" id="leftCol">
    <h3>Show results for</h3>
    <ul class="nav nav-stacked" id="sidebar">
      <li><h4>GWAS (<?php echo $query['hits']['total']?>)</h4></li>
      <!-- <li><a href="#"><h4>Other Types of Study</h4></a></li> -->

    </ul>





  </div>
  <div class="col-md-9">
    <?php if (isset($results)) {
      $query0_txt = "";
      if ($q == "") {
        $query0_txt = "";
      }
      else{
        $query0_txt= '"' . $q . '"';
      }
      $query1_txt = "";
      if ($r == "") {
        $query1_txt = "";
      }
      elseif ($query0_txt == ""){
        $query1_txt= '"' . $r . '"';
      }
      else{
        $query1_txt= ' and "' . $r . '"';
      } 
      $query2_txt = "";
      if ($s == "") {
        $query2_txt = "";
      }
      else{
        $query2_txt= ' with over "' . $s . '" case sizes ';
      }	
      ?>
      <h4><?php echo show_current_record_number($offset,$query['hits']['total'],50)?> of <?php echo $query['hits']['total']?> results for <?php echo $query0_txt?><?php echo $query1_txt?><?php echo $query2_txt?> in <b>GWAS Publications.</b></h4>

      <?php echo show_pagination($query['hits']['total'],$offset,'gwas_result.php?query1='.$q.'&query2='.$r.'&query3='.$s.'&offset=',50); ?>



<table class="table table-striped">
  <thead>
    <tr>
      <th>PMID</th>
      <th>Title</th>
      <th>Abstract</th>
    </tr>
  </thead>
  <tbody>
    <?php if(isset($results) && count($results)>0){
      for($i=0;$i<min(50,count($results));$i++){
        $r = $results[$i];
        //foreach($results as $r){
        ?>


        <tr><td style="color:blue">
          <a href="<?php echo "https://www.ncbi.nlm.nih.gov/pubmed/?term=".$r['_id'] ?>">
            <u><?php echo $r['_id']; ?></u></a></td>
            <td><?php echo $r['_source']['TI']; ?></a></td>
            <!-- <td><?php echo substr($r['_source']['AB'],0,200).'...'; ?></td> -->
            <td><?php if(array_key_exists("AB", $r['_source'])){echo substr($r['_source']['AB'],0,200).'...';}else{echo "-";} ?></td>
          </tr>
          <?php } } ?>
        </tbody>
      </table>
    </div>
    <?php } ?>

</div>
</div>

  <?php include dirname(__FILE__) . '/../views/footer.php';?>


