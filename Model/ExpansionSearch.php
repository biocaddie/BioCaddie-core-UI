<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/7/16
 * Time: 2:34 PM
 * Class to get synonyms,construct ES query and get ES result
 */
require_once 'ElasticSearch.php';

class ExpansionSearch extends ElasticSearch
{

    protected $synonyms;
    protected $synonymsArray=[];
    public function __construct($input_array)
    {
        parent::__construct($input_array);
        if (substr($this->query, 0, 5) == "&#34;" && substr($this->query, -5, 5) == "&#34;") {
            $this->query = trim($this->query, "&#34;");
            $this->queryType = 'phrase';
        }
        $this->setSynonyms();
        $this->setSearchResult();
    }

    /*
     * convert query to umls ids
     * @return array(string)
     */


    private function get_cuis_from_metamap($data)

    {
        global $metamap_server;

        $curl = curl_init($metamap_server);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $mesh= json_decode($response, true);
        $cuis = $mesh['MeshID'];
        $cuis = array_merge($cuis,$mesh['GeneID']);
        $cuis = array_unique($cuis);

        return $cuis;
    }



    protected function getUmlsID()
    {
        if ($this->query == "" || $this->query == " ") {
            $this->query = '';
            return;
        }
        $umls_IDs = $this->get_cuis_from_metamap($this->query);
        return $umls_IDs;
    }

   /* protected function getUmlsID()
    {
        if ($this->query == "" || $this->query == " ") {
            $this->query = '';
            return;
        }
        $input_array = ['query' => $this->query, 'esIndex' => 'terms', 'searchFields' => ['term']];
        $search = new ElasticSearch($input_array);
        $search->setSearchResult();
        $result = $search->getSearchResult();getScigraphNode
        $repositoryHits = $result['hits']['total'];
        $umls_IDs = [];
        if ($repositoryHits > 0) {
            $umls_IDs = $result['hits']['hits'][0]['_source']['cuis'];
        }
        return $umls_IDs;
    }*/

    /*
     * get node of a umlsID from scigraph
     * @return array(string)
     */

    protected function getScigraphNode($umls_ID)
    {
        global $scigraph_url;
        $url = $scigraph_url . "umls:_" . $umls_ID;
        if (substr(php_uname(), 0, 7) == "Windows") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $json = curl_exec($ch);
            curl_close($ch);
        } else {
            $json = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $url);
        }
        $data = json_decode($json, true);
        $nodes = $data["nodes"];
        foreach ((array)$nodes as $node) {
            if ($node['id'] == 'umls:_' . $umls_ID) {
                return $node;
            }
        }
        return Null;
    }

    /*
     * process node from scigraph
     * @return array(string)
     */

    protected function parse_node($node)
    {
        $synonyms = array();
        if (isset($node["id"]) && isset($node['meta']['synonym'])) {
            $synonyms = $node['meta']['synonym'];
        }

        return $synonyms;
    }

    /*
     * generate synonyms
     * @return array(string)
     */

    protected function setSynonyms()
    {
        $stopwords = ['of','the','a','an','or','by','to','up','in','on'];
        if($this->queryType=='phrase'){
            $this->synonyms = [];
            $this->synonymsArray=[];
        }
        else {

            $umls_IDs = $this->getUmlsID();
            $expansion_query = [];
            foreach ($umls_IDs as $umls_ID) {
                $node = $this->getScigraphNode($umls_ID);
                $subarray = $this->parse_node($node);
                $expansion_query = array_merge($expansion_query, $subarray);
                $newsubarray=[];
                foreach($subarray as $query){
                    $query = preg_replace('/\b('.implode('|',$stopwords).')\b/','',$query);
                    array_push($newsubarray,$query);
                }
                array_push($this->synonymsArray,$newsubarray);
            }
            $expansion_query = array_unique($expansion_query);
            $this->synonyms = $expansion_query;
        }
    }

    /*
     * generate ES query part according to synonyms and query
     * @return array(string)
     */

    protected function generateQueryPart()

    {
        $query_part = [
        ['multi_match' => [
            'query' => $this->query,
            'fields' => $this->searchFields,
            'operator' => 'and',
            'type' => $this->queryType]]
    ];
        //$expan_query = $this->getSynonyms();
        //$expan_query=[];

        if(sizeof($this->synonymsArray)==0){
            return $query_part;
        }
        $all_synsquery = ['bool'=>['must'=>[]]];
        foreach(array_keys($this->synonymsArray) as $key){
            $synmquery = ['bool'=>['should'=>[]]];
            foreach ($this->synonymsArray[$key] as $query ) {
                array_push($synmquery['bool']['should'], ['multi_match' => [
                    'query' => $query,
                    'fields' => $this->searchFields,
                    'operator' => 'and']]);
            }
            array_push($all_synsquery['bool']['must'],$synmquery);
        }

        array_push($query_part,$all_synsquery);
        return $query_part;
    }

    /*
     * overwrite ES query part according to synonyms and query
     * @return array(string)
     */

    protected function generateQuery()
    {
        if (count(array_keys($this->filterFields)) < 1 && $this->year=="") {
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
        /*echo '<pre>';
        print_r($query_part);
        echo '</pre>';*/
        return $query_part;
    }

    /*
     * get all synonyms of a query
     * @return array(string)
     */

    public function getSynonyms()
    {
        return $this->synonyms;
    }
    public function getSynonymsArray()
    {
        return $this->synonymsArray;
    }


}

//use example
/* $input_array=['esIndex'=>'pdb','searchFields'=>'dataset.title','query'=>'cancer'];
  $search = new ExpansionSearch($input_array);
  $result = $search->getSearchResult();
  var_dump($result); */
?>
