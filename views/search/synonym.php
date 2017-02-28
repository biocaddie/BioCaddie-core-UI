<?php

/*
 * Display result status and number of results per page on the search.php page
 * input: an object of SearchBuilder class
 *
 * */

function partialSynonym($searchBuilder)
{
    $count=0;

    if($_SESSION['synonym'] == null || sizeof($_SESSION['synonym'])==0){
        $_SESSION['query']=$searchBuilder->getQuery();
        $_SESSION['synonym']=$searchBuilder->getExpandedQuery();

    }

    ?>

    <div class="panel panel-primary" id="synonym">
        <div class="panel-heading">
            <strong>Synonyms </strong>
        </div>

        <div class="panel-body">
                <ul class="no-disk">
                    <?php foreach ($_SESSION['synonym'] as $item):
                        if($count<5){?>
                        <li>
                            <span class="fa fa-tags"></span>
                            <span><?php echo $item; ?></span>
                        </li>
                    <?php $count++;} endforeach; ?>
                </ul>
        </div>

        <div class="panel-footer">
            <a href="expanded-query.php?q=<?php echo $searchBuilder->getQuery();?>">See more</a>
        </div>
    </div>


    <?php
}

?>