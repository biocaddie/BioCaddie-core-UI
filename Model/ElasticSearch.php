<?php

/**
 * Created by PhpStorm.
* User: xchen2
* Date: 7/7/16
* Time: 2:27 PM
* Class to construct ES query and get ES resul
*/
require_once dirname(__FILE__) . '/../config/datasources.php';

class ElasticSearch
{ /*
* ES search parameters
*/

public $esIndex;
public $esType;
public $query;
public $year;
public $searchFields = [];
public $filterFields = [];
public $aggsFields = [];
public $offset = 1;
public $rowsPerPage = 20;
public $facetSize = 10;
public $highlight = ['fields' => ['*' => ["pre_tags" => ["<strong1>"], "post_tags" => ["</strong1>"], "number_of_fragments" => 0]]];
public $queryType = 'most_fields';
public $sort;
public $sort_field;
public $searchResult;
public $timelineFlag = false;

public function __construct($input_array)
{
	if (array_key_exists('esIndex', $input_array)) {
		$this->esIndex = $input_array['esIndex'];
	}
	if (array_key_exists('searchFields', $input_array)) {
		$this->searchFields = $input_array['searchFields'];
	}
	if (array_key_exists('query', $input_array)) {
		$this->query = $input_array['query'];
	}
	if (array_key_exists('esType', $input_array)) {
		$this->esType = $input_array['esType'];
	}
	if (array_key_exists('filterFields', $input_array)) {
		$this->filterFields = $input_array['filterFields'];
	}
	if (array_key_exists('aggsFields', $input_array)) {
		$this->aggsFields = $input_array['aggsFields'];
	}
	if (array_key_exists('offset', $input_array)) {
		$this->offset = $input_array['offset'];
	}
	if (array_key_exists('rowsPerPage', $input_array)) {
		$this->rowsPerPage = $input_array['rowsPerPage'];
	}
	if (array_key_exists('facetSize', $input_array)) {
		$this->facetSize = $input_array['facetSize'];
	}
	if (array_key_exists('sort', $input_array)) {
		$this->sort = $input_array['sort'];

	}
	if (array_key_exists('sort_field', $input_array)) {
		$this->sort_field = $input_array['sort_field'];

	}
	if (array_key_exists('timelineFlag', $input_array)) {
		$this->timelineFlag = $input_array['timelineFlag'];
	}
	if (array_key_exists('year', $input_array)) {
		$this->year = $input_array['year'];
	}
	if (array_key_exists('highlight', $input_array)) {
		$this->highlight = $input_array['highlight'];
	}

}

/*
 * if token is wrapper by double quote, query it as a phrase
 */

public function updateQueryString()
{
	if (substr($this->query, 0, 5) == "&#34;" && substr($this->query, -5, 5) == "&#34;") {
		$this->query = trim($this->query, "&#34;");
		$this->queryType = 'phrase';
	}
}

/*
 * Generate filter query part
 * @return array(string)
 */

protected function generateFilter()
{
	$j = 0;
	$terms = [];

	foreach (array_keys($this->filterFields) as $key) {
		$filter_field = $key;
		$new_filter_values = [];
		$filter_values = $this->filterFields[$key];

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

	if ($this->year != "") {
		$range = ['dataset.dateReleased' => ['gte' => $this->year . "||/y", 'lte' => $this->year . '||/y', 'format' => 'yyyy']];
		array_push($filter['and'], ['range' => $range]);
	}
	if(isset($_GET['scoremax'])){
		$scoreMax=floatval($_GET['scoremax']);
		array_push($filter['and'],['range'=>['analyticsDiploid.score' => ['gte' => $scoreMax]]]);
	}
	if(isset($_GET['ancestry']) && isset($_GET['fraction']) && isset($_GET['ancestrycheck']) && $_GET['fraction']!="0"){
		//$x = explode('_',$_GET['ancestry']);
		$ancestry=$_GET['ancestry'];
		$ancestryScore = floatval($_GET['fraction']);
		array_push($filter['and'],['range'=>['analyticsDiploid.'.$ancestry => ['gte' => $ancestryScore]]]);
	}

	/*echo '<pre>';
	 print_r($filter);
	 echo '</pre>';*/
	return $filter;
}



    /*
     * generate query part for ES
     * @return array(string)
     */

    protected function generateQuery()
    {

        if (count(array_keys($this->filterFields)) < 1 && $this->year == "") {

            $query_part = [
                'bool' => [
                    'should' => [
                        'multi_match' => [
                            'query' => $this->query,
                            'fields' => $this->searchFields,
                            'operator' => 'and',
                            'type' => $this->queryType]
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
                            'fields' => $this->searchFields,
                            'operator' => 'and',
                            'type' => $this->queryType
                        ]
                    ],
                    'filter' => $filter
                ]
            ];
        }

        return $query_part;
    }

    /*
     * generate aggs part in ES
     * @return array(string)
     */

