<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 1/19/16
 * Time: 12:30 PM
 */

function partialSynonym($searchBuilder)
{
    $count=0;
    $_SESSION['synonym']=$searchBuilder->getExpansionquery();
    $_SESSION['query']=$searchBuilder->getQuery();

    ?>

    <div class="panel panel-primary" id="synonym">
        <div class="panel-heading">
            <strong>Synonyms </strong>
        </div>

        <div class="panel-body">
                <ul class="no-disk">
                    <?php foreach ($searchBuilder->getExpansionquery() as $item):
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