<?php
$scorecheck="";
$ancestrycheck="";
if(isset($_GET['scorecheck'])){
	if($_GET['scorecheck']=='on'){
		$scorecheck="checked";
	}
}
if(isset($_GET['ancestrycheck'])){
	if($_GET['ancestrycheck']=='on'){
		$ancestrycheck="checked";
	}
}
$select1="";
$select2="";
$select3="";
$select4="";
$select5="";
if(isset($_GET['ancestry'])){
	if($_GET['ancestry']=='SAS'){
		$select1="selected";
	}
	if($_GET['ancestry']=='EAS'){
		$select2="selected";
	}
	if($_GET['ancestry']=='AMR'){
		$select3="selected";
	}
	if($_GET['ancestry']=='AFR'){
		$select4="selected";
	}
	if($_GET['ancestry']=='EUR'){
		$select5="selected";
	}
}
$scoremax = 0;
if(isset($_GET['scoremax'])){
	$scoremax=$_GET['scoremax'];
}

$fraction = 0;
if(isset($_GET['fraction'])){
	$fraction=$_GET['fraction'];
}
?>

<script>
function checkForm(){

	cansubmit=2;
	scrmax=document.diploidForm.scoremax.value;
	frctn=document.diploidForm.fraction.value;
	if(document.diploidForm.scorecheck.checked==false){
		document.diploidForm.scoremax.value="0";
		cansubmit--;
		
	}
	if(document.diploidForm.ancestrycheck.checked==false){
		document.diploidForm.ancestry.selectedIndex=-1;
		document.diploidForm.fraction.value=0;
		cansubmit--;
	}

	if(cansubmit<1){
		alert("You must check 'Min Diversity score' or 'Main Ancestry' to get the results!");
	}
	else if((scrmax!="" || frctn!="") && scrmax<0 || scrmax>1 || frctn<0 || frctn>1){
		alert("You must set diversity score or ancestry between 0-1!");
	}
	else{
		document.diploidForm.submit();
	}
	
}
</script>
<div class="panel panel-success">
    <div class="panel-heading" role="tab" id="heading_score">
        <h4 class="panel-title">
            <a role="button" data-target="#filter_score" data-toggle="collapse" aria-expanded="true"
               aria-controls="filter_score">
                <i class="fa fa-expand"></i> <span data-toggle="tooltip" data-placement="top" title="
                Race and admixture estimated from 1000 genome project">Diversity Filter</span>
            </a>
        </h4>
    </div>
    <div class="panel-body" style="padding-left: 15px">
<form name="diploidForm" action="<?php echo $url;?>" method="get">
    <input type="hidden" name="searchtype" value="data">
    <input type="hidden" name="query" value="<?php echo $_GET['query'];?>">
    <input type="hidden" name="repository" value="0026">
    <?if(isset($_GET['sort']) && $_GET['sort']!=""):?>
    <input type="hidden" name="sort" value="<?php echo $_GET['sort'];?>">
    <?endif;?>
    <?if(isset($_GET['offset'])&& $_GET['offset']!=""):?>
    <input type="hidden" name="offset" value="<?php echo $_GET['offset']==""?"1":$_GET['offset'];?>">
    <?endif;?>
    <?if(isset($_GET['filters']) && $_GET['filters']!=""):?>
        <input type="hidden" name="filters" value="<?php echo $_GET['filters'];?>">
    <?endif;?>

    <input id="score" name="scorecheck" type="checkbox" <?php echo $scorecheck;?>> <span data-toggle="tooltip" data-placement="top" title="0: one ancestry only, 1: equal parts of five ancestry">Min Diversity score [0 - 1]</span> <input style="width:50px" type="text" name="scoremax" value="<?php echo $scoremax;?>"> <br>
    <input name="ancestrycheck" type="checkbox" <?php echo $ancestrycheck;?>> <span data-toggle="tooltip" data-placement="top" title="EUR: European, EAS: East Asian, SAS: South Asian, AFR: African, AMR: Native American">Min Admixture Level [0 - 1]</span> <br>
    <select class="form-control" style="max-width: 70px;float: left" name="ancestry">
        <option <?php echo $select5; ?> value="EUR">EUR</option>
        <option <?php echo $select1; ?> value="SAS">SAS</option>
        <option <?php echo $select2; ?> value="EAS">EAS</option>
        <option <?php echo $select3; ?> value="AMR">AMR</option>
        <option <?php echo $select4; ?> value="AFR">AFR</option>


    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

     <input type="text" name="fraction" style="width:50px;float: left" value="<?php echo $fraction;?>">

     <input type="button" class="btn btn-primary" value="Submit" onclick="checkForm();">
</form>
    </div>
</div>
