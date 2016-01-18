<div class="container">
  <div  class="jumbotron search-block">
    <p class="search-text-lg">Engaging the community toward a Data Discovery Index (v0.2)</p>

    <form action='./datasource.php' method='get' autocomplete='off' class="js-ajax-php-json" id="search-form">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for data through BioCADDIE" name='query' id='query'>
        <div class="input-group-btn">
          <input class="btn btn-default" id="btn-search" type='submit' value='' onClick="return empty()">
        </div><!-- /btn-group -->
      </div><!--input-group-->
    </form>

    <!--dialog to diaplay the summary of search result-->
    <div id="dialog" title="Search Suggestion" hidden="hidden" autofocus="autofocus" >
      <p class="">This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
    </div>

    <p class="search-text-sm">Search examples(breast cancer, genetic analysis software, gene EGFR)</p>
  </div> <!-- jumbotron -->
  

  <div class="row">
    <div class="col-lg-4">
      <?php require_once("./index/statistics.php");?>
    </div><!--/.global_statistics-->
    <div class="col-lg-4">
     <?php require_once("./index/piechart.php");?>
    </div>
    <div class="col-lg-4" >
      <?php require_once("./index/mostaccess.php");?>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4">
      <?php require_once("./index/latest.php");?>
    </div>
    <div class="col-lg-4">
      <?php require_once("./index/feature.php");?>
    </div>
    <div class="col-lg-4">
      <?php require_once("./index/announcement.php");?>
    </div>
  </div>
</div><!--/.container-->

<div class="content-holder">
  <?php require_once("./index/pilotproject.php");?>
</div>


<!--forms for the type and search function-->
<form id="protein_form" action="./datasource.php" method = "post">
  <input type="hidden" name="query" id="query1">
  <!--<input type="hidden" name="SessionName">-->
  <input type="hidden" name="protein" id="protein">
</form>

<form id="phenotype_form" action="./datasource.php" method = "post">
  <input type="hidden" name="query" id="query2">
  <!--<input type="hidden" name="SessionName">-->
  <input type="hidden" name="phenotype" id="phenotype">
</form>

<form id="gene_expression_form" action="./datasource.php" method = "post">
  <input type="hidden" name="query" id="query3">
  <input type="hidden" name="gene expression" id="gene expression">
</form>

<form id="sequence_form" action="./datasource.php" method = "post">
  <input type="hidden" name="query" id="query4">
  <input type="hidden" name="sequence" id="sequence">
</form>


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


