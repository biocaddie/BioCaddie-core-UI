<?php

function breadcrumb($searchBuilder) {

    $href  = "search.php?query=".$searchBuilder->getQuery()."&searchtype=".$_GET["searchtype"];
    $breadcrumbArray = array();
    $x=get_correction($searchBuilder->getQuery());
    $has_fix =  $x[0];
    $correction = $x[1];
    if(isset($_GET['repository'])){
        $correctionhref = "search-repository.php?query=".$correction."&searchtype=".$_GET["searchtype"]."&repository=".$_GET['repository'];
    }
    else{
        $correctionhref = "search.php?query=".$correction."&searchtype=".$_GET["searchtype"];
    }

    ?>
    <?php if($has_fix):?>
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb dynamic-crumbs">
                    <li>Did you mean <a class="hyperlink" href="<?php echo $correctionhref;?>"><?php echo $correction;?></a> ?</li>
            </div>
            </ol>
        </div>
    <?php endif;?>
    <div class="row">
        <div class="col-sm-12">
            <ol class="breadcrumb dynamic-crumbs">
                <li><a href="<?php echo $href;?>" id="crumb0"><?php echo $searchBuilder->getQuery(); ?></a></li>
                <?php

                if(isset($_GET['repository'])){
                    $repo = $_GET['repository'];
                    $href  = "search-repository.php?query=".$searchBuilder->getQuery()."&searchtype=".$_GET["searchtype"]."&repository=";
                    $repoName = getRepositoryIDNameMapping()[$repo];
                    $href .= $repo;
                    echo "<li><a href=\"".$href."\" id=\"crumb1\">". $repoName ."</a></li>";
                    $breadcrumbArray[$repoName] = array("repository");
                }

                if(isset($_GET['filters'])){
                    $href  = "search-repository.php?query=".$searchBuilder->getQuery()."&searchtype=".$_GET["searchtype"].
                        "&repository=".$_GET['repository']."&offset=1&filters=";

                    $filters= explode('$', $_GET['filters']);

                    $countfilters = 0;

                    foreach($filters as $filter){  // each filter panel
                        $filterItems = explode('@',$filter);
                        if($countfilters ==0){
                            $href.=$filterItems[0]."@";
                        }else{
                            $href.="$".$filterItems[0]."@";
                        }

                        if(count($filterItems)>1){
                            $filterItems = explode(',',$filterItems[1]);
                        }


                        $count =0;
                        foreach($filterItems as $filterItem){ // each selected filter item
                            if($count==0){
                                $href .=$filterItem;
                            }else{
                                $href .=",".$filterItem;
                            }
                            echo "<li><a href=\"".$href."\" id=\"crumb1\">". $filterItem ."</a></li>";
                            $count++;
                        }
                        $countfilters++;
                    }


                }
                ?>
            </ol>
        </div>
    </div>

    <?php
}
?>