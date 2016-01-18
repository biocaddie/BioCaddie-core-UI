<?php

session_start();

// To track user's search history
$q=$_REQUEST['query'];
if(!isset($_SESSION["history"])){
  $_SESSION["history"]=array();
}else{
	if(end($_SESSION["history"])!=$q){
		array_push($_SESSION["history"],$q);
	}
}
?>