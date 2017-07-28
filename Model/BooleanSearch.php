<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/8/16
 * Time: 10:24 AM
 * Class to parse boolean search query, construct ES query and generate ES result
 */

require_once dirname(__FILE__) . '/Parser.php';
require_once dirname(__FILE__) . '/ExpansionSearch.php';

class BooleanSearch extends ExpansionSearch
{
    protected $synonyms;
    protected $all_synonyms = [];
    public $search_details = null;

    /*
     * expand field names for advanced search
     * @return array(string)
     */

    protected function fieldsExpansion($query_field)
    {
        $query_fields_array = array();
        switch ($query_field) {
            case "all search fields":
                $query_fields_array = ['_all'];
                break;
            case "":
                $query_fields_array = ['_all'];
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
            case "dimension":
                $query_fields_array = array("dimension.name", "dimensions.name", 'cde.name');
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

    protected function recursiveConstructBoolQuery($parsed_query)
    {
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
                    $query_fields = $this->fieldsExpansion($x[1]);
                    $quert_term = $x[0];
                    $query_type = $x[2];
                    if ($query_fields == null || $query_fields == "all search fields") {
                        $query_fields = '_all';
                    }

                    /* Add synonyms */
                    $this->query = $quert_term;

                    //$this->setSynonyms();
                    $x = $this->getSynonymsForBooleanQuery($query_type, $quert_term);
                    $expan_query = $x[0];//$this->synonyms;
                    $synonyms_array = $x[1];

                    $this->all_synonyms = array_merge($this->all_synonyms, $expan_query);
                    if (sizeof($this->search_details) > 0) {
                        $this->search_details .= " " . $operator . " ";
                    }
                    $this->search_details .= "(\"" . $quert_term . "\"";
                    foreach ($expan_query as $tmp) {
                        $this->search_details .= " OR (\"" . $tmp . '")';
                    }
                    $this->search_details .= ")";
                    if ($query_fields != '_all' and !is_array($query_fields)) {
                        $this->search_details .= "[" . $query_fields . "]";
                    } else {
                        $this->search_details .= "[" . implode(",",$x[1]) . "]";
                    }

                    //$query_part_sub = $this->generateQueryPartBoolean($quert_term,$query_fields,$this->queryType,$this->synonymsArray);
                    $query_part_sub = $this->generateQueryPartBoolean($quert_term, $query_fields, $query_type, $synonyms_array);
                    array_push($query_part['bool'][$oprArray[$operator]], $query_part_sub);
                }

            }


        }
        return $query_part;
    }


    /*
     * generate ES query part according to synonyms and query
     * @return array(string)
     */


    protected function generateQueryPartBoolean($quert_term, $query_fields, $queryType, $synonymsArray)

    {   global $cdedemo;
        if($cdedemo){
            if (in_array('dimension.name', $query_fields) ){
                $query_part = $this->generateQueryDimensionPart($quert_term, $queryType);
                return $query_part;
            }
        }
        $query_part = ['bool' => ["should" => [
            ['multi_match' => [
                'query' => $quert_term,
                'fields' => $query_fields,
                'operator' => 'and',
                'type' => $queryType
            ]
            ]]]];
        if(sizeof($synonymsArray)==0 or sizeof($synonymsArray)==0){
            return $query_part;
        }
        $all_synsquery = ['bool' => ['must' => []]];
        foreach (array_keys($synonymsArray) as $key) {
            $synmquery = ['bool' => ['should' => []]];
            foreach ($synonymsArray[$key] as $query) {
                array_push($synmquery['bool']['should'], ['multi_match' => [
                    'query' => $query,
                    'fields' => $query_fields,
                    'operator' => 'and',
                    'type' => $queryType],
                ]);
            }
            array_push($all_synsquery['bool']['must'], $synmquery);
        }

        array_push($query_part['bool']['should'], $all_synsquery);
        return $query_part;
    }

    /*
     * @return array(string)
     */

    protected function processBooleanQuery($query)
    {

        $query_type = 'most_fields';
        $this->queryType = 'most_fields';
        // Get query term
        $quert_term = trim(preg_replace("/\[[^)]+\]/", "", $query));
        if (substr($quert_term, 0, 5) == "&#34;" && substr($quert_term, -5, 5) == "&#34;") { //if token is wrapper by double quote, query it as a phrase
            $quert_term = (trim($quert_term, "&#34;"));
            $query_type = 'phrase';
            $this->queryType = 'phrase';
        }

        // Get search fields
        preg_match_all("/\[(.*)\]/", $query, $mo);
        $query_fields = '';
        if (isset($mo[0][0])) {
            $query_fields = strtolower(trim($mo[0][0], "\[\]"));
        }

        return [$quert_term, $query_fields, $query_type];
    }

    /*
     * generate the query part for search without boolean
     * @return array(string)
     */
    protected function getSynonymsForBooleanQuery($query_type, $query)
    {
        $stopwords = ['of', 'the', 'a', 'an', 'or', 'by', 'to', 'up', 'in', 'on'];
        if ($query_type == 'phrase') {
            $synonyms = [];
            $synonyms_array = [];
        } else {
            $umls_IDs = $this->getUmlsIDforQuery($query);
            $expansion_query = [];
            $synonyms_array = [];
            foreach ($umls_IDs as $umls_ID) {
                $node = $this->getScigraphNode($umls_ID);
                $subarray = $this->parse_node($node);
                $expansion_query = array_merge($expansion_query, $subarray);
                $newsubarray = [];
                foreach ($subarray as $query) {
                    $query = preg_replace('/\b(' . implode('|', $stopwords) . ')\b/', '', $query);
                    array_push($newsubarray, $query);
                }
                array_push($synonyms_array, $newsubarray);
            }
            $expansion_query = array_unique($expansion_query);
            $synonyms = $expansion_query;
        }
        return [$synonyms, $synonyms_array];
    }

    protected function getUmlsIDforQuery($query)
    {
        if ($query == "" || $query == " ") {
            return;
        }
        $umls_IDs = $this->get_cuis_from_metamap($query);
        return $umls_IDs;
    }

    protected function generateSingleQuery()
    {

        $x = $this->processBooleanQuery($this->query);
        $query_fields = $x[1];
        $quert_term = $x[0];
        $this->query = $quert_term;
        $this->searchFields = $this->fieldsExpansion($query_fields);

        if ($query_fields == null || $query_fields == "all search fields") {
            $query_fields = $this->searchFields;
        }
        global $cdedemo;

        /* Add synonyms */
         if($this->queryType=='phrase'){
            $expan_query = [];
            $this->synonymsArray = [];
         }
         elseif($cdedemo and $query_fields=='dimension'){
             $expan_query = [];
             $this->synonymsArray = [];
         }
         else{
             $this->synonymsArray=[];
             $this->setSynonyms();
             $expan_query = parent::getSynonyms();
         }

        $this->all_synonyms = array_merge($this->all_synonyms, $expan_query);
        $this->search_details = "(\"" . $quert_term . "\")";
        foreach ($expan_query as $tmp) {
            $this->search_details .= " OR (\"" . $tmp . '")';
        }
        $this->search_details .= ")";

        if ($query_fields != '_all' and !is_array($query_fields)) {
            $this->search_details .= "[" . $query_fields . "]";
        } else {
            $this->search_details .= "[" . implode(",",$x[1]) . "]";
        }
        global $cdedemo;
        if($cdedemo){
            if ($query_fields == 'dimension' ){
                return $this->generateDimensionQuery($this->query, $this->queryType);
            }
        }


        return $this->generateQuery();
    }

    protected function generateDimensionQuery()
    {
        if (count(array_keys($this->filterFields)) < 1 && $this->year == "") {
            $query_part = [
                'bool' => [
                    'should' => $this->generateQueryDimensionPart($this->query, $this->queryType)
                ]
            ];
        } else {
            $filter = $this->generateFilter();
            $query_part = [
                'filtered' => [
                    'query' => [
                        'bool' => [
                            'should' => $this->generateQueryDimensionPart($this->query, $this->queryType)
                        ]
                    ],
                    'filter' => $filter
                ]
            ];
        }
        return $query_part;
    }

    protected function generateQueryDimensionPart($query, $type)

    {

        $query_part =
            array(["nested" => [
                "path" => "dimension",
                "query" => [
                    "multi_match" => [
                        "query"=>$query,
                        "fields"=>["dimension.name"],
                        "operator" => "and",
                        'type'=>$type
                            ],
                        ],
                        ]
                    ]
                );
         $query_part=array_merge($query_part,
             array(["nested"=>[
                 "path"=>"cde",
                 "query"=>[
                     "multi_match" => [
                        "query"=>$query,
                        "fields"=>["cde.name"],
                        "operator" => "and",
                        'type'=>$type
                            ],
                        ],
                        ]
                    ]
         ));

         $query_part = ['bool'=>['should'=>$query_part]];
       // $query_part = $query_part1;

        return $query_part;
    }

    /*
     * Generates the query for Boolean search
     * @return array(string)
     */

    protected function generateBoolQuery()
    {
        $parser = new Parser();
        $parsedQuery = $parser->set_Content($this->query)->tokenize()->parse();
        if (count($parsedQuery) == 1) {
            $query_part = $this->generateSingleQuery();
        } else {

            if (count(array_keys($this->filterFields)) < 1 && $this->year == "") {
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

    protected function generateSearchBody()
    {
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

    public function getSearchResult()
    {
        $this->updateQueryString();
        $body = $this->generateSearchBody();

        return $this->generateResult($body);
    }

    public function getSynonyms()
    {
        return array_unique($this->all_synonyms);
    }

    public function __construct($input_array)
    {
        parent::__construct($input_array);
        $this->setSearchResult();
    }


}

//use example
/* $input_array=['esIndex'=>'pdb','searchFields'=>'dataset.title','query'=>'cancer[title] NOT lung'];
  $search = new BooleanSearch($input_array);
  $result = $search->getSearchResult();
  var_dump($result); */
?>