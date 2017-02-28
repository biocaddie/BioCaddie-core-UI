<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 12/21/15
 * Time: 3:15 PM
 */


require_once dirname(__FILE__) .'/GrantSearch.php';


class PubmedGrantService
{
    private $grants;
    private $pmid;

    private function loadParameters()
    {
        $this->pmid = filter_input(INPUT_GET, "pmid", FILTER_SANITIZE_STRING);
        if ($this->pmid === NULL || strlen($this->pmid) == 0) {
            return false;
        }
    }
    function __construct() {
        $this->loadParameters();
        $this->getPubmedGrant();
    }
    /*
     * get grant from pubmed given pmid
     * @return array(string)
     */
    public function getPubmedGrant($pmid=Null)

    {   if(!$pmid) {
            $pmid = $this->pmid;
        }
        $urlBase = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=";#20509765'26570998'
        $grantArray = array();
        $url = $urlBase . $pmid . '&retmode=xml';
        $resultPage = file_get_contents($url);

        $xmlArray = simplexml_load_string($resultPage);// or die("Error: SimpleXML cannot create object");
        if(isset($xmlArray->PubmedArticle->MedlineCitation->Article->GrantList->Grant))
        {
            $grants = $xmlArray->PubmedArticle->MedlineCitation->Article->GrantList->Grant;
            $this->grants = $this->reduce_duplicate_grant($grants);

            foreach ($this->grants as $grant) {
                $name = $this->buildName($grant);

                array_push($grantArray, $name);
            }
        }
        return $grantArray;
    }
    /*
     * get  pmid from singleItemService object
     * @return string
     */
    public function extractPmid($SingleItemService){
        $result = $SingleItemService->getSearchResults();
        //var_dump($result);
        $pmid = Null;
        if (isset($result['primaryPublications'][0]['ID'])) {   //pdb,peptideAtlas,yped
            $pmid= substr($result['primaryPublications'][0]['ID'],5);
        }
        elseif (isset($result['primaryPublications']['ID'])) {
            $pmid= substr($result['primaryPublications']['ID'],5);
        }
        elseif (isset($result['PrimaryPublication']['ID'])) {//bmrb
            $pmid=$result['PrimaryPublication']['ID'];
        }
        elseif (isset($result['primaryPublication'][0]['ID'])) {
            $pmid= substr($result['primaryPublication'][0]['ID'],5);
        }
        elseif (isset($result['primaryPublication']['ID'][0])) {
            if(strpos($result['primaryPublication']['ID'][0],'PMID')>=0 || strpos($result['primaryPublication']['ID'][0],'pmid')>=0){
                $pmid= substr($result['primaryPublication']['ID'][0],5);   //geo,openfmri,uniprot
            }
            else{
                $pmid= $result['primaryPublication']['ID'][0];//physiobank
            }
        }elseif(isset($result['publication']['ID'])) {   //bioproject
            $pmid = substr($result['publication']['ID'], 5);
        }
        elseif(isset($result['publication'][0]['ID'])) {
            $pmid = substr($result['publication'][0]['ID'], 5);
        }

       // var_dump($pmid);
        return $pmid;
    }


   /*
    * make up showing name for grant
    * @return string
    */
    protected function buildName($grant){
        $name = '';
        if (isset($grant->GrantID)) {
            $name = $name . $grant->GrantID;
        }
        if (isset($grant->Acronym)) {
            $name = $name . '/' . $grant->Acronym;
        }
        if (isset($grant->Agency)) {
            $name = $name . '/' . $grant->Agency;
        }
        if (isset($grant->Country)) {
            $name = $name . '/' . $grant->Country;
        }
        if (0 === strpos($name, '/')){
            $name = substr($name,1);
        }
        return $name;
    }
/*
 * search grant detail from ES
 * @return array(string)
 */
    public function searchGrantInfo()
    {
        $grants_details = array();
        $newgrants = $this->grants;//$this->reduce_duplicate_grant($this->grants);
        foreach ($newgrants as $grant) {
            $name = $this->buildName($grant);
            $grants_details[$name]=[];
            if (isset($grant->Agency)) {
                if (strpos($grant->Agency, 'NIH') !== false) {
                    $search = new GrantSearch(['esIndex'=>'grant','GrantID'=>$grant->GrantID]);
                    $results = $search->getSearchResult();
                    $repositoryHits = $results['hits']['total'];
                    if($repositoryHits>0){
                        $newresult = $results['hits']['hits'];
                        $project_detail_list = $this->filterFirstGrantForTheSameProjectNum($newresult);
                        $grants_details[$name]=$project_detail_list;

                    }

                }
            }

        }

        return $grants_details;
    }
    /*
     * only showing the first grant
     * @return array(string)
     */
    protected function filterFirstGrantForTheSameProjectNum($results){
        $project_list = array();
        $project_detail_list = array();
        $out = array();
        foreach($results as $result){
            $project_num = $result['_source']['project_num'];
            $id = $result['_source']['ID'];
            if(!isset($project_list[$project_num])){
                $project_list[$project_num]=$id;
                $project_detail_list[$project_num]=$result['_source'];
            }
            else{
                if($id<$project_list[$project_num]){
                    $project_list[$project_num]=$id;
                    $project_detail_list[$project_num]=$result['_source'];
                }
            }
        }
        foreach(array_keys($project_detail_list) as $key){
            array_push($out,$project_detail_list[$key]);
        }
        return $out;
    }
    protected function reduce_duplicate_grant($grants){
        $IDs = [];
        $newgrants= [];
        foreach ($grants as $grant) {
            $name = $grant->GrantID;
            $checkname = str_replace(' ','',$name);
            $checkname = str_replace('-','',$checkname);

            if(preg_match('/^[A-Za-z]{2}[0-9]{5}/',substr($checkname,-7))){
                $checkname=substr($checkname,0,strlen($checkname)-5)."0".substr($checkname,strlen($checkname)-5);
            }
            $checkname=substr($checkname,strlen($checkname)-8,strlen($checkname));
            if(in_array($checkname,$IDs)){
                continue;
            }
            array_push($IDs,$checkname);
            array_push($newgrants,$grant);

    }
        return $newgrants;
}
}

?>