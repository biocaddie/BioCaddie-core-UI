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