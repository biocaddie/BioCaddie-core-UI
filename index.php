<?php 
include './templates/header_home.php';

session_start();

// To track user's search history
if(!isset($_SESSION["history"])){
  $_SESSION["history"]=array();
}
?>

<?php include './templates/index.php';?>
<?php include './templates/footer.php';?>
