<!--Word Cloud-->
<?php
//calculate the count of the most accessed dataset
require_once dirname(__FILE__) . '/../../Model/ElasticSearch.php';
//require_once dirname(__FILE__) . '/config/config.php';
function partialWordCloud($searchBuilder)
{
    $result = $searchBuilder->getElasticSearchResults();
    $keyitems1 = $result['aggregations']['significantKeys1']['buckets'];
    $keyitems2 = $result['aggregations']['significantKeys2']['buckets'];
    $keyitems3 = $result['aggregations']['significantKeys3']['buckets'];
    $keyitems4 = $result['aggregations']['significantKeys4']['buckets'];

    $resultcsv = array();
    $resultcsv50 =array();
    $wordlist=['xlsx','and','for','no','a','an','of','xls'];

    $querystring = explode(" ",$searchBuilder->getQuery());

    for ($j = 0; $j < 60; $j++) {
        $elementtmp1 = array();
        $elementtmp2 = array();
        $elementtmp3 = array();
        $elementtmp4 = array();
        array_push($elementtmp1, $keyitems1[$j]['key']);
        array_push($elementtmp1, $keyitems1[$j]['score']);
        array_push($resultcsv, $elementtmp1);
        array_push($elementtmp2, $keyitems2[$j]['key']);
        array_push($elementtmp2, $keyitems2[$j]['score']);
        array_push($resultcsv, $elementtmp2);
        array_push($elementtmp3, $keyitems3[$j]['key']);
        array_push($elementtmp3, $keyitems3[$j]['score']);
        array_push($resultcsv, $elementtmp3);
        array_push($elementtmp4, $keyitems4[$j]['key']);
        array_push($elementtmp4, $keyitems4[$j]['score']);
        array_push($resultcsv, $elementtmp4);
    }

    $data = array();
    foreach($resultcsv as $key => $value){
        $data[$key] = $value[1];
    }
    array_multisort($data,SORT_DESC,$resultcsv);

    $querystringlower = array();
    foreach($querystring as $a_l)
    {
        $b=strtolower($a_l);
        array_push($querystringlower,$b);
    }

    for($count=0;$count<65;$count++)
    {
        if(!in_array($resultcsv[$count][0],$querystring)
            and !in_array($resultcsv[$count][0],$querystringlower)
            and (!is_numeric($resultcsv[$count][0]))
            and !in_array($resultcsv[$count][0],$wordlist) and (strlen($resultcsv[$count][0])>2)){
            array_push($resultcsv50,$resultcsv[$count]);
        }
    }

    $res = array();
    foreach ($resultcsv50 as $value) {
        if(isset($res[$value[0]])){
            if($res[$value[0]][1]>$value[1]){

            }
            else{
                unset($value[0]);
                //array_push($res, $value);
                $res[$value[0]] = $value;
            }
        }
        else{
            //array_push($res, $value);
            $res[$value[0]] = $value;
        }
    }

    $resultFinal = array();

    foreach ($res as $kv) {
        $elementtmp = array();

        $elementtmp['text']=$kv[0];
        $elementtmp['size']= floor(($kv[1]/$resultcsv50[0][1])*(24-6))+6;
        array_push($resultFinal, $elementtmp);
    }

    $resultFinal = json_encode($resultFinal);

?>
    <div class="panel panel-primary" id="word-cloud">
        <div class="panel-heading">
            <strong>Word Cloud</strong>
            <input type="button" value="Apply" id="word-apply" style="display: none" disabled>
        </div>

        <div class="panel-body">
            <script src="js/wordcloud.js"></script>
            <script>
                $(window).load(function(){
                    wordCloud(<?php echo $resultFinal;?>);
                })
            </script>
            <svg  xmlns="http://www.w3.org/2000/svg" id="svg-key" viewBox="0 0 180 180">
            </svg>
        </div>
    </div>
<?php
}
?>
