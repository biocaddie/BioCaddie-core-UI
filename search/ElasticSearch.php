<?php

require_once dirname(__FILE__) . '/ElasticSearchBase.php';
require_once dirname(__FILE__) . '/Parser.php';

class ElasticSearch extends ElasticSearchBase {

    public $offset = 1;
    public $rowsPerPage = 20;
    public $highlight = ['fields' => ['*' => [ "pre_tags" => ["<strong>"], "post_tags" => ["</strong>"],"number_of_fragments" =>0]]];
    public $facet_size = 10;
    public $querytype = 'most_fields';

    protected function update_query_string(){
        if (substr($this->query, 0, 5) == "&#34;" && substr($this->query, -5, 5) == "&#34;") { //if token is wrapper by double quote, query it as a phrase
            $this->query = trim($this->query, "&#34;");
            $this->querytype = 'phrase';

        }
    }
    public function getSearchRowCount(){
        $body = $this->generateRowCountBody();
        return $this->generateResult($body);
    }

    public function getSearchResult() {
        $body = $this->generateSearchBody();
        return $this->generateResult($body);
    }

    public function generateResult($body) {
        global $es;
        if (sizeof($this->es_type) > 0) {
            $result = $es->search([
                'index' => $this->es_index,
                'type' => $this->es_type,
                'body' => $body
            ]);
        } else {
            $result = $es->search([
                'index' => $this->es_index,
                'body' => $body
            ]);
        }

        return $result;
    }

