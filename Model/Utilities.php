<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/14/16
 * Time: 2:26 PM
 */
/*
 * in bioproject ,the title is duplicate, get rid of the duplicate
 * @parm String
 * @return String
 */
function reduce_duplicate_in_title($text)
{

    if (strpos($text, ':') !== false) {
        $first = trim(substr($text, 0, strlen($text) / 2));
        $second = trim(substr($text, strlen($text) / 2 + 1));
        if (strcmp($first, $second) == 0) {
            return $first;
        }
    }
    return $text;

}

/**
 * Decodes ElasticSearch facets to be used for the search query.
 * @param UNKNOWN $post_arrays
 * @return array(UNKNOWN)
 */
 function decode_filter_fields($post_arrays)
{
    $result = [];
    $keys = array_keys($post_arrays);
    foreach ($keys as $key) {
        $values = explode(':', $key);
        $filter = $values[0];
        $replaceList = ['____' => '.', '___', ' '];
        $filter_value = str_replace(array_keys($replaceList), array_keys(array_values), $values[1]);
        if (array_key_exists($key, $result)) {
            array_push($result[$filter], $filter_value);
        } else {
            $result[$filter] = [$filter_value];
        }
    }
    return $result;
}

function convert_array_to_string($array,$name){
    $result = '';
    foreach($array as $item ){
        $result=$result.$item[$name].'<br>';
    }
    return $result;
}

function convert_array_to_string_one_level($array){
    $result = '';
    foreach($array as $item ){
        $result=$result.$item.'<br>';
    }
    return substr($result,0,strlen($result)-4);
    //return $result;
}

function convert_array_to_string_one_level_delimited($array,$delimit){
    $result = '';
    foreach($array as $item ){
        $result=$result.$item.$delimit;
    }
    return $result;
}

function convert_array_to_string_two_level($array){

    $result = '';
    foreach(array_keys($array) as $item ){
        $result=$result.$item.':'.$array[$item].'<br>';
    }
    return $result;
}

function format_time($time){
    $time=str_replace('T000000+0000','',$time);
    $time=str_replace('T00:00:00Z','',$time);
    $time=str_replace('Z','',$time);
    if(strlen($time)<=4){
        return $time;
    }
    /*if(preg_match('/\W[<br>]?/',$time)>0){
        return $time;
    }*/
    if(preg_match('/,/',$time)>0){//vectorbase
        return $time;
    }
    if(strpos($time,'<br>')>=0 or is_array($time)){
        $times = explode('<br>',$time);
        $a = '';
        foreach($times as $itime){
            $a = $a.date("m-d-Y",strtotime($itime)).'<br>';

        }
        return substr($a,0,strlen($a)-4);
    }

    return date("m-d-Y",strtotime($time));

}

//check if it is url
function check_url($url){
    $a = preg_match('/(https|http|ftp)\:\/\/|([a-z0-9A-Z]+\.[a-z0-9A-Z]+\.[a-zA-Z]{2,4})|([a-z0-9A-Z]+\.[a-zA-Z]{2,4})|\?([a-zA-Z0-9]+[\&\=\#a-z]+)/', $url);
    if($a>0){
        if(preg_match('/[,]/',$url)){
            return false;
        }
        return true;
    }
    return false;
}

function shorten($value) {
    $maxLen = 100;
    if(is_array($value)){
        return 0;
    }
    $value = strip_tags($value);
    return strlen($value) > $maxLen ? substr($value, 0, $maxLen) . '...' : $value;
}


/*
 * check if a string is endswith with a substring
 */
function endsWith($haystack,$needle,$case=true)
{
    $expectedPosition = strlen($haystack) - strlen($needle);

    if ($case)
        return strrpos($haystack, $needle, 0) === $expectedPosition;

    return strripos($haystack, $needle, 0) === $expectedPosition;
}

function encodeFacetsTerm($key, $value) {
    // Replace . with '____'
    $value1 = str_replace('.', '____', $value);
    // Replace space with '___'
    $value2 = str_replace(' ', '___', $value1);
    $value3 = str_replace('"', '', $value2);
    return $key . ':' . $value3;
}

