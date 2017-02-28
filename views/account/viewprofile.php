<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$history = [];
if (isset($_SESSION["history"])) {
    $history = $_SESSION["history"]['query'];
    $date = $_SESSION["history"]['date'];
}


$objDBController = new DBController();
$dbconn=$objDBController->getConn();

//Get saved searches
$search = new Search();
$search->setUemail($_SESSION['email']);
$result = $search->showPartialSearch($dbconn);

//Get collections
$collections = new UserCollection();
$collections->setUserEmail($_SESSION['email']);
$collectionsList = $collections ->showPartialCollections($dbconn);

//Check if manager
$email=$_SESSION['email'];
$flag = check_manager_email($objDBController,$email);


?>

<div class="container">
    <div class="col-lg-4">
        <div class="box">
            <div>
                <img class="circle-image" src="<?php echo $userData["picture"]; ?>" width="100px" size="100px" /><br/>
                <p class="welcome">Welcome <?php echo $userData["name"]; ?></p>
                <p class="oauthemail"><?php echo $userData["email"]; ?></p>
                <div class='logout'><a href="?logout">Logout</a></div>
            </div>

        </div>
        <?php if ($flag):?>
          <div class="box" style="text-align: left">
              <ul>
                  <li>
                     <a class='hyperlink' href="manage_submit_repository.php"> <strong>Manage submitted repository</strong></a>
                  </li>
              </ul>
          </div>
        <?php endif;?>
    </div>

    <div class="col-lg-8">
        <?php require_once 'profileSavedSearch.php';?>
        <?php require_once 'profileRecentActivity.php';?>
        <?php require_once 'profileCollections.php';?>



    </div>

    </div>
</div>