    protected function generateFacets()
    {
        $facets = [];

        for ($i = 0; $i < count($this->aggsFields); $i++) {
            $facets[$this->aggsFields[$i]] = [
                'terms' => [
                    'field' => $this->aggsFields[$i],
                    'size' => $this->facetSize]
            ];


            if ($this->timelineFlag == true) {
                $facets['datasets_over_time'] = [
                    'date_histogram' => [
                        "field" => "dataset.dateReleased",
                        "interval" => "year"
                    ]
                ];

                $facets['no_date'] = [
                    'filter' => [
                        'missing' => [
                            'field' => 'dataset.dateReleased'
                        ]
                    ]
                ];
            }
        }
        //Add word cloud start
        $facets['significantKeys1'] = [
            'significant_terms' => [
                //'field' => 'NLP_Fields.Meshterm',
                //'fields' => ['attributes.description','NLP_Fields.Meshterm','dataset.description'],
                'field' =>'attributes.description',
                'size' => '50'
            ]
        ];
        $facets['significantKeys2'] = [
            'significant_terms' => [
                //'field' => 'NLP_Fields.Meshterm',
                //'fields' => ['attributes.description','NLP_Fields.Meshterm','dataset.description'],
                'field' => 'NLP_Fields.Meshterm',
                'size' => '50'
            ]
        ];
        $facets['significantKeys3'] = [
            'significant_terms' => [
                //'field' => 'NLP_Fields.Meshterm',
                //'fields' => ['attributes.description','NLP_Fields.Meshterm','dataset.description'],
                'field' => 'dataset.description',
                'size' => '50'
            ]
        ];
        $facets['significantKeys4'] = [
            'significant_terms' => [
                //'field' => 'NLP_Fields.Meshterm',
                //'fields' => ['attributes.description','NLP_Fields.Meshterm','dataset.description'],
                'field' => 'dataset.title',
                'size' => '50'
            ]
        ];
        //Add word cloud end
        return $facets;
    }

    /*
     * generate sort part in ES
     * @return array(string)
     */

    protected function generateSort()
    {
        $sortSetting = [];
        $selectedSort = $this->sort;
        if ($selectedSort == 'date') {
            $sortSetting = [$this->sort_field => ["order" => "desc", "missing" => "_last", "unmapped_type" => "date"]];
        } elseif ($selectedSort == 'citations') {
            $sortSetting = ["citation" => ["order" => "desc", "mode" => "avg", "missing" => "_last", "unmapped_type" => "integer"]];
        } elseif ($selectedSort == 'title') {
            $sortSetting = ["dataset.title" => ["order" => "asc", "missing" => "_last", "unmapped_type" => "string"]];
            /*
             * } elseif ($selectedSort == 'author') {
              $sortSetting = ["title" => ["order" => "asc", "missing" => "_last", "unmapped_type" => "string"]];
             */
        } else {
            $sortSetting = [];
        }
        return $sortSetting;
    }

    /*
     * generate search body in ES
     * @return array(string)
     */

    protected function generateBody()
    {
        $facets = $this->generateFacets();
        $sort = $this->generateSort();

        $search_query = $this->generateQuery();

        $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
            'size' => $this->rowsPerPage,
            # 'min_score'=>0.005,
            'query' => $search_query,
            'highlight' => $this->highlight
        ];
        if (count($facets) > 0) {
            $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
                'size' => $this->rowsPerPage,
                #   'min_score'=>0.005,
                'query' => $search_query,
                'highlight' => $this->highlight,
                'aggs' => $facets
            ];
        }

        if (count(array_keys($sort)) > 0) {
            $body['sort'] = $sort;
        }
        // var_dump($this->rowsPerPage);
         /*echo '<pre>';
         print_r($body);
         echo '</pre>';*/
        return $body;
    }

    /*
     * generate search result from ES
     * @return array(string)
     */

    protected function generateResult($body)
    {
        global $es;
        $result = null;
        try {
            if (sizeof($this->esType) > 0) {
                $result = $es->search([
                    'index' => $this->esIndex,
                    'type' => $this->esType,
                    'body' => $body
                ]);
            } else {
                $result = $es->search([
                    'index' => $this->esIndex,
                    'body' => $body
                ]);
            }

        } catch (\Exception $e) {

            // Print out the ES error message
            /* echo "<pre>";
              print_r($e);
             echo "</pre>";*/
        }
        /*echo "<pre>";
        var_dump($this->esIndex);
        var_dump($result['hits']['total']);
        echo "</pre>";*/

        return $result;
    }

    //Add most accessed datasets start
    protected function generateResultByID($idparam,$indexparam)
    {
        global $es;
        $result = null;
        try {
            if (sizeof($this->esType) > 0) {
                $result = $es->get([
                    'id' => $idparam,
                    'index' => $indexparam,
                    'type' => "_all"
                ]);
            } else {
                $result = $es->get([
                    'id' => $idparam,
                    'index' => $indexparam,
                    'type' => "_all"
                ]);
            }

        } catch (\Exception $e) {

        }

        return $result;
    }
    //Add most accessed datasets end

    /*
     * get search result from ES
     * @return array(string)
     */

    public function getSearchResult()
    {
        return $this->searchResult;
    }

    public function setSearchResult()
    {
        $this->updateQueryString();
        $body = $this->generateBody();
        $this->searchResult = $this->generateResult($body);
    }

    //Add most accessed datasets start
    public function setSearchResultfromDataset($body)
    {
        $this->searchResult = $this->generateResult($body);
    }
    public function setSearchResultfromID($idparam,$indexparam)
    {
        $this->searchResult = $this->generateResultByID($idparam,$indexparam);
    }
    //Add most accessed datasets end
}

//use example
/* $input_array=['esIndex'=>'pdb','searchFields'=>'dataset.title','query'=>'cancer'];
 $search = new ElasticSearch($input_array);
 $result = $search->getSearchResult();
 var_dump($result); */
?>