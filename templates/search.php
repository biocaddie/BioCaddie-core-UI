<!--Search box-->
  <div class="row">
    <div  class="jumbotron search-block-2">
      <form action='./datasource.php' method='get' autocomplete='off' class="js-ajax-php-json">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search for data through BioCADDIE" name='query' id='query' value="<?php echo $q;?>">
          <div class="input-group-btn">
            <input class="btn btn-default" id="btn-search" type='submit' value='' onClick="return empty()">
          </div><!-- /btn-group -->
        </div><!--input-group-->
      </form>

      <p class="search-text-sm">Search examples(breast cancer, genetic analysis software, gene EGFR)</p>
    </div> <!-- jumbotron -->
  </div>