/**
 * decide if a search query is a boolean search or not
 * @return int (1: boolean search; 0: not a boolean search)
 */
function isBoolSearch($query)

{
    if (preg_match('/^&#34;[^&#34;]+&#34;$/',$query)){  //example "number OR litters" will consider as phrase search
        return 0;
     }
    //preg_match('/^\"[^\"]+\"$/',$query)
    return preg_match('/(AND|OR|NOT|\[|\])/', $query);
}


// To track user's recent search activity
function trackSearchActivity($query, $search_type){

    if (!isset($_SESSION["history"]['query'])) {
        $_SESSION["history"]['query'] = array();
        $_SESSION["history"]['date'] = array();
    }
    $historyItem =$query . '|||' . $search_type;

    date_default_timezone_set('America/chicago');
    $historyDate = date("Y-m-d H:i:s");
    ;

    if (in_array($historyItem, $_SESSION["history"]['query'])) {
        $_SESSION["history"]['query'] = array_diff($_SESSION["history"]['query'], [$historyItem]);
    }
    array_push($_SESSION["history"]['query'], $historyItem);
    array_push($_SESSION["history"]['date'], $historyDate);
}

function get_dom_value($url){

    $dom = new DOMDocument();
    @$dom->loadHTML($url);
    if($dom){
        return $url;
    }
    $urls = $dom->getElementsByTagName('a');

    //preg_match("/<a[^>]+>([^<]*)</", $url, $result);
    $result = $urls->item(0)->nodeValue;
    if(!$result){
        $result = $url;
    }

    return $result;
}
function get_dom_href($url){


	$dom = new DomDocument();
    @$dom->loadHTML($url);
    preg_match('/<a.*?href=(["\'])(.*?)\1.*$/', $url, $result);
    if($result[2]){
        $href = $result[2];
    }
    else{
        $href = $url;
    }

    return $href;
}

