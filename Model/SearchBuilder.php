<?php

require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/RepositoriesRep.php';
require_once dirname(__FILE__) . '/Repositories.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';
require_once dirname(__FILE__) . '/ExpansionSearch.php';
require_once dirname(__FILE__) . '/DuplicateExpansionSearch.php';
require_once dirname(__FILE__) . '/BooleanSearch.php';
require_once dirname(__FILE__) . '/Utilities.php';
require_once dirname(__FILE__) . '/WriteMysqlLog.php';
require_once dirname(__FILE__) . '/DBController.php';
date_default_timezone_set('America/Chicago');

/*
 * Parse and send parameters to Elasticsearch class.
 * Get search results from Elasticsearch class.
 */

class SearchBuilder {
    /*
     * Constant variables
     * should be moved to config/datasources.php
     */

    public $DATATYPES_MAPPING;  // map data type to index
    public $ACCESS_MAPPING;     // map access type to index
    public $AUTH_MAPPING;       // map authorization type to index
    public $ALL_DATA_TYPES;     // a list of all data types
    public $ALL_ACCESS_TYPES;   // a list of all access types
    public $ALL_AUTH_TYPES;     // a list of all authorization types
    public $ALL_ELASTICSEARCH_INDEXES;      // a list of all elasticsearch indexes
    public $PAGE_NAME; //search.php or search-repository.php
    // required properties provided by users
    /*
     * search query entered by users
     * @var string
     */
    private $query;

    /*
     * define the type of search, the value could be data set or repository
     * @var string
     */
    private $searchType;
    // optional properties provided by users
    /*
     * repositories selected by users, e.g. ['0001','0002',...]
     * @var array(string)
     */
    private $selectedRepositories;

    /*
     * data types selected by users
     * @var array(string)
     */
    private $selectedDatatypes;

    /*
     * page number of current page
     * @var int
     */
    private $offset;

    /*
     * number of rows per page
     * @var int
     */
    private $rowsPerPage;

    /*
     * search results are sort by $sort, the value could be 'relevance', 'date'...
     * @var string
     */
    private $sort;

    /*
     * accessibility type selected by users
     * @var string
     */
    private $selectedAccess;


    /*
     * authorization type selected by users
     * @var string
     */
    private $selectedAuth;


    /*
     * Elasticsearch index name of the selected repositories
     * @var string (joined by ',')
     * */
    private $elasticSearchIndexes;

    /*
     * Elasticsearch index name of the selected repositories, e.g. ""pdb,mpd,geo"...
     * @var string (joined by ',')
     * */
    private $selectedElasticSearchIndexes;


    /*
     * range of the selected rows for current page ($start . '-' . $end)
     * @var string
     */
    private $rowRange;

    /*
     * an instance of the repositories class
     * @var object
     */
    private $repositories;


    /*
     * flag to indicate whether the query expansion is needed or not
     * 0 for expansion, 1 for no expansion
     * @var int
     */
    private $expansionFlag = 0;

    /*
     * synonyms of the query
     * @var string
     * */
    private $expandedQuery = [];
    private $expandedQueryArray =[];

    /*
     * search results from ElasticSearch, an instance of ElasticSearch class or ExpansionSearch class
     * @var object
     */
    private $elasticSearchResults;
    private $elasticSearchResults_without_duplicate;
    private $input_array_for_duplicate;
    /*
     * number of search results and selection status of the data types
     * @var array(array(string))
     */
    private $datatypes;

    /*
     * number of search results and selection status of the access types
     * @var array(array(string))
     */
    private $access;

    /*
     * number of search results and selection status of the authorization types
     * @var array(array(string))
     */
    private $auth;

    /*
     * total number of search results from all repositories
     * @var int
     */
    private $totalRows;

    /*
     * total number of search results from selected repositories
     * @var int
     */
    private $selectedTotalRows;

    private $year;


    /************
     * search-repository page
     * ********** */


    /*
     * Selected filters
     * */
    private $selectedFilters;
    /*
     * is from linkout
     * */
    private $isLinkout;

    /*
     * All filters for current repository
     */
    private $filters;


