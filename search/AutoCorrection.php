<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 6/20/16
 * Time: 3:51 PM
 */

function get_correction($q){
    global $es_end_point;
    $has_fix = false;
    $url = $es_end_point.'/terms/_suggest';
    $data = array(
        "suggestions" => array(
            "text" => "",
            "term" => array(
                "analyzer" => "standard",
                "field" =>  "term"
            )
        )
    );
    if (!preg_match('/(AND|OR|NOT|\[|\])/', $q)){
        $data['suggestions']['text'] = $q;
    }

    $data = json_encode($data);
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec( $ch );
    $response = json_decode($response);
    $response = $response->suggestions;
    $fixed_ver = [];
    foreach($response as $sugg) {
        if(count($sugg->options) > 0) {
            $has_fix = true;
            $fixed_ver[] = $sugg->options[0]->text ;
        }
        else {
            $fixed_ver[] = $sugg->text ;
        }
    }
    $correction = implode($fixed_ver, ' ');
    return [$has_fix,$correction];
}

?>