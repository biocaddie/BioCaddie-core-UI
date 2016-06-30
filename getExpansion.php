<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 5/3/16
 * Time: 11:50 AM
 */


$cuis = array("C0005411","C0006104","C0008018","C0013935","C0013936","C0015915","C0017952","C0033684","C0076930","C0079419","C0079441","C0086418","C0243066","C0449951","C0751971","C0768042","C0812319","C1269537","C1305370","C1416706","C1417170","C1417171","C1419864","C1425248","C1523148","C1704256","C1755625","C1999703","C2681653");

foreach($cuis as $cui){
    getTerms($cui);
}

function getTerms($cuiID){
    $url = "http://localhost:9000/scigraph/graph/neighbors/umls:_".$cuiID; #umls:_C0242379
    $json = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $url);
    
    print "-------------------------".$cuiID.'--------------------------';
    print "<pre>";
    print $json.'<br>';
    print "</pre>";

//    $data = json_decode($json, true);
}
?>
