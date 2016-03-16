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
$search = new Search();
$search->setUemail($_SESSION['email']);
$result = $search->showPartialSearch($dbconn);

?>

<div class="container">
    <div class="col-lg-4">
        <div class="box">
            <div>
                <img class="circle-image" src="<?php echo $userData["picture"]; ?>" width="100px" size="100px" /><br/>
                <p class="welcome">Welcome <?php echo $userData["name"]; ?></p>
                <p class="oauthemail"><?php echo $userData["email"]; ?></p>
                <div class='logout'><a href='?logout'>Logout</a></div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div id="savedSearch" class="panel panel-primary">
            <div class="panel-heading">
                <strong>Saved Search Query</strong>
            </div>

            <div class="panel-body">
                <table class="table">
                    <thead>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Term</th>
                    </thead>

                    <?php foreach ($result as $row) {
                        $href="./search.php?query=". $row['search_term']."&searchtype=". $row['search_type'];
                        $href = str_replace('"','',$href);
                        ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['create_time'];?></td>
                        <td><?php echo $row['search_type'];?></td>
                        <td><a class="hyperlink"
                               href='<?php echo $href?>'><?php echo $row['search_term'] ?></a></td>
                    </tr>
                    </tbody>

                    <?php }?>
                    </table>
            </div>
            <div class="panel-footer">
                <a class="hyperlink" href="savedsearch.php">View Saved Search Query >></a>
            </div>
        </div>

        <div id="recentActivity" class="panel panel-primary">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-chevron-up"></span> Recent
                    Activities</strong></div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Term</th>
                    </thead>

                    <?php
                    if (count($history) > 0) {
                    $counter = count($history) - 1;
                    ?>

                    <tbody>
                    <?php foreach (array_reverse($history) as $historyItem):
                        $items = explode("|||", $historyItem);
                        $query = $items[0];
                        $searchtype = $items[1];
                        ?>
                        <tr>
                            <td>
                                <?php echo $date[$counter]; ?>
                            </td>
                            <td>
                                <?php echo $searchtype ?>
                            </td>
                            <td>
                                <a class="hyperlink"
                                   href="./search.php?query=<?php echo $query ?>&searchtype=<?php echo $searchtype ?>"><?php echo $query ?></a>
                            </td>
                        </tr>
                        <?php $counter--;endforeach; ?>
                    </tbody>
                    <?php } ?>
                </table>
            </div>

            <div class="panel-footer">
                <a class="hyperlink" href="recentactivity.php">See All Recent Activity >></a>
            </div>
        </div>
    </div>

    </div>
</div>