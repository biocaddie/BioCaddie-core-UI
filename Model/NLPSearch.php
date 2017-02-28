<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 3/9/16
 * Time: 1:38 PM
 */
require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/../Model/ExpansionSearch.php';
require_once dirname(__FILE__) . '/../config/config.php';

class NLPExpansionTerms extends ExpansionTerms
{
    protected $NLP;
    private $url;// = 'http://clamp.uth.edu/nlp-process-webapp/cdr';

    function __construct(){
        global $nlp_server;
        $this->url =$nlp_server;
    }
    private function set_NLP($data)
    {   $time=microtime(true);
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $this->NLP = json_decode($response, true);
        $time_end=microtime(true);
        $timeslape = $time_end-$time;
        echo '<br>get nlp'.$timeslape .'seconds<br>';
    }
    public function setOriginalumlsID($originalTerm)
    {   $this->set_NLP($originalTerm);
        $this->originalumlsID = array_unique($this->NLP['MeshID']);
    }
    public function get_NLP(){
        return $this->NLP;
    }

}
Class NLPSearch extends ExpansionSearch
{

    protected $originalumlsID;
    protected $NLP;
    public function getTerminologyquery()
    {

        $ExpansionTerms = new NLPExpansionTerms();

        $this->update_query_string();

        $ExpansionTerms->setOriginalTerm($this->query);

        $ExpansionTerms->setOriginalumlsID($this->query);
        $time=microtime(true);
        $expan_query = $ExpansionTerms->get_expansion_query();
        var_dump($expan_query);
        $time_end=microtime(true);
        $timeslape = $time_end-$time;
        echo '<br>get expansion terms'.$timeslape .'seconds<br>';
        $this->NLP = $ExpansionTerms->get_NLP();

        return $expan_query;

    }

    private function get_NLP()
    {
        return $this->NLP;
    }

    protected function generateQuery()
    {
        if (count(array_keys($this->filterFields)) < 1) {
            $query_part = [
                'bool' => [
                    'should' => $this->generateQueryPart()
                ]
            ];
        } else {
            $filter = $this->generateFilter();
            $query_part = [
                'filtered' => [
                    'query' => [
                        'bool' => [
                            'should' => $this->generateQueryPart()
                        ]
                    ],
                    'filter' => $filter
                ]

            ];
        }
        return $query_part;
    }

    private function construct_NLP_query($NLP_data){
        $diseaseID = array_unique($NLP_data['DiseaseID']);
        $chemicalID = array_unique($NLP_data['ChemcialID']);
        $geneID = array_unique($NLP_data['GeneID']);
        $BPID = array_unique($NLP_data['GOID']);
        //$meshID = $NLP_data['MeshID'];
        //$disease = $NLP_data['Disease'];
        //$chemical = $NLP_data['Chemical'];
        //$gene = $NLP_data['gene'];
        //$BP = $NLP_data['BP'];
        //$mesh = $NLP_data['Meshterm'];
        $cellline=$NLP_data['CellLine'];

        $d_result = "";
        foreach($diseaseID as $d ){
            if(strlen($d_result)==0){
                $d_result = 'NLP_fields.DiseaseID:'.$d;
            }
            else{
                $d_result = $d_result.' AND '.'NLP_fields.DiseaseID:'.$d;
            }
        }
        $c_result = "";
        foreach($chemicalID as $d ){
            if(strlen($c_result)==0){
                $c_result = 'NLP_fields.ChemcialID:'.$d;
            }
            else{
                $c_result = $c_result.' AND '.'NLP_fields.ChemcialID:'.$d;
            }
        }
        $bp_result = "";
        foreach($BPID as $d ){
            if(strlen($bp_result)==0){
                $bp_result = 'NLP_fields.GOID:"'.$d.'"';
            }
            else{
                $bp_result = $bp_result.' AND '.'NLP_fields.GOID:"'.$d.'"';
            }
        }
        $g_result = "";
        foreach($geneID as $d ){
            if(strlen($g_result)==0){
                $g_result = 'NLP_fields.GeneID:'.$d;
            }
            else{
                $g_result = $g_result.' OR '.'NLP_fields.GeneID:'.$d;
            }
        }
        $cellline_result="";
        foreach($cellline as $d ){
            if(strlen($cellline_result)==0){
                $cellline_result = 'NLP_fields.cellline:'.$d;
            }
            else{
                $cellline_result = $cellline_result.' AND '.'NLP_fields.cellline:'.$d;
            }
        }
        /*$mesh_result="";
        foreach($meshID as $d ){
            if(strlen($mesh_result)==0){
                $mesh_result = 'NLP_fields.meshID:'.$d;
            }
            else{
                $mesh_result = $mesh_result.' OR '.'NLP_fields.meshID:'.$d;
            }
        }
       */
        $result = '';
        if(strlen($d_result)>0){
            $result = '('.$d_result.')';
        }
        if(strlen($c_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$c_result.')';
            }
            else{
                $result = '('.$c_result.')';
            }
        }
        if(strlen($bp_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$bp_result.')';
            }
            else{
                $result = '('.$bp_result.')';
            }
        }
        if(strlen($g_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$g_result.')';
            }
            else{
                $result = '('.$g_result.')';
            }
        }
        /*if(strlen($mesh_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$mesh_result.')';
            }
            else{
                $result = '('.$mesh_result.')';
            }
        }*/
        if(strlen($cellline_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$cellline_result.')';
            }
            else{
                $result = '('.$cellline_result.')';
            }
        }
        return $result;
    }



    private function generateQueryPart()
    {   //using boost method

        $expan_query = $this->getTerminologyquery();
        $NLP_data = $this->get_NLP();
        $query_part = [['multi_match'=>[
            'query'=>$this->query,
            'fields'=>$this->searchFields,
            'operator'=>'and',
            'boost'=>'1',
            'type' => $this->querytype]]];

        foreach($expan_query as $query){
            array_push($query_part,['multi_match'=>[
                'query'=>$query,
                'fields'=>$this->searchFields,
                'operator'=>'and',
                'boost'=>'1']]);
        }

        $query_string = ["query_string"=>[
            //'query'=>'(NLP_fields.DiseaseID:'.$NLP_data['DiseaseID'][0].')AND(NLP_fields.ChemcialID:'.$NLP_data['ChemcialID'][0].')',
            'query'=>$this->construct_NLP_query($NLP_data),
            'boost'=>'2'
        ]];

        //$query_part = [];
        array_push($query_part,$query_string);

        echo '<pre>';
        print_r($query_part);
        echo '</pre>';
        return $query_part;

    }

}

/*$NERSearch = new NLPSearch();
$NERSearch->es_index="arrayexpress_20160523nlp";
$NERSearch->search_fields = ['dataset.description','dataset.title','dataset.dataType','treatment.title'];

$NERSearch->facets_fields = [];
$NERSearch->filter_fields = [];
$NERSearch->query = "lung cancer breast cancer EGFR";// gene expression glucose MDA-MB-231 EGFR";// gene expression glucose MDA-MB-231";//"lung carcinoma EGFR gene expression glucose MDA-MB-231";
$result = $NERSearch->getSearchResult();
//var_dump($result);
$repositoryHits = $result['hits']['total'];
echo $repositoryHits;
for($i=0;$i<20;$i++){
    echo '<pre>';
    print_r($result['hits']['hits'][$i]['_score']);
    print_r($result['hits']['hits'][$i]['_source']['dataset']['title']);
    echo '</pre>';
}*/

?>