    /*
     * search results from Elasticsearch, an instance of elasticsearch class or expansionsearch class
     * search results for a single repository
     * @var object
     */
    private $singleRepoElasticSearchResults;


    /*
     * search results from Elasticsearch, an instance of elasticsearch class or expansionsearch class
     * search results for a single repository without selected filters
     * @var object
     */
    private $singleRepoResultsNoFilter;


    /*
     * json data for the generation of timeline chart
     *
     * */
    private $timelineData;

    public $timelineFlag;

    public $sort_field='';
    public $booleanFlag = false;
    public $booleanDetail='';
    function __construct() {
        // The functions below are from config/datasources.php file
        // should be moved to config/datasources.php?
        $this->DATATYPES_MAPPING = getDatatypesMapping();
        $this->ACCESS_MAPPING = getAccessibilityMapping();
        $this->AUTH_MAPPING = getAuthMapping();
        $this->ALL_DATA_TYPES = getDatatypes();
        $this->ALL_ACCESS_TYPES = getAllAccess();
        $this->ALL_AUTH_TYPES = getAllAuth();
        $this->ALL_ELASTICSEARCH_INDEXES = getElasticSearchIndexes();
        $this->PAGE_NAME = basename($_SERVER['PHP_SELF']);

        /*
         * 1. Load parameters
         * */
        $this->setSearchType();
        $this->setYear();
        $this->setQuery();
        $this->setOffset();
        $this->setSort();
        $this->setRowRange();
        $this->setRowsPerPage();
        $this->setSelectedDatatypes();
        $this->setSelectedRepositories();
        $this->setSelectedAccess();
        $this->setSelectedAuth();
        $this->setSelectedFilters();
        $this->isLinkout();
        // initialize repositories class
        if ($this->getSearchType() == 'repository') {
            $this->repositories = new RepositoriesRep();
        } else {
            $this->repositories = new Repositories();
        }

        // set expansion flag
        if (isBoolSearch($this->getQuery()) == 0) {
            $this->expansionFlag = 0; // not boolean search, will use expansion
        } else {
            $this->expansionFlag = 1;
        }

        $this->setElasticSearchIndexes();

        /* Track user's activity */
        $log_date = date("Y-m-d H:i:s");
        ;
        $message = $this->query . ' (' . $this->searchType . ')';
        $user_email = @$_SESSION['email'];
        $objDBController = new DBController();
        $dbconn = $objDBController->getConn();
        $referral = @$_SERVER["HTTP_REFERER"];

        write_mysql_log($dbconn, $log_date, $message, $user_email, session_id(), $referral);
    }

    public function searchAllRepo() {
        // prepare parameters for the search instance
        $input_array = [];

        $input_array['query'] = $this->getQuery();
        //$input_array['sort'] = $this->getSort();
        $input_array['aggsFields'] = ['_index'];
        $input_array['facetSize'] = 300;
        $input_array['offset'] = 1;
        $input_array['rowsPerPage'] = 0;

        /*
         * generate search results from all repositories
         * */
        $input_array['esIndex'] = $this->ALL_ELASTICSEARCH_INDEXES;
        $input_array['searchFields'] = ["_all"];
        if($this->isLinkout=='linkout'){
            $input_array['searchFields'] = ['primaryPublications.ID','primaryPublication.ID','publication.ID','PrimaryPublication.ID'];
        }

        if($this->getYear()!=""){
            $input_array['year'] = $this->getYear();
        }


        $esResults = $this->setElasticSearchResults($input_array);
        $this->input_array_for_duplicate=$input_array;
        $this->totalRows = $this->setTotalRows($esResults);
        $this->setRepositoryRows($esResults);   // set number of rows for each repository

        if ($this->getSelectedRepositories() != NULL
                or $this->getSelectedAuth() != NULL
                or $this->getSelectedAccess() != NULL
                or $this->getSelectedDatatypes() != NULL) {
            $this->elasticSearchResults = $esResults;
            $this->selectedTotalRows = $this->totalRows;
        }
    }

