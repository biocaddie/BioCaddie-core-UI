<?php

/*
 * Display search details on the search.php page
 * input: an object of SearchBuilder class
 *
 * */

function partialSearchDetails($searchBuilder)
{
    $detail = $searchBuilder->getSearchDetails();
    ?>

    <div class="panel panel-primary" id="details">
        <div class="panel-heading">
            <strong>Search Details </strong>
        </div>

        <div class="panel-body">
            <div class="form-group span6">
                <textarea class="form-control" rows="5" id="comment"><?php echo $detail;?></textarea>
            </div>
        </div>

    </div>


    <?php
}
?>