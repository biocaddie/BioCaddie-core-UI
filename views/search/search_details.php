<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 1/19/16
 * Time: 12:30 PM
 */

function partialSearchDetails($searchBuilder)
{
    $query = $searchBuilder->getQuery();
    $detail = "(".$searchBuilder->getSearchType().")".$query;

    ?>

    <div class="panel panel-primary" id="details">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-chevron-up"></span>
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