function convert_datasetDist_to_array($array){
    $result = [];
    foreach($array as $item ){
        foreach(array_keys($item) as $key){
            $display_value=$item[$key];
            if(is_array($item[$key])){
                $display_value=$item[$key][0];
            }
            if($key==='accessURL'){
                array_push($result,[$key,$item[$key],$item[$key]]);
            }
            else{
                array_push($result,[$key,$display_value]);
            }

        }
    }
    return $result;
}
function get_display_item_common_view($id,$rows){
    //var_dump($rows);
    $search_results = [];
    $logo_link_icon = '&nbsp;&nbsp;&nbsp;<img style="height: 20px ;width:60px" src="./img/repositories/'. $id.'.png">';
    $search_results['logo']=$logo_link_icon;
    $search_results['repo_id']=$id;
    $search_results['show_order']=['dataset','taxonomicInformation','TaxonomicInformation','TaxonomyInformation','access','attributes',"primaryPublication",'publication',
        "primaryPublications",'PrimaryPublication','activity','biologicalEntity','biologicalProcess','cellline','cellLine','cellularComponent','primaryCell','dimension','dimensions','disease',
        'treatment','instrument','person','material',"BiologicalEntity","MolecularEntity",'molecularEntity','anatomicalPart','gene','dataAcquisition','dataStandard','distribution','assay','antibody','license',"acl","dataType",
        'Activity','AlternateIdentifiers','alternateIdentifiers','alternateIdentifier','DataDistribution','RelatedIdentifiers','relatedIdentifiers','Access',"Study",'studyGroup','grant','iPSC','Instument','protein','phosphoProtein','datastandard',"citation","study",'identifiers','internal',
        "AnatomicalPart",'datasetDistributions','datasetDistribution','datasetdistribution','persons','creators','identifierInformation','dataAnalysis','dataDistributions',
        "taxonomicinformation","dataDistribution",'organization','dataRepository','datarepository'];


    foreach(array_keys($rows) as $key){
        if($key=='dataItem' || $key=='NLP_Fields'||$key=='provenance'){
            continue;
        }
        $search_results[$key]=[];
        if(is_array($rows[$key]) and array_key_exists(0,$rows[$key]) and sizeof($rows[$key])>1){ //datasetDistributions
            $result = convert_datasetDist_to_array($rows[$key]);
            foreach($result as $item){
                array_push($search_results[$key],$item);
            }
            continue;
        }

        $newrows = $rows[$key];
        if(is_array($rows[$key]) and array_key_exists(0,$rows[$key]) and sizeof($rows[$key])==1){
            $newrows = $rows[$key][0];
        }
        if(!is_array($newrows)){ //handle tcga acl,Access
            array_push($search_results[$key],[$key,$newrows]);
            continue;
        }
        foreach(array_keys($newrows) as $subkey){

            $display_value = (is_array($newrows[$subkey]) and array_key_exists(0,$newrows[$subkey]) ) ? convert_array_to_string_one_level($newrows[$subkey]) : $newrows[$subkey];

            if(in_array($subkey,['dateReleased','dateModified','dateCreated','dateUpdated','dateSubmitted','dateStarted','accessionDate'])){
                $display_value = format_time($display_value);
            }
            $a = check_url($display_value);

            if($a and !in_array($subkey,['title','description','publicationVenue','ID','name','alternateID','storedIn','authors'])){//emdb

                array_push($search_results[$key],[$subkey,$display_value,$display_value]);
            }

            elseif($key==='dataset' && $subkey==='title') {
                if(array_key_exists('landingpage',$rows['access'])){
                    $search_results['title']=[$subkey, $display_value, @$rows['access']['landingpage']];
                }
                elseif(array_key_exists('landingPage',$rows['access'])){
                    $search_results['title']=[$subkey, $display_value, @$rows['access']['landingPage']];
                }
                else{
                    $search_results['title']=[$subkey, $display_value,'']; //clinvar does not have link
                }
            }

            else{
                array_push($search_results[$key],[$subkey,$display_value]);
            }

        }
    }
        //handle title is missing case, like some case in nursa,retina
        if(strlen($search_results['title'][1])==0 or !array_key_exists('title',$search_results)){
            if(array_key_exists('landingpage',$rows['access'])){
                $search_results['title']=['title', $rows['dataset']['ID'], @$rows['access']['landingpage']];
            }
            elseif(array_key_exists('landingPage',$rows['access'])){
                $search_results['title']=['title', $rows['dataset']['ID'], @$rows['access']['landingPage']];
            }

            else{
                $search_results['title']=['title', $rows['dataset']['ID'], ''];
            }
        }
        //handle pmid link
        $pubfields = ['primaryPublications','primaryPublication','PrimaryPublication'];
        foreach($pubfields as $pubfield) {
            if (array_key_exists($pubfield, $search_results)) {
                for($i=0;$i<sizeof($search_results[$pubfield]);$i++){

                if (in_array('ID', $search_results[$pubfield][$i])) {
                    if (strpos($search_results[$pubfield][$i][1], 'pmid:') !== false  ) {
                        if($search_results[$pubfield][$i][1]=='pmid:N/A'){
                            continue;
                        }
                        $pmidlink = 'https://www.ncbi.nlm.nih.gov/pubmed/' . substr($search_results[$pubfield][$i][1], 5);
                        array_push($search_results[$pubfield][$i], $pmidlink);
                    }
                    elseif(preg_match('/^[0-9]*$/',$search_results[$pubfield][$i][1])==1){

                        $pmidlink = 'https://www.ncbi.nlm.nih.gov/pubmed/' . $search_results[$pubfield][$i][1];
                        array_push($search_results[$pubfield][$i], $pmidlink);
                    }
                }
            }
            }

        }
    return $search_results;

}
function get_search_repo_common_view($ids,$results,$query,$repoid){
    $show_array = [];


    for ($i = 0; $i < count($results); $i++) {
        $show_line = [];
        $r = $results[$i];


        foreach ($ids as $id) {
            $id_list = explode('.', $id);
            $idLevel = count($id_list);

            $id0 = $id_list[0];
            $id1 = $id_list[1];

            if ($idLevel == 3) {
                $id2 = $id_list[2];

                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id0][$id1][$id2] = implode(' ', $r['highlight'][$id]);
                }
                if (isset($r['_source'][$id0][$id1][0][$id2])) {
                    $show = shorten($r['_source'][$id0][$id1][0][$id2]);
                    if ($r['_source'][$id0][$id1][0][$id2] == '' || $r['_source'][$id0][$id1][0][$id2] == ' ') {
                        $show = 'n/a';
                    }
                }
            } else {

                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                }


                if (isset($r['_source'][$id0][$id1])) {
                    $show = shorten($r['_source'][$id0][$id1]);
                    if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                        $show = 'n/a';
                    }
                }

                if(isset($r['_source'][$id0][$id1])){
                    if(is_array($r['_source'][$id0][$id1])){
                        $show = implode("<br>", $r['_source'][$id0][$id1]);
                    }
                }else{
                    $show = "N/A";
                }


            }

            // Link data set to display-item page

            if ($id == 'dataset.title') {
                    if(strlen($r['_source']['dataset']['title'])==0) {
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $repoid . '&id=' . $r['_id'] . '&query=' . $query . '">' . $r['_source']['dataset']['ID'] . '</a>';
                    }
                    else{
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $repoid . '&id=' . $r['_id'] . '&query=' . $query . '">' . $r['_source']['dataset']['title'] . '</a>';
                    }
                }


            if($id=='dataset.ID' and !isset($r['_source']['dataset']['title'])){//TCGA
                $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $repoid . '&id=' . $r['_id'] . '&query=' . $query . '">' . $r['_source']['dataset']['ID'] . '</a>';
            }
            // Link citation to original citation page
            if ($id === 'primaryPublications.title') { //pdb
                if (isset($r['_source']['primaryPublications'][0]['ID'])) {
                    $citationPMID = explode(':', $r['_source']['primaryPublications'][0]['ID'])[1];
                    $citationLink = "http://www.ncbi.nlm.nih.gov/pubmed/" . $citationPMID;
                    $show =  '<a class=hyperlink href="' . $citationLink . '" target="_blank">'.$citationPMID.'</a>';
                } else {
                    $show = '';
                }
            }
            if(in_array($id,['dataset.dateReleased','dataset.dateCreated','datasetDistribution.dateReleased'])){
                $show=format_time($r['_source'][$id0][$id1]);
            }
            $show = (is_array($r['_source'][$id0]) and array_key_exists(0,$r['_source'][$id0]) ) ? convert_array_to_string($r['_source'][$id0],$id1) : $show;

            array_push($show_line, $show);
        }
        array_push($show_array, $show_line);
    }
    return $show_array;
}
function sort_by_name($input) {
    $num = array();
    if ($input != NULL) {
        foreach ($input as $key => $value) {

            $num[$key] = $value[0];

        }
        array_multisort($num, SORT_ASC, $input);
    }
    return $input;
}

function is_empty_field($array)
{
    if (sizeof($array) == 0) {
        return true;
    }
    $result = '';
    foreach (array_keys($array) as $key) {
        $result = $result . $array[$key][1];
    }
    if (strlen(trim($result)) == 0) {
        return true;
    }

    return false;
}

function check_valid_url($url) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_TIMEOUT,1);
    curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent: Some-Agent/1.0");
    $data = curl_exec($ch);
    $headers = curl_getinfo($ch);
    curl_close($ch);
    if(preg_match('/^[4|5]./',$headers['http_code'])){
    //if(in_array($headers['http_code'],['404','400','401','402','403','504','406'])){
        return False;
    }
    return True;
}
function get_tooltip($terms){
    $terms = explode('<br>',$terms);
    $result = [];
    foreach($terms as $term){
        if(array_key_exists($term,get_keyword_define())){
            $result[$term] = get_keyword_define()[$term];
        }
        else {
            $result[$term]= Null;
        }
    }
    return $result;
}

?>