    public function searchSelectedRepo() {
        /*
         *  Generate search results for selected repositories
         * */

        // prepare parameters for the search instance
        $input_array = [];

        $input_array['query'] = $this->getQuery();
        //$input_array['sort'] = $this->getSort();
        $input_array['aggsFields'] = ['_index'];
        if ($this->getRowsPerPage() == 0) {
            $input_array['facetSize'] = 300;
            $input_array['offset'] = 1;
            $input_array['rowsPerPage'] = 20;
        } else {
            $input_array['facetSize'] = 300;
            $input_array['offset'] = $this->getOffset();
            $input_array['rowsPerPage'] = $this->getRowsPerPage();
        }

        $input_array['timelineFlag'] = $this->timelineFlag;

        $input_array['esIndex'] = $this->getElasticSearchIndexes();
        $input_array['searchFields'] = ["_all"];
        if($this->isLinkout=='linkout'){
            $input_array['searchFields'] = ['primaryPublications.ID','primaryPublication.ID','publication.ID','PrimaryPublication.ID'];
        }

        if($this->getYear()!="" && $this->timelineFlag==false){
            $input_array['year'] = $this->getYear();
        }

        // generate elasticsearch results
        $this->elasticSearchResults = $this->setElasticSearchResults($input_array);
        $this->input_array_for_duplicate=$input_array;
        $this->selectedTotalRows = $this->setTotalRows($this->getElasticSearchResults());

        $this->setAggByDate($this->elasticSearchResults);
    }

    /*
     * If on search repository, generate search results for single repository
     * */

    public function searchSingleRepo() {
        $repository = $this->repositories->getRepository($this->selectedRepositories[0]);

        unset($input_array);
        $input_array = [];
        $input_array['esIndex'] = $repository->index;
        $input_array['esType'] = $repository->type;
        $input_array['query'] = $this->getQuery();
        $input_array['sort'] = $this->getSort();

        $input_array['sort_field']= $repository->sort_field;
        $input_array['searchFields'] = ['_all'];//array_merge($repository->searchFields,['_all']);

        $input_array['aggsFields'] = $repository->facetsFields;
        $input_array['filterFields'] = ($this->getSelectedFilters() != NULL) ? $this->selectedFilters : [];
        $input_array['offset'] = $this->getOffset();
        $input_array['rowsPerPage'] = $this->getRowsPerPage();
        if($this->isLinkout=='linkout'){
            $input_array['searchFields'] = ['primaryPublications.ID','primaryPublication.ID','publication.ID','PrimaryPublication.ID'];
        }

        if($this->getYear()!=""){
            $input_array['year'] = $this->getYear();
        }


        $this->setSingleRepoElasticSearchResults($this->setElasticSearchResults($input_array));
        $this->selectedTotalRows = $this->getSingleRepoElasticSearchResults()['hits']['total'];
        $this->sort_field = $repository->sort_field;
        // Search without filters
        $input_array['filterFields'] = [];

        $this->setSingleRepoResultsNoFilter($this->setElasticSearchResults($input_array));
    }

    /*
     * Set the number of search results for each repository
     * */

    public function setRepositoryRows($esResults) {
        $repositories_counts = [];

        foreach ($esResults['aggregations']['_index']['buckets'] as $bucket) {
            $key = explode('_', $bucket['key'])[0];
            $repositories_counts[$key] = $bucket['doc_count'];
        }

        foreach ($this->repositories->getRepositories() as $repository) {
            if (isset($repositories_counts[$repository->index])) {
                $repository->num = $repositories_counts[$repository->index];
            }
        }
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Get query from http GET
     * @return boolean
     */
    public function setQuery() {
        $this->query = trim(filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING));

        if ($this->query === NULL || strlen($this->query) == 0) {
            $this->query = " ";
        }

        // To track user's search history
        trackSearchActivity($this->getQuery(), $this->getSearchType());

        return true;
    }

    /**
     * @return string
     */
    public function getSearchType() {
        return $this->searchType;
    }