    // Generates the main body of the query.
    protected function generateRowCountBody() {
        $facets = $this->generateFacets();
        $search_query = $this->generateBoolQuery();
        $sort = $this->generate_sort();
        $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
            'size' => 0,
            'fields' => [],
            'query' => $search_query,
            'highlight' => $this->highlight
        ];
        if (count($facets) > 0) {
            $body['aggs'] = $facets;
        }
        if (count(array_keys($sort) >= 1)) {
            $body['sort'] = $sort;
        }
        return $body;
    }

    protected function generateFacets() {
        $facets = [];
        for ($i = 0; $i < count($this->facets_fields); $i++) {
            $facets[$this->facets_fields[$i]] = [
                'terms' => [
                    'field' => $this->facets_fields[$i],
                    'size' => $this->facet_size]
            ];
        }
        return $facets;
    }

    protected function generateHighlight() {
        return $this->highlight;
    }

    protected function generateFilter() {
        $j = 0;
        $terms = [];

        foreach (array_keys($this->filter_fields) as $key) {
            $filter_field = $key;
            $new_filter_values = [];
            $filter_values = $this->filter_fields[$key];

            foreach ($filter_values as $filter_value) {
                if (strpos($filter_value, ',') && !strpos($filter_value, '"')) {
                    $filter_value = '"' . $filter_value . '"';
                }
                array_push($new_filter_values, $filter_value);
            }
            $terms[$j] = ['terms' => [$filter_field => [$new_filter_values]]];
            $j += 1;
        }

        $filter = ['and' => $terms];
        return $filter;
    }

    protected function generateAggs() {
        return [];
    }

    // Generates the sorting part of the query.
    protected function generate_sort() {
        $sort = [];
        if ($this->sort == 'date') {
            $sort = ['date' => ['order' => 'desc', 'missing' => '_last', 'unmapped_type' => 'string']];
        } elseif ($this->sort == 'citation') {
            $sort = ['citations' => ['order' => 'desc', "mode" => 'avg',  'missing' => '_last', 'unmapped_type' => 'integer']];
        } else {
            $sort = [];
        }
        return $sort;
    }

    // Generates the data selection part of the query.
    protected function generateQuery() {
        if (preg_match('/(AND|OR|NOT|\[|\])/', $this->query)) {
            // Get query term
            $quertterm = preg_replace("/\[[^)]+\]/", "", $this->query);
            if (substr($quertterm, 0, 5) == "&#34;" && substr($quertterm, -5, 5) == "&#34;") { //if token is wrapper by double quote, query it as a phrase
                $quertterm = (trim($quertterm, "&#34;"));
            }

            // Get search fields
            preg_match_all("/\[(.*)\]/", $this->query, $mo);
            $queryfields = strtolower(trim($mo[0][0], "\[\]"));

            $this->query=$quertterm;
            $this->search_fields=$this->fieldsExpansion($queryfields);

        }
        //$this->update_query_string(); //for handle "query" as phrase case
        if (count(array_keys($this->filter_fields)) < 1) {
            $query_part = [
                'bool' => [
                    'should' => [
                        'multi_match' => [
                            'query' => $this->query,
                            'fields' => $this->search_fields,
                            'operator' => 'and',
                            'type' => $this->querytype]
                    ]
                ]
            ];
        } else {
            $filter = $this->generateFilter();
            $query_part = [
                'filtered' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $this->query,
                            'fields' => $this->search_fields,
                            'operator' => 'and',
                          'type'=>$this->querytype
                        ]
                    ],
                    'filter' => $filter
                ]
            ];
        }
       // print_r($query_part);
        return $query_part;
    }

    // Generates the main body of the query.
    protected function generateSearchBody() {
        $facets = $this->generateFacets();

        $search_query = $this->generateBoolQuery();
        $sort = $this->generate_sort();
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
                'aggs' => $facets //'facets'=>$facets
            ];
        }
        if (count(array_keys($sort) >= 1)) {
            $body['sort'] = $sort;
        }
        return $body;
    }

    // Generates the query for Boolean search
    protected function generateBoolQuery(){
        $parser=new Parser();
        $parsedQuery = $parser->set_Content($this->query)->tokenize()->parse();

        if(count($parsedQuery)==1){
            $query_part=$this->generateQuery();

        }else {

            if (count(array_keys($this->filter_fields)) < 1) {
                $query_part = $this->recursiveConstructBoolQuery($parsedQuery);
            } else {
                $filter = $this->generateFilter();
                $query_part = array('filtered' => array('query' => $this->recursiveConstructBoolQuery($parsedQuery), 'filter' => $filter));
            }

        }
        return $query_part;
    }

    protected function recursiveConstructBoolQuery($parsedQuery){
        $query_part=array('bool'=>array('must'=>array(),
            'should'=>array(),
            'must_not'=>array()));

        $oprArray=[
            "AND"=>"must",
            "OR"=>"should",
            "NOT"=>"must_not"
        ];

        $size = count($parsedQuery);
        for($i=0;$i<$size;$i++){
            if($i%2==0){
                $query = $parsedQuery[$i];  // Get query term

                if($i==0){
                    $operator=$parsedQuery[$i+1];
                    if($operator=="NOT"){
                        $operator = "AND";
                    }
                }else{
                    $operator=$parsedQuery[($i-1)];
                }

                if(is_array($query)){
                    array_push($query_part['bool'][$oprArray[$operator]],$this->recursiveConstructBoolQuery($query));
                }else{
                    // Get query term
                    $quertterm =preg_replace("/\[[^)]+\]/","",$query);
                    if(substr($quertterm,0,5)=="&#34;" && substr($quertterm,-5,5)=="&#34;"){ //if token is wrapper by double quote, query it as a phrase
                        $quertterm=(trim($quertterm, "&#34;"));
                    }

                    // Get search fields
                    preg_match_all("/\[(.*)\]/", $query, $mo);
                    $queryfields=strtolower(trim($mo[0][0],"\[\]"));

                    if($queryfields==null||$queryfields=="all search fields"){
                        $queryfields = $this->search_fields;
                    }

                    array_push($query_part['bool'][$oprArray[$operator]],
                        array('multi_match'=>array('query' =>$quertterm,'fields' =>$queryfields, "operator"=>"and")));
                }

            }
        }

        return $query_part;
    }

    function fieldsExpansion($queryField){
        $queryfieldsArray = array();
        switch($queryField){
            case "all search fields":
                $queryfieldsArray=$this->search_fields;
                break;
            case "title":
                $queryfieldsArray = array("dataItem.title","title","dataset.title","Dataset.title");
                break;
            case "author":
                $queryfieldsArray = array("author","dataset.creator");
                break;
            case "description":
                $queryfieldsArray = array(" dataItem.description","desc","description","dataset.description","dataset.note","Dataset.description");
                break;
            default:
                $queryfieldsArray = array($queryField);
        }

        return $queryfieldsArray;
    }
}

?>
