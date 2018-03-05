<?php
//calculate the count of the most accessed dataset
require_once dirname(__FILE__) . '/../../Model/ElasticSearch.php';

$index_name = "datamed-beta-2017*";
$display_item = "display"."\-"."item";
$search_field = "datamed_log.actionURL";
$search_size =10000;
$ids = array();
$input_array = ['esIndex' => $index_name, 'searchFields' => $search_field, 'query' => $display_item];
$search = new ElasticSearch($input_array);

$query_part = [
    'bool' => [
        'should' => [
            'query_string' => [
                'query' => $display_item,
                'default_field' => $search_field,
            ]
        ]
    ]
];

$body = ['from' => 0,
    'size' => $search_size,
    'query' => $query_part
];

$search->setSearchResultfromDataset($body);
$results = $search->getSearchResult();
$datasets = $results['hits']['hits'];
foreach($datasets as $dataset) {
    $actionURL = $dataset['_source']['actionURL'];

    if(strpos($actionURL,"id=")>0)
    {
        if(strpos($actionURL,"&",(strpos($actionURL,"id=")+3))>0) {
            $id = substr($actionURL,(strpos($actionURL,"id=")+3),(strpos($actionURL,"&",(strpos($actionURL,"id=")+3))-strpos($actionURL,"id=")-3));
        }
        else{
            $id = substr($actionURL,(strpos($actionURL,"id=")+3),24);
        }
    }
    else{
        continue;
    }

    array_push($ids,$id);
}

$countIds = array_count_values($ids);
arsort($countIds);
$resultids = array_keys($countIds);
$resultcounts = array_values($countIds);

$fiveids = ';';
if(count($countIds)>=8)
{
    for($i=0;$i<8;$i++){
        //array_push($fiveids,$resultids[$i]);
        //array_push($fivecounts,$resultcounts[$i]);
        if($i==7) {
            $fiveids .= $resultids[$i] ;
            $fivecounts .= $resultcounts[$i];
        }
        else {
            $fiveids .= $resultids[$i] . ';';
            $fivecounts .= $resultcounts[$i] . ';';
        }
    }
}
else{
    for($i=0;$i<count($countIds);$i++){
        if($i==count($countIds)-1) {
            $fiveids .= $resultids[$i] ;
            $fivecounts .= $resultcounts[$i];
        }
        else {
            $fiveids .= $resultids[$i] . ';';
            $fivecounts .= $resultcounts[$i] . ';';
        }
    }

}

$hreftmp=";";
$countofurl=0;

$idsearch_size = 1;
$resultoflink = array();
$resultoftitle=array();
$resultofcount = array();
$resultofid=array();
$resultofrepository=array();
for($k=0;$k<count($resultids);$k++){
    //$idindex_name = "datamed-beta-2017*";
    $iddisplay_item = $resultids[$k];
    $idsearch_field = "_id";

    $urlofid = array();
    //$input_array = ['esIndex' => $idindex_name, 'searchFields' => $idsearch_field, 'query' => $iddisplay_item];
    $input_array = ['esIndex' => $index_name, 'searchFields' => $search_field, 'query' => $iddisplay_item];
    $search = new ElasticSearch($input_array);

    $idquery_part = [
        'bool' => [
            'should' => [
                'query_string' => [
                    'query' => $iddisplay_item,
                    'default_field' => $search_field,
                ]
            ]
        ]
    ];

    $body = [
        'query' => $idquery_part
    ];

    $search->setSearchResultfromDataset($body);
    $resultsofactionurl = $search->getSearchResult();
    $actionURLall = $resultsofactionurl['hits']['hits'][0]['_source']['actionURL'];
    //$landingpage = $results['hits']['hits'][0]['_source']['access']['landingPage'];
    $reporsitory = substr($actionURLall,(strpos($actionURLall,"repository=")+11),4);

    $search->setSearchResultfromID($resultids[$k],getRepositoryIDMapping()[$reporsitory]);
    $resultsofid = $search->getSearchResult();

    $resulttitle= $resultsofid['_source']['dataset']['title'];
    $accesslink="http://datamedbeta.biocaddie.org/display-item.php?repository=".$reporsitory."&id=".$resultids[$k];

    if($resulttitle!=null) {
        $countofurl++;

        array_push($resultoftitle,$resulttitle);
        array_push($resultoflink,$accesslink);
        array_push($resultofcount,$resultcounts[$k]);
        array_push($resultofid,$resultids[$k]);
        array_push($resultofrepository,$reporsitory);
    }
    if($countofurl==7)
        break;
}
?>
<div id="datasets-container" class="panel panel-primary panel-home">
    <div class="panel-heading">
        <strong>Top 7 Datasets</strong>
        <span class="title-icon">
            <i class="fa fa-bar-chart fa-fw"></i>
        </span>

    </div>

    <div class="panel-body">
        <table class="table-striped">
            <?php
            for($n=0;$n<7;$n++){
            ?>
            <tr>
                <td><strong><?php echo $resultofcount[$n] ?></strong></td>
                <td> <img style="height: 16px ;width:16px; margin :5px"
                     src="./img/repositories/<?php echo $resultofrepository[$n];?>.png">
                </td>
                <td><strong><a class="hyperlink" style="font-size: 13px"
                           href="<?php echo $resultoflink[$n]?>"><?php echo $resultoftitle[$n] ?></a></strong>
                </td>
            </tr>
                <?php
            }
            ?>
        </table>

    </div>
</div>