    /**
     * get search type from http GET
     * @return boolean
     */
    public function setSearchType() {
        $this->searchType = filter_input(INPUT_GET, "searchtype", FILTER_SANITIZE_STRING);
        return true;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear()//$year)
    {
        $this->year = filter_input(INPUT_GET, "year", FILTER_SANITIZE_STRING);
        return true;
    }
    /**
     * @return int
     */
    public function getOffset() {
        return $this->offset;
    }

    /**
     * get offset from http GET
     * default value is 1
     */
    public function setOffset() {
        $this->offset = filter_input(INPUT_GET, "offset", FILTER_SANITIZE_STRING);
        if ($this->offset === NULL) {
            $this->offset = 1;
        } else {
            $this->offset = intval($this->offset);
        }
    }

    /**
     * @return int
     */
    public function getRowsPerPage() {
        return $this->rowsPerPage;
    }

    /**
     * get number of rows per page from http GET
     * default value is 20
     */
    public function setRowsPerPage() {
        $this->rowsPerPage = filter_input(INPUT_GET, "rowsPerPage", FILTER_SANITIZE_STRING);
        if (!isset($this->rowsPerPage)) {
            $this->rowsPerPage = 20;
        }
    }

    /**
     * @return string
     */
    public function getSort() {
        return $this->sort;
    }

    /**
     * get sort method from http GET
     * default value is 'relevance'
     */
    public function setSort() {
        $this->sort = filter_input(INPUT_GET, "sort", FILTER_SANITIZE_STRING);
        if (!isset($this->sort)) {
            $this->sort = "relevance";
        }
    }
    public function isLinkout(){
        $this->isLinkout = filter_input(INPUT_GET, "external", FILTER_SANITIZE_STRING);
    }
    /**
     * @return array(string)
     */
    public function getSelectedDatatypes() {
        return $this->selectedDatatypes;
    }

    /**
     * get selected data types from http GET
     */
    public function setSelectedDatatypes() {
        $datatypes = filter_input(INPUT_GET, "datatypes", FILTER_SANITIZE_STRING);
        if (isset($datatypes)) {
            $this->selectedDatatypes = explode(',', $datatypes);
        }
    }

    /**
     * @return array(string)
     */
    public function getSelectedAccess() {
        return $this->selectedAccess;
    }

    /**
     * get selected access types from http GET
     */
    public function setSelectedAccess() {
        $access = filter_input(INPUT_GET, "access", FILTER_SANITIZE_STRING);
        if (isset($access)) {
            $this->selectedAccess = explode(',', $access);
        }
    }

    /**
     * @return array(string)
     */
    public function getSelectedAuth() {
        return $this->selectedAuth;
    }

    /**
     * get selected authorization types from http GET
     */
    public function setSelectedAuth() {
        $auth = filter_input(INPUT_GET, "auth", FILTER_SANITIZE_STRING);
        if (isset($auth)) {
            $this->selectedAuth = explode(',', $auth);
        }
    }

    /**
     * @return int
     */
    public function getExpansionFlag() {
        return $this->expansionFlag;
    }

    /**
     * @param int $expansionFlag
     */
    public function setExpansionFlag($expansionFlag) {
        $this->expansionFlag = $expansionFlag;
    }

    /**
     * @return array(string)
     */
    public function getSelectedRepositories() {
        return $this->selectedRepositories;
    }
    public function getSortFieldSingleRepo(){
         return $this->sort_field;
    }
    /**
     * get selected repositories from http GET
     */
    public function setSelectedRepositories() {
        $repositories = filter_input(INPUT_GET, "repository", FILTER_SANITIZE_STRING);
        if (isset($repositories)) {
            $this->selectedRepositories = explode(',', $repositories);
        }
    }

    /**
     * @return string
     */
    public function getElasticSearchIndexes() {
        return $this->elasticSearchIndexes;
    }

    /**
     *  Get elasticsearch index of selected repositories only
     * @return string
     */
    public function getSelectedElasticSearchIndexes() {
        return $this->selectedElasticSearchIndexes;
    }

    /**
     * Get elasticsearch index of selected repositories
     * If no selected repository, return all repositories
     * @return String
     */
    public function setElasticSearchIndexes() {
        if ($this->getSearchType() == 'repository') {       // search for repository
            $this->elasticSearchIndexes = "repository";
        } else {                                            // search for data set
            if ($this->selectedDatatypes != NULL) {
                $this->selectedElasticSearchIndexes = $this->elasticSearchIndexes = $this->mapDataTypeToIndex();
            } elseif ($this->selectedAccess != NULL) {
                $this->selectedElasticSearchIndexes = $this->elasticSearchIndexes = $this->mapAccesstoIndex();
            } elseif ($this->selectedAuth != NULL) {
                $this->selectedElasticSearchIndexes = $this->elasticSearchIndexes = $this->mapAuthtoIndex();
            } elseif ($this->selectedRepositories != NULL) {
                $index = [];
                foreach ($this->selectedRepositories as $selectedRepository) {
                    array_push($index, $this->repositories->getRepository($selectedRepository)->index);
                }
                $this->selectedElasticSearchIndexes = $this->elasticSearchIndexes = implode(',', $index);
            } else {                                        // no selected repository, return all repositories
                foreach ($this->DATATYPES_MAPPING as $index) {
                    //To block the clinicaltrials result
                    //if (in_array("clinicaltrials", $index)) {
                    //    unset($index[0]);
                    //}
                    $this->elasticSearchIndexes .= implode(',', $index) . ',';
                }
            }
        }
    }

    /**
     * @return object
     */
    public function getElasticSearchResults() {
        return $this->elasticSearchResults;
    }

    /**
     * Call Elasticsearch class to generate search result
     * @input input array
     * @return search result from elasticsearch
     */
    public function setElasticSearchResults($input_array) {
        if ($this->expansionFlag == 1 || $this->query == " ") {
            // If advanced search
            $search = new BooleanSearch($input_array);
            $this->booleanFlag = True;

        } else {
            // If simple search
            $search = new ExpansionSearch($input_array);
            $this->setExpandedQuery($search);   // Generate synonyms
        }

        $result = $search->getSearchResult();
        if($this->booleanFlag){
            $this->booleanDetail=$search->search_details;
        }

        return $result;
    }
    public function getElasticSearchResults_without_duplicate() {
        $this->setElasticSearchResults_without_duplicate($this->input_array_for_duplicate);
        return $this->elasticSearchResults_without_duplicate;
    }

    /**
     * Call Elasticsearch class to generate search result
     * @input input array
     * @return search result from elasticsearch
     */
    public function setElasticSearchResults_without_duplicate($input_array) {
        if ($this->expansionFlag == 1 || $this->query == " ") {
            // If advanced search
            $search = new BooleanSearch($input_array);
            $this->booleanFlag = True;
            $this->booleanDetail = $search->search_details;
        } else {
            // If simple search
            $search = new DuplicateExpansionSearch($input_array);
            //$this->setExpandedQuery($search);   // Generate synonyms
        }

        $result = $search->getSearchResult();
        $this->elasticSearchResults_without_duplicate = $result;
        return $result;
    }
    /**
     * @return mixed
     */
    public function getSingleRepoElasticSearchResults() {
        return $this->singleRepoElasticSearchResults;
    }

    /**
     * @param mixed $singleRepoElasticSearchResults
     */
    public function setSingleRepoElasticSearchResults($singleRepoElasticSearchResults) {
        $this->singleRepoElasticSearchResults = $singleRepoElasticSearchResults;
    }

    /**
     * @return mixed
     */
    public function getSingleRepoResultsNoFilter() {
        return $this->singleRepoResultsNoFilter;
    }

    /**
     * @param mixed $singleRepoResultsNoFilter
     */
    public function setSingleRepoResultsNoFilter($singleRepoResultsNoFilter) {
        $this->singleRepoResultsNoFilter = $singleRepoResultsNoFilter;
    }

    /**
     * @return int
     */
    public function getSelectedTotalRows() {
        return $this->selectedTotalRows;
    }

    /**
     * @return int
     */
    public function getTotalRows() {
        return $this->totalRows;
    }

    /**
     * input: elasticsearch results
     * ouput: total number of search results
     */
    public function setTotalRows($esResults) {
        $totalRow = 0;
        $repositories_counts = [];

        if($esResults['aggregations']['_index']['buckets']!=NULL){
            foreach ($esResults['aggregations']['_index']['buckets'] as $bucket) {
                $key = explode('_', $bucket['key'])[0];
                $repositories_counts[$key] = $bucket['doc_count'];
                $totalRow += $repositories_counts[$key];
            }
        }

        return $totalRow;
    }


    /**
     * @return int
     */
    public function getAggByDate() {
        return $this->timelineData;
    }

    public function setAggByDate($esResults){
        $this->timelineData = [];

        $yearCount = [];
        if($esResults['aggregations']!=NULL && !array_key_exists('datasets_over_time',$esResults['aggregations'])){
            return;
        }

        if($esResults['aggregations']['datasets_over_time']['buckets']!=NULL){


        foreach ($esResults['aggregations']['datasets_over_time']['buckets'] as $bucket) {

            $year = substr($bucket['key_as_string'],0,4);
            // Remove year 1970
            if($year != 1970){
                $count = $bucket['doc_count'];

                if(array_key_exists($year,$yearCount)){
                   break;
                }else{
                    if(date("Y")>=$year){
                        $yearCount[$year] = $count;
                    }
                }

            }
        }
        }
        //$yearCount['NULL'] = $esResults['aggregations']['no_date']['doc_count'];

        foreach($yearCount as $key=>$value){
            $tmp['year'] = $key;
            $tmp['frequency'] = $value;
            array_push($this->timelineData,$tmp);
        }

        $this->timelineData = json_encode($this->timelineData);
    }

    /**
     * @return array(array(string))
     */
    public function getDatatypes() {
        return $this->datatypes;
    }

    /**
     * Load all data types, their row counts, and selection status
     */
    public function setDatatypes() {
        $this->datatypes = [];
        foreach ($this->ALL_DATA_TYPES as $datatype) {    // Loop all data types
            $rows = 0;

            foreach ($this->repositories->getRepositories() as $repository) {   // Loop all repositories
                if (in_array($repository->index, $this->DATATYPES_MAPPING[$datatype])) {
                    $rows += $repository->num;
                }
            }
            $this->datatypes[$datatype]['rows'] = $rows;
            if (isset($this->selectedDatatypes) && in_array($datatype, $this->selectedDatatypes)) {
                $this->datatypes[$datatype]['selected'] = true;
            } else {
                $this->datatypes[$datatype]['selected'] = false;
            }
        }
    }

    /**
     * @return array(array(string))
     */
    public function getAccess() {
        return $this->access;
    }

    /**
     * Load access types, their row counts, and selection status
     */
    public function setAccess() {
        $this->access = [];
        foreach ($this->ALL_ACCESS_TYPES as $access) {
            $rows = 0;

            foreach ($this->repositories->getRepositories() as $repository) {
                if (in_array($repository->index, $this->ACCESS_MAPPING[$access])) {
                    $rows += $repository->num;
                }
            }
            $this->access[$access]['rows'] = $rows;
            if (isset($this->selectedAccess) && in_array($access, $this->selectedAccess)) {
                $this->access[$access]['selected'] = true;
            } else {
                $this->access[$access]['selected'] = false;
            }
        }
    }

    /**
     * @return array(array(string))
     */
    public function getAuth() {
        return $this->auth;
    }

    /**
     * Load authorization types, their row counts, and selection status
     */
    public function setAuth() {
        $this->auth = [];
        foreach ($this->ALL_AUTH_TYPES as $auth) {
            $rows = 0;

            foreach ($this->repositories->getRepositories() as $repository) {
                if (in_array($repository->index, $this->AUTH_MAPPING[$auth])) {
                    $rows += $repository->num;
                }
            }
            $this->auth[$auth]['rows'] = $rows;
            if (isset($this->selectedAuth) && in_array($auth, $this->selectedAuth)) {
                $this->auth[$auth]['selected'] = true;
            } else {
                $this->auth[$auth]['selected'] = false;
            }
        }
    }

    /**
     * @return string
     */
    public function getExpandedQuery() {
        return $this->expandedQuery;
    }
    public function getExpandedQueryArray() {
        return $this->expandedQueryArray;
    }
    /**
     * @param an object of the expansionSearch class
     */
    public function setExpandedQuery($search) {
        $this->expandedQuery = $search->getSynonyms();
        $this->expandedQueryArray = $search->getSynonymsArray();

    }

    /**
     * @return string
     */
    public function getRowRange() {
        return $this->rowRange;
    }

    /**
     * Row range of the current page
     */
    public function setRowRange() {
        $start = ((($this->offset - 1) * $this->getRowsPerPage()) + 1);
        $end = (($this->offset - 1) * $this->getRowsPerPage()) + $this->getRowsPerPage();
        $this->rowRange = $start . '-' . $end;
    }

    /**
     * Mannually Set Range For Sharing.
     */
    public function setRangeForSharing() {
        $this->offset = 1;
        $this->rowsPerPage = 1000;
    }

    /**
     * @return Repositories
     */
    public function getRepositories() {
        return $this->repositories;
    }

    /**
     * @param Repositories $repositories
     */
    public function setRepositories($repositories) {
        $this->repositories = $repositories;
    }

    public function setSelectedFilters() {
        $this->selectedFilters = [];
        $filters = filter_input(INPUT_GET, "filters", FILTER_SANITIZE_STRING);

        if (isset($filters) && $filters != "") {
            $facets = explode('$', $filters);
            foreach ($facets as $facet) {
                $facetParts = explode('@', $facet);
                $keys = explode(',', $facetParts[1]);
                $this->selectedFilters[$facetParts[0]] = $keys;
            }
        }
    }

    public function getSelectedFilters() {
        return $this->selectedFilters;
    }

    /*
     * Find all indexes with the selected authorization types
     * @return string
     */

    public function mapAuthtoIndex() {
        $elasticSearchIndexes = '';
        foreach ($this->selectedAuth as $index) {
            $elasticSearchIndexes .= implode(',', $this->AUTH_MAPPING[$index]) . ',';
        }
        return $elasticSearchIndexes;
    }

    /*
     * Find all indexes with the selected access types
     * @return string
     */

    public function mapAccesstoIndex() {
        $elasticSearchIndexes = '';
        foreach ($this->selectedAccess as $index) {
            $elasticSearchIndexes .= implode(',', $this->ACCESS_MAPPING[$index]) . ',';
        }
        return $elasticSearchIndexes;
    }

    /*
     * Find all indexes with the selected data types
     * @return string
     */

    public function mapDataTypeToIndex() {
        $elasticSearchIndexes = '';
        foreach ($this->selectedDatatypes as $index) {
            $elasticSearchIndexes .= implode(',', $this->DATATYPES_MAPPING[$index]) . ',';
        }
        return $elasticSearchIndexes;
    }

    /*
     * Get the search details
     * @return String
     */

    public function getSearchDetails() {
        $query = $this->getQuery();
        $detail = "(" . $this->getSearchType() . ')"' . $query . '"';
        $synonyms_array = $this->getExpandedQueryArray();
        if (sizeof($synonyms_array)==0){
            if(!$this->booleanFlag){
                return $detail;
            }
            else{
                return "(" . $this->getSearchType() . ')' .$this->showBooleanExpansionDetails();
            }

        }
        $syn_details = '[';
        foreach(array_keys($synonyms_array) as $key){
            $subdetail = '("';
            foreach ($synonyms_array[$key] as $synonyms) {
                if($subdetail=='("'){
                    $subdetail = $subdetail . $synonyms . '"';
                }
                else{
                    $subdetail = $subdetail . ' OR "' . $synonyms . '"';
                }

            }
            if($syn_details=='['){
                $syn_details = $syn_details . $subdetail.')';
            }
            else{
                $syn_details = $syn_details . ' AND '.$subdetail.')';
            }

        }
        $syn_details = $syn_details.']';
        $detail = $detail .' OR '.$syn_details;
        return $detail;
    }
    public function showBooleanExpansionDetails(){
        return $this->booleanDetail;
    }

}
