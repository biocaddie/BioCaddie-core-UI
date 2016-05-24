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
    if(preg_match('/(AND|OR|NOT|\[|\])/', $query)){
        $detail = "(".$searchBuilder->getSearchType().')'.$query;
        $synonyms=[];
    }
    else{
        $detail = "(".$searchBuilder->getSearchType().')"'.$query.'"';
        $synonyms=$searchBuilder->getExpansionquery();

    }
    foreach($synonyms as $synonyms){
        $detail = $detail .' OR "'.$synonyms.'"';
    }

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