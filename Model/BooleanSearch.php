<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/8/16
 * Time: 10:24 AM
 * Class to parse boolean search query, construct ES query and generate ES result
 */
require_once dirname(__FILE__) . '/Parser.php';
require_once 'ElasticSearch.php';

class BooleanSearch extends ElasticSearch {

    protected $synonyms;
    public $search_details=null;
    /*
     * expand field names for advanced search
     * @return array(string)
     */


    protected function fieldsExpansion($query_field) {
        $query_fields_array = array();
        switch ($query_field) {
            case "all search fields":
                $query_fields_array = $this->searchFields;
                break;
            case "title":
                $query_fields_array = array("dataItem.title", "title", "dataset.title", "Dataset.title");
                break;
            case "author":
                $query_fields_array = array("author", "dataset.creator");
                break;
            case "description":
                $query_fields_array = array(" dataItem.description", "desc", "description", "dataset.description", "dataset.note", "Dataset.description");
                break;
            case "disease":
                $query_fields_array = array("disease", "NLP_Fields.Disease", "disease.name");
                break;
            default:
                $query_fields_array = array($query_field);
        }

        return $query_fields_array;
    }

    /*
     * construct bool search query
     * @return array(string)
     */

    protected function recursiveConstructBoolQuery($parsed_query) {
        $query_part = array('bool' =>
                                array('must' => array(),
                                      'should' => array(),
                                      'must_not' => array()
                                    )
                            );

        $oprArray = [
            "AND" => "must",
            "OR" => "should",
            "NOT" => "must_not"
        ];

        $size = count($parsed_query);
        for ($i = 0; $i < $size; $i++) {
            if ($i % 2 == 0) {
                $query = $parsed_query[$i];  // Get query term

                if ($i == 0) {
                    $operator = $parsed_query[$i + 1];
                    if ($operator == "NOT") {
                        $operator = "AND";
                    }
                } else {
                    $operator = $parsed_query[($i - 1)];
                }

                if (is_array($query)) {
                    array_push($query_part['bool'][$oprArray[$operator]], $this->recursiveConstructBoolQuery($query));
                } else {
                    $x = $this->processBooleanQuery($query);
                    $query_fields = $x[1];
                    $quert_term = $x[0];

                    if ($query_fields == null || $query_fields == "all search fields") {
                        $query_fields = $this->searchFields;
                    }

                    /* Add synonyms */
                    $expan_query = $this->getSynonyms($quert_term);

                    if(sizeof($this->search_details)>0){
                        $this->search_details.=" ".$operator." ";
                    }
                    $this->search_details .= "(\"".$quert_term."\"";
                    foreach ($expan_query as $tmp) {
                        $quert_term.=" ".$tmp;
                        $this->search_details .= " OR \"".$tmp.'"';
                    }
                    $this->search_details .=")";
                    if($query_fields!='_all'and !is_array($query_fields)){
                        $this->search_details.="[".$query_fields."]";
                    }

                    array_push($query_part['bool'][$oprArray[$operator]], array('multi_match' => array('query' => $quert_term, 'fields' => $query_fields, "operator" => "or")));
                }

            }


        }

        return $query_part;
    }

    /*
     * @return array(string)
     */

    protected function processBooleanQuery($query) {
        // Get query term
        $quert_term = preg_replace("/\[[^)]+\]/", "", $query);
        if (substr($quert_term, 0, 5) == "&#34;" && substr($quert_term, -5, 5) == "&#34;") { //if token is wrapper by double quote, query it as a phrase
            $quert_term = (trim($quert_term, "&#34;"));
        }

        // Get search fields
        preg_match_all("/\[(.*)\]/", $query, $mo);
        $query_fields = '';
        if (isset($mo[0][0])) {
            $query_fields = strtolower(trim($mo[0][0], "\[\]"));
        }
        return [$quert_term, $query_fields];
    }

    /*
     * generate the query part for search without boolean
     * @return array(string)
     */

    protected function generateSingleQuery() {

        $x = $this->processBooleanQuery($this->query);
        $query_fields = $x[1];
        $quert_term = $x[0];

        $this->query = $quert_term;
        $this->searchFields = $this->fieldsExpansion($query_fields);

        return $this->generateQuery();
    }

    /*
     * Generates the query for Boolean search
     * @return array(string)
     */

    protected function generateBoolQuery() {
        $parser = new Parser();
        $parsedQuery = $parser->set_Content($this->query)->tokenize()->parse();

        if (count($parsedQuery) == 1) {
            $query_part = $this->generateSingleQuery();
        } else {

            if (count(array_keys($this->filterFields)) < 1 && $this->year=="") {
                $query_part = $this->recursiveConstructBoolQuery($parsedQuery);
            } else {
                $filter = $this->generateFilter();
                $query_part = array('filtered' => array('query' => $this->recursiveConstructBoolQuery($parsedQuery), 'filter' => $filter));
            }
        }


        return $query_part;
    }

    /*
     * Generates the main body of the query.
     * @return array(string)
     */

    protected function generateSearchBody() {
        $facets = $this->generateFacets();
        $search_query = $this->generateBoolQuery();

        $sortSetting = $this->generateSort();
        $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
            'size' => $this->rowsPerPage,
            'query' => $search_query,
            'highlight' => $this->highlight
        ];
        if (count($facets) > 0) {
            $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
                'size' => $this->rowsPerPage,
                'query' => $search_query,
                'highlight' => $this->highlight,
                'aggs' => $facets
            ];
        }
        if (count(array_keys($sortSetting) > 0)) {
            $body['sort'] = $sortSetting;
        }
        return $body;
    }

    /*
     * get search result.
     * @return array(string)
     */

    public function getSearchResult() {
        $this->updateQueryString();
        $body = $this->generateSearchBody();

        return $this->generateResult($body);
    }

    public function __construct($input_array) {
        parent::__construct($input_array);
        $this->setSearchResult();
    }


    /*
     * generate synonyms
     * @return array(string)
     */

    protected function getSynonyms($query)
    {

        if (substr($query, 0, 5) == "&#34;" && substr($query, -5, 5) == "&#34;") {
            $query = trim($query, "&#34;");
            $queryType = 'phrase';
        }

        if($queryType=='phrase'){
            $this->synonyms = [];
        }
        else {

            $umls_IDs = $this->getUmlsID($query);

            $expansion_query = [];
            foreach ($umls_IDs as $umls_ID) {
                $node = $this->getScigraphNode($umls_ID);

                $expansion_query = array_merge($expansion_query, $this->parse_node($node));
            }
            $expansion_query = array_unique($expansion_query);
            $synonyms = $expansion_query;
        }

        if(basename($_SERVER['PHP_SELF']) == "search.php"){

            if($_SESSION['synonym']==null){
                $_SESSION['synonym'] = $synonyms;
            }else{
                $tmp = array_merge($_SESSION['synonym'],$synonyms);
                $_SESSION['synonym'] = $tmp;
            }

        }


        return $synonyms;
    }

    protected function getUmlsID($query)
    {
        if ($query == "" || $query == " ") {
            $query = '';
            return;
        }
        $umls_IDs = $this->get_cuis_from_metamap($query);
        return $umls_IDs;
    }

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


}

//use example
/* $input_array=['esIndex'=>'pdb','searchFields'=>'dataset.title','query'=>'cancer[title] NOT lung'];
  $search = new BooleanSearch($input_array);
  $result = $search->getSearchResult();
  var_dump($result); */
?>