<?php

function partialActivities() {
    $history = [];
    if (isset($_SESSION["history"])) {
        $history =  $_SESSION["history"]['query'];;
    }
    if (count($history) > 0) {
        $counter = 0;
        ?>
        <div class="panel panel-success" id="recent">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-chevron-up"></span> Recent Activity</strong></div>
            <div class="panel-body">
                <ul class="no-disk">
        <?php foreach (array_reverse($history) as $historyItem): 
			$items = explode("|||", $historyItem);
        		$query = $items[0];
        		$searchtype = $items[1]
	?>
                        <li>
                            <span class="fa fa-search"></span>
                            <a href="./search.php?query=<?php echo $query ?>&searchtype=<?php echo $searchtype ?>"><?php echo $query."(".$searchtype.")" ;?></a>
                        </li>
                        <?php if (++$counter === 5) break; ?>
        <?php endforeach; ?>
                </ul>
            </div>
            <div class="panel-footer">
                <a class="hyperlink" href="./recentactivity.php">See more and save search query</a>
            </div>
        </div>
        <?php
    }
}
?>
