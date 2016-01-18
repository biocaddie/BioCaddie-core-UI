<div  class="row">
  <div  class="jumbotron search-block-2">
  <form action="result.php" method='get' autocomplete='off'>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Search for data through BioCADDIE" name='query' value="<?php echo $q;?>">
      <input type="hidden" name="datasource" value="<?php echo $datasource;?>">
      <div class="input-group-btn">
        <input class="btn btn-default" type='submit' value='' id="btn-search">
      </div><!-- /btn-group -->
    </div><!--input-group-->
  </form>
  
   <p class="search-text-sm">Search examples(breast cancer, genetic analysis software, gene EGFR)</p>
</div><!-- /.col-lg-12 -->
