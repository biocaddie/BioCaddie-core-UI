<?php

require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/Repositories_rep.php';
require_once dirname(__FILE__) . '/Repositories.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';
require_once dirname(__FILE__) . '/ExpansionSearch.php';
require_once dirname(__FILE__) . '/../write_mysql_log.php';
require_once dirname(__FILE__) . '/../dbcontroller.php';
date_default_timezone_set('America/Chicago');

class SearchBuilder
{

    // An instance of repositories database.
    private $expanflag = 0; //0 for expansion, 1 for no expansion
    private $repositories;
    private $query;
    private $searchtype;
    private $expanquery;
    private $itemID;
    private $orignalSearchResults;

    // Page number.
    private $offset;
    // Rows per page.
    private $rowsPerPage;
    // The range of the selected rows for current page.
    private $rowRange;
    // Sort order.
    private $sort;    


    public function setExpanflag($expanflag)
    {
        $this->expanflag = $expanflag;
    }

    public function getItemID()
    {
        return $this->itemID;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getSearchType()
    {
        return $this->searchtype;
    }

    public function setSearchtype($searchtype)
    {
        $this->searchtype = $searchtype;
    }

    public function getExpansionquery()
    {
        $search = new ExpansionSearch();
        $search->query = $this->query;
        $this->expanquery = $search->getTerminologyquery();
        return $this->expanquery;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getRowsPerPage()
    {
        return $this->rowsPerPage;
    }
    
    public function getSort()
    {
        return $this->sort;
    }   

    // Stores selected datatypes from post query parameters.
    private $selectedDatatypes;

    public function getSelectedDatatypes()
    {
        return $this->selectedDatatypes;
    }

    // Number of rows per page.
    private $totalRows;

    public function getTotalRows()
    {
        return $this->totalRows;
    }

    // Current page result set.
    private $searchResults;

    public function getSearchResults()
    {
        return $this->searchResults;
    }

    // Stores datatypes, their row counts, and selection state.
    private $datatypes;

    public function getDatatypes()
    {
        return $this->datatypes;
    }

    private $access;

    public function getAccess()
    {
        return $this->access;
    }

    private $selectedAccess;

    public function getSelectedAccess()
    {
        return $this->selectedAccess;
    }


    function __construct()
    {
        $this->loadSearchType();

        if (!$this->loadQuery()) {
            header('Location: index.php');
            exit;
        }

        if ($this->getSearchType() == 'repository') {
            $this->repositories = new Repositories_rep();
        } else {
            $this->repositories = new Repositories();

        }

        /* Track user's activity*/
        $log_date = date("Y-m-d H:i:s");;
        $message = $this->query . ' (' . $this->searchtype . ')';
        $user_email = $_SESSION['email'];
        $objDBController = new DBController();
        $dbconn = $objDBController->getConn();
        $referral = $_SERVER["HTTP_REFERER"];
        write_mysql_log($dbconn, $log_date, $message, $user_email, session_id(), $referral);


        if (!$this->isBoolSearch($this->query)) {
            $this->expanflag = 0; // expansion
        } else {
            $this->expanflag = 1; // no expansion
        }
        $this->setPageNumber();
        $this->setRowsPerPageField();
        $this->setSortField();
        $this->setRowRange();
        $this->setDatatypesField();

        $this->setSeletectedRepositories();
        $this->setSelectedAccess();

        //$this->setSearchResults();
        $this->setTotalRows();
        $this->setSearchResults();
        $this->setDatatypes();


        $this->setAccess();


    }

    private function loadSearchType()
    {
        $this->searchtype = filter_input(INPUT_GET, "searchtype", FILTER_SANITIZE_STRING);
        return true;
    }

    // Load Search Query String.
    private function loadQuery()
    {
        $this->query = filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING);

        if ($this->query === NULL || strlen($this->query) == 0) {
            $this->query = " ";
        }

        // To track user's search history
        if (!isset($_SESSION["history"]['query'])) {
            $_SESSION["history"]['query'] = array();
            $_SESSION["history"]['date'] = array();
        }
        $historyItem = $this->getQuery() . '|||' . $this->getSearchType();

        date_default_timezone_set('America/chicago');
        $historyDate = date("Y-m-d H:i:s");;

        if (in_array($historyItem, $_SESSION["history"]['query'])) {
            $_SESSION["history"]['query'] = array_diff($_SESSION["history"]['query'], [$historyItem]);
        }
        array_push($_SESSION["history"]['query'], $historyItem);
        array_push($_SESSION["history"]['date'], $historyDate);
        return true;
    }

    // Load Page Number.
    private function setPageNumber()
    {
        $this->offset = filter_input(INPUT_GET, "offset", FILTER_SANITIZE_STRING);
        if ($this->offset === NULL) {
            $this->offset = 1;
        } else {
            $this->offset = intval($this->offset);
        }
    }
        
    private function setRowsPerPageField()
    {
        $this->rowsPerPage = filter_input(INPUT_GET, "rowsPerPage", FILTER_SANITIZE_STRING);
        if (!isset($this->rowsPerPage)) {
            $this->rowsPerPage = 20;
        }
    }
    
    // Loads selected field to sort the result.
    private function setSortField()
    {
        $this->sort = filter_input(INPUT_GET, "sort", FILTER_SANITIZE_STRING);
        if (!isset($this->sort)) {
            $this->sort = "relevance";
        }
    }

    private function setDatatypesField()
    {
        $datatypes = filter_input(INPUT_GET, "datatypes", FILTER_SANITIZE_STRING);
        if (isset($datatypes)) {
            $this->selectedDatatypes = explode(',', $datatypes);
        }
    }

    public function setSelectedAccess()
    {
        $access = filter_input(INPUT_GET, "access", FILTER_SANITIZE_STRING);
        if (isset($access)) {
            $this->selectedAccess = explode(',', $access);
        }
    }

    // Counts the total number of relavant results from the query.    
    private function setTotalRows()
    {
        $this->totalRows = 0;
        $search = $this->getSearchObject();

        $esResults = $search->getSearchResult();
        $this->orignalSearchResults = $esResults;


        $repositories_counts = [];

        foreach ($esResults['aggregations']['_index']['buckets'] as $bucket) {
            $key = explode('_', $bucket['key'])[0];
            $repositories_counts[$key] = $bucket['doc_count'];
        }
       // if (isset($this->selectedDatatypes) or isset($this->selectedAccess)or sizeof($this->selectedRepositories) >= 1) {  //comment due to block the clinical trial dataset
            $search_all = $this->get_search_object_of_all_repositories();
            $esResults_all = $search_all->getSearchResult();
            foreach ($esResults_all['aggregations']['_index']['buckets'] as $bucket) {
                $key = explode('_', $bucket['key'])[0];
                $repositories_counts[$key] = $bucket['doc_count'];
            }
        //}

        foreach ($this->repositories->getRepositories() as $repository) {


            // Set the total number of documents for current repository
            $repositoryHits = $repositories_counts[$repository->index];

            $repository->num = $repositoryHits;
            if (isset($this->selectedDatatypes)) {
                foreach ($this->selectedDatatypes as $selectedtype) {
                    $es_indexes = getDatatypesMapping()[$selectedtype];
                    foreach ($es_indexes as $es_index) {
                        if ($es_index == $search->es_index) {
                            $this->totalRows += $repositoryHits;
                        }
                    }
                }
            } elseif(isset($this->selectedAccess)){
                foreach ($this->selectedAccess as $access) {

                    $es_indexes = getAccessibilityMapping()[$access];

                    foreach ($es_indexes as $es_index) {
                        if ($es_index == $search->es_index) {
                            $this->totalRows += $repositoryHits;
                        }
                    }
                }
            }elseif (sizeof($this->selectedRepositories) >= 1) {
                foreach ($this->selectedRepositories as $selectedRepositories) {
                    $es_index = getRepositoryIDMapping()[$selectedRepositories];

                    if ($es_index == $search->es_index) {
                        $this->totalRows += $repositoryHits;
                    }
                }
            } else {

                $this->totalRows += $repositoryHits;
            }
        }
    }

    // Count the total number of relavant results from the query.
    private function setSearchResults()
    {
        // Search on all repositories.
        $esResults = $this->orignalSearchResults;
        $esItems = $esResults['hits']['hits'];

        $this->totalRows = $esResults['hits']['total'];

        $this->itemID = $esItems['_type'];
        $indexTypeHeader = [];
        foreach ($this->repositories->getRepositories() as $repo) {
            $indexTypeHeader[$repo->index . '_' . $repo->type] = [$repo->datasource_headers,
                $repo->source,
                $repo->show_name,
                $repo->id,
                $repo->link_field,
                $repo->core_fields_show_name];
        }
        $this->searchResults = $this->constructResults($esItems, $indexTypeHeader);

    }

    // Returns an ElasticSearch object to search on all repositories.
    private function getSearchObject()
    {
        $elasticSearchIndexes = '';
        if ($this->getSearchType() == 'repository') {
            $elasticSearchIndexes = "repository";
        } else {
            $mappings = getDatatypesMapping();
            $accessMappings = getAccessibilityMapping();
            if (isset($this->selectedDatatypes)) {
                foreach ($this->selectedDatatypes as $index) {
                    $elasticSearchIndexes .= implode(',', $mappings[$index]) . ',';
                }
                $elasticSearchIndexes = substr($elasticSearchIndexes, 0, -1);
            }elseif(isset($this->selectedAccess)){
                foreach ($this->selectedAccess as $index) {
                    $elasticSearchIndexes .= implode(',', $accessMappings[$index]) . ',';
                }
                $elasticSearchIndexes = substr($elasticSearchIndexes, 0, -1);
            }
            else {
                foreach ($mappings as $index) {
                    //for block the clinicaltrials result
                    if(in_array("clinicaltrials",$index)){
                        unset($index[0]);
                    }
                    $elasticSearchIndexes .= implode(',', $index) . ',';
                }
                $elasticSearchIndexes = substr($elasticSearchIndexes, 0, -1);

            }
        }
        //for select multiple repositories
        if (sizeof($this->selectedRepositories) >= 1) {
            $index = [];
            foreach ($this->selectedRepositories as $selectedRepository) {
                foreach ($this->repositories->getRepositories() as $repository) {
                    if ($repository->id == $selectedRepository) {
                        array_push($index, $repository->index);
                    }
                }
            }
            $elasticSearchIndexes = implode(',', $index);
        }
        if ($this->expanflag == 1 || $this->query==" ") { // if query term is null, don't use query expansion
            $search = new ElasticSearch();
        } else {
            $search = new ExpansionSearch();
        }

        $search->search_fields = $this->repositories->getSearchFields();
        //$search->facets_fields = [];
        $search->facets_fields = ['_index'];
        $search->facet_size = 300;
        $search->query = $this->getQuery();
        $search->filter_fields = [];
        $search->es_index = $elasticSearchIndexes;
        $search->es_type = '';
        $search->offset = $this->getOffset();
        $search->rowsPerPage = $this->getRowsPerPage();
        $search->sort = $this->getSort();

        return $search;
    }

    private function constructResults($esItems, $indexTypeHeader)

    {
        $returnValue = [];
        foreach ($esItems as $row) {
            $this->itemID = $row['_id'];
            $key = explode("_", $row['_index'])[0] . '_' . $row['_type']; // index name = [repository name]_[date]

            $visibleFields = $this->constructResultsFields($row, $indexTypeHeader, $key);

            if ($visibleFields != NULL) {
                $fields = explode('.', $indexTypeHeader[$key][4]);
                $orinialItem = $this->getResultSource($indexTypeHeader, $key, $fields, $row);
                if ($this->getSearchType() != 'repository') {
                    $visibleFields['ref'] = 'display-item.php?repository=' . $indexTypeHeader[$key][3] . '&idName=' . $indexTypeHeader[$key][4] . '&id=' . $row['_id'];
                    $visibleFields['ref_raw'] = 'share-item-repository=' . $indexTypeHeader[$key][3] . '&id=' . $row['_id'];
                } else {
                    $visibleFields['ref'] = $row['_source']['url'];
                }

                $visibleFields['source'] = $indexTypeHeader[$key][2];
                $visibleFields['source_ref'] = $indexTypeHeader[$key][3];

                array_push($returnValue, $visibleFields);
            }
        }

        return $returnValue;
    }

    private function constructResultsFields($item, $indexTypeHeader, $key)
    {
        if (array_key_exists($key, $indexTypeHeader)) {
            $headersId = $indexTypeHeader[$key][0];
            $show_item = [];
            for ($i = 0; $i < sizeof($headersId); $i++) {
                $newName = $indexTypeHeader[$key][5][$headersId[$i]];
                if (isset($item['highlight'][$headersId[$i]])) {
                    $show_item[$newName] = $item['highlight'][$headersId[$i]][0];
                    continue;
                }
                $fields = explode('.', $headersId[$i]);

                if (count($fields) == 2) {

                    $show_item[$newName] = is_array($item['_source'][$fields[0]][$fields[1]]) ? implode(' ', $item['_source'][$fields[0]][$fields[1]]) : $item['_source'][$fields[0]][$fields[1]];

                    //add for handle the case like niddkcr disease.name field
                    if (!isset($item['_source'][$fields[0]][$fields[1]]) and is_array($item['_source'][$fields[0]])) {
                        $show_item[$newName] = '';

                        foreach ($item['_source'][$fields[0]] as $subarray) {
                            if (strlen($show_item[$newName]) == 0) {
                                $show_item[$newName] = $subarray[$fields[1]];
                            } else {
                                $show_item[$newName] = $show_item[$newName] . ',' . $subarray[$fields[1]];
                            }
                        }
                    }
                } else {
                    if (isset($item['highlight'][$headersId[$i]])) {
                        $item['_source'][$headersId[$i]] = $item['highlight'][$headersId[$i]];
                    }

                    $show_item[$newName] = is_array($item['_source'][$headersId[$i]]) ? implode(' ', $item['_source'][$headersId[$i]]) : $item['_source'][$headersId[$i]];
                }
            }
            foreach (array_keys($show_item) as $key) {
                if ((bool)(strtotime($show_item[$key]))) {
                    $show_item[$key] = date("m-d-Y", strtotime($show_item[$key]));
                }
            }
            //$show_item['_id'] = $item['_id'];
            return $show_item;
        }
        return NULL;
    }

    private function getResultSource($indexTypeHeader, $key, $fields, $row)
    {
        if (count($fields) == 2) {

            if (is_array($row['_source'][$fields[0]][$fields[1]])) {
                $row['_source'][$fields[0]][$fields[1]] = implode(' ', $row['_source'][$fields[0]][$fields[1]]);
            }
            $orinialItem = str_replace('<strong>', '', $row['_source'][$fields[0]][$fields[1]]);
        } else {
            if (is_array($row['_source'][$indexTypeHeader[$key][4]])) {
                $row['_source'][$indexTypeHeader[$key][4]] = implode(' ', $row['_source'][$indexTypeHeader[$key][4]]);
            }
            $orinialItem = str_replace('<strong>', '', $row['_source'][$indexTypeHeader[$key][4]]);
        }

        return str_replace('</strong>', '', $orinialItem);
    }

    public function setRowRange()
    {
        $start = ((($this->offset - 1) * $this->getRowsPerPage()) + 1);
        $end = (($this->offset - 1) * $this->getRowsPerPage()) + $this->getRowsPerPage();
        $this->rowRange = $start . '-' . $end;
    }

    public function getRepositoriesList()
    {
        $resultSet = [];
        $mappings = getDatatypesMapping();
        $accessmappings = getAccessibilityMapping();

        foreach ($this->repositories->getRepositories() as $repository) {

            if (isset($this->selectedDatatypes)) {
                foreach (getDatatypes() as $datatype) {
                    if (in_array($repository->index, $mappings[$datatype]) && $repository->num > 0) {

                        if (in_array($datatype, $this->selectedDatatypes)) {
                            $resultSet[$repository->show_name]['selected'] = true;
                            $resultSet[$repository->show_name]['id'] = $repository->id;
                            $resultSet[$repository->show_name]['rows'] = ($repository->num == NULL ? 0 : $repository->num);
                            $resultSet[$repository->show_name]['whole'] = $repository->whole_name;
                        }

                    }
                }
            } elseif (isset($this->selectedAccess)) {
                foreach (getAllAccess() as $access) {
                    if (in_array($repository->index, $accessmappings[$access]) && $repository->num > 0) {

                        if (in_array($access, $this->selectedAccess)) {
                            $resultSet[$repository->show_name]['selected'] = true;
                            $resultSet[$repository->show_name]['id'] = $repository->id;
                            $resultSet[$repository->show_name]['rows'] = ($repository->num == NULL ? 0 : $repository->num);
                            $resultSet[$repository->show_name]['whole'] = $repository->whole_name;
                        }
                    }
                }
            } else {
                if($repository->num > 0){
                    $resultSet[$repository->show_name]['selected'] = false;
                    $resultSet[$repository->show_name]['id'] = $repository->id;
                    $resultSet[$repository->show_name]['rows'] = ($repository->num == NULL ? 0 : $repository->num);
                    $resultSet[$repository->show_name]['whole'] = $repository->whole_name;
                }
            }


            foreach ($this->selectedRepositories as $selectedRepository) {
                if ($selectedRepository == $repository->id) {
                    $resultSet[$repository->show_name]['selected'] = true;
                    $resultSet[$repository->show_name]['id'] = $repository->id;
                    $resultSet[$repository->show_name]['rows'] = ($repository->num == NULL ? 0 : $repository->num);
                    $resultSet[$repository->show_name]['whole'] = $repository->whole_name;
                }
            }
        }

        return $resultSet;
    }

    // Loades datatypes, their row counts, and selection state.
    private function setDatatypes()
    {
        $this->datatypes = [];
        $mappings = getDatatypesMapping();

        foreach (getDatatypes() as $datatype) {

            $rows = 0;

            foreach ($this->repositories->getRepositories() as $repository) {

                if (in_array($repository->index, $mappings[$datatype])) {
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

    // Loades datatypes, their row counts, and selection state.
    private function setAccess()
    {
        $this->access = [];
        $mappings = getAccessibilityMapping();

        foreach (getAllAccess() as $access) {
            $rows = 0;

            foreach ($this->repositories->getRepositories() as $repository) {
                if (in_array($repository->index, $mappings[$access])) {
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

    public function getUrlWithQyery()
    {
        return 'search.php?query=' . $this->query . '&searchtype=' . $this->getSearchType();
    }

    public function getUrlByOffset($newOffset)
    {
        $returnValue = $this->getUrlWithQyery() . '&offset=' . $newOffset;
        $returnValue .= $this->getRowsPerPageQueryString();             
        $returnValue .= $this->getSortUrl();
        $returnValue .= $this->getDatatypesUrl();
        $returnValue .= $this->getRepositoriesUrl();
        return $returnValue;
    }

    public function getUrlBySort($sort)
    {
        $returnValue = $this->getUrlWithQyery() . '&offset=1&sort=' . $sort;
        $returnValue .= $this->getRowsPerPageQueryString();        
        $returnValue .= $this->getDatatypesUrl();
        $returnValue .= $this->getRepositoriesUrl();
        return $returnValue;
    }
    
    public function getUrlByRowsPerPage($rowsPerPage)
    {
        $returnValue = $this->getUrlWithQyery() . '&offset=1&rowsPerPage=' . strval($rowsPerPage);
        $returnValue .= $this->getSortUrl();
        $returnValue .= $this->getDatatypesUrl();
        $returnValue .= $this->getRepositoriesUrl();
        return $returnValue;
    }

    public function getUrlByDatatype($datatype)
    {
        $newDatatypes = [];
        if (isset($this->selectedDatatypes)) {
            $newDatatypes = $this->selectedDatatypes;
        }

        if (in_array($datatype, $newDatatypes)) {
            $newDatatypes = array_diff($newDatatypes, [$datatype]);
        } else {
            array_push($newDatatypes, $datatype);
        }

        if (count($newDatatypes) > 0) {
            return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl() . '&datatypes=' . implode(',', $newDatatypes);
        }

        return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl();
    }


    public function getUrlByAccessibility($access)
    {
        $newAccess = [];
        if (isset($this->selectedAccess)) {
            $newAccess = $this->selectedAccess;
        }

        if (in_array($access, $newAccess)) {
            $newAccess = array_diff($newAccess, [$access]);
        } else {
            array_push($newAccess, $access);
        }

        if (count($newAccess) > 0) {
            return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl() . '&access=' . implode(',', $newAccess);
        }

        return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl();
    }


    public function getUrlByRepository($repository)
    {
        return 'search-repository.php?query=' . $this->query . '&searchtype=' . $this->searchtype . '&repository=' . $repository;
    }

    private function getRowsPerPageQueryString()
    {
        if (isset($this->rowsPerPage) && $this->rowsPerPage != 20) {
            return '&rowsPerPage=' . $this->rowsPerPage;
        }
        return '';
    }
    
    private function getSortUrl()
    {
        if (isset($this->sort) && $this->sort != 'relevance') {
            return '&sort=' . $this->sort;
        }
        return '';
    }

    private function getDatatypesUrl()
    {
        if (isset($this->selectedDatatypes)) {
            return '&datatypes=' . implode(",", $this->selectedDatatypes);
        }
        return '';
    }

    private function getRepositoriesUrl()
    {
        if (sizeof($this->selectedRepositories) >= 1) {
            return '&repository=' . implode(",", $this->selectedRepositories);
        }
        return '';
    }

    //for select multiple repositories
    // Stores selected repositories from post query parameters.
    private $selectedRepositories = [];

    // Loads selected repositories  to sort the result.
    private function setSeletectedRepositories()
    {
        $repositories = filter_input(INPUT_GET, "repository", FILTER_SANITIZE_STRING);
        if (isset($repositories)) {
            $this->selectedRepositories = explode(',', $repositories);
        }
    }

    public function getSelectedRepositories()
    {
        return $this->selectedRepositories;
    }

    public function getUrlBySelectedRepository($repository)
    {
        $newRepositores = [];
        if (isset($this->selectedRepositories)) {
            $newRepositores = $this->selectedRepositories;
        }

        if (in_array($repository, $newRepositores)) {
            $newRepositores = array_diff($newRepositores, [$repository]);
        } else {
            array_push($newRepositores, $repository);
        }

        if (count($newRepositores) > 0) {
            return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl() . '&repository=' . implode(',', $newRepositores);
        }
        return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl();
    }

    public function isBoolSearch($query)
    {
        if (preg_match('/(AND|OR|NOT|\[|\])/', $query)) {
            return true;
        } else {
            return false;
        }
    }

    public function isRepositorySelected()
    {
        $result = false;
        $repositoryList = $this->getRepositoriesList();
        foreach ($repositoryList as $repositoryName => $details) {
            if ($details['selected'] == true) {
                $result = true;
                return $result;
            }
        }
        return $result;
    }

    private function get_search_object_of_all_repositories()
    {
        $elasticSearchIndexes = getElasticSearchIndexes();

        if ($this->expanflag == 1 || $this->query==" ") { // if query term is null, don't use query expansion
            $search = new ElasticSearch();
        } else {
            $search = new ExpansionSearch();
        }

        $search->search_fields = $this->repositories->getSearchFields();
        $search->facets_fields = ['_index'];
        $search->facet_size = 200;
        $search->query = $this->getQuery();
        $search->filter_fields = [];
        $search->es_index = $elasticSearchIndexes;
        $search->es_type = '';
        $search->offset = $this->getOffset();
        $search->sort = $this->getSort();
        return $search;
    }
}
