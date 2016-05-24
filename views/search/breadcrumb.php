<?php

function breadcrumb($searchBuilder) {
    $currentURL = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]."\"";
    $href  = "search.php?query=".$searchBuilder->getQuery()."&searchtype=".$_GET["searchtype"];
    $breadcrumbArray = array();
    ?>
    <div class="row">
        <div class="col-sm-12">
            <ol class="breadcrumb dynamic-crumbs">
                <li><a href="<?php echo $href;?>" id="crumb0"><?php echo $searchBuilder->getQuery(); ?></a></li>
                <?php
                if(isset($_GET['repository'])){
                    $repos = explode(',',$_GET['repository']);
                    $href  = "search.php?query=".$searchBuilder->getQuery()."&searchtype=".$_GET["searchtype"]."&offset=".$_GET["offset"]."&repository=";
                    $count = 0;
                    foreach($repos as $repo){
                        $repoName = getRepositoryIDNameMapping()[$repo];
                        if($count == 0){
                            $href .= $repo;
                        }else{
                            $href .=",". $repo;
                        }
                        echo "<li><a href=\"".$href."\" id=\"crumb1\">". $repoName ."</a></li>";
                        if($repoName!=null){
                            $breadcrumbArray[$repoName] = array("repository");
                        }
                        $count++;
                    }
                }

                if(isset($_GET['datatypes'])){
                    $types = explode(',',$_GET['datatypes']);
                    $href  = "search.php?query=".$searchBuilder->getQuery()."&searchtype=".$_GET["searchtype"]."&offset=".$_GET["offset"]."&datatypes=";
                    $count = 0;
                    foreach($types as $type){
                        if($count == 0) {
                            $href .= $type;
                        }else{
                            $href .= ",".$type;
                        }

                        echo "<li><a href=\"".$href."\" id=\"crumb1\">". $type."</a></li>";
                        $breadcrumbArray[$type] = array("datatypes");
                        $count++;
                    }

                }
                ?>
            </ol>
        </div>
    </div>

    <?php
}
?>