<?php

require_once dirname(__FILE__) . '/Utilities.php';

/*
 * Construct the search results and hyperlinks for the search.php page
 * @input an instance of SearchBuilder class
 * @output
 *      $this->repositoryFilter: an array of data for repository filter
 *      $this->dataTypeFilter: an array of data for data type filter
 *      $this->searchResults: an array of data for search results
 */

class ConstructSearchView {
    /*
     * Constant variables
     */

    public $DATATYPES_MAPPING;  // map data type to index
    public $ACCESS_MAPPING;     // map access type to index
    public $AUTH_MAPPING;       // map authorization type to index
    public $ALL_DATA_TYPES;     // a list of all data types
    public $ALL_ACCESS_TYPES;   // a list of all access types
    public $ALL_AUTH_TYPES;     // a list of all authorization types
    public $ALL_ELASTICSEARCH_INDEXES;      // a list of all elasticsearch indexes
    public $PAGE_NAME;

    /**
     * a SearchBuilder object
     * @var object
     */
    private $searchBuilder;

    /*
     * an array of data for repository filter
     * @var array(array(string))
     */
    private $repositoryFilter;

    /*
     * an array of data for data type filter
     * @var array(array(string))
     * */
    private $dataTypeFilter;


    /*
     * an array of data for accessibility filter
     * @var array(array(string))
     * */
    private $accessFilter;


    /*
     * an array of data for authorization filter
     * @var array(array(string))
     * */
    private $authorizationFilter;


    /*
     * an array of data for search results
     * @var array(array(string))
     */
    private $searchResults;
    private $searchResults_without_duplicate;
    public function __construct($searchBuilder) {
        /*
         * Load parameters
         * */
        // the functions below are from config/datasources.php file
        $this->DATATYPES_MAPPING = getDatatypesMapping();
        $this->ACCESS_MAPPING = getAccessibilityMapping();
        $this->AUTH_MAPPING = getAuthMapping();
        $this->ALL_DATA_TYPES = getDatatypes();
        $this->ALL_ACCESS_TYPES = getAllAccess();
        $this->ALL_AUTH_TYPES = getAllAuth();
        $this->ALL_ELASTICSEARCH_INDEXES = getElasticSearchIndexes();

        $this->PAGE_NAME = (basename($_SERVER['PHP_SELF']));

        $this->setSearchBuilder($searchBuilder);


        $this->setRepositoryFilter();    // generate repositories filter
        $this->setDataTypeFilter();    // generate data types filter

        $this->setAccessFilter();       // generate accessibility filter
        $this->setAuthorizationFilter();        // generate authorization filter
        $this->setSearchResults(); // generate a list of search results
    }

    /**
     * @return object
     */
    public function getSearchBuilder() {
        return $this->searchBuilder;
    }

    /**
     * @param object $searchBuilder
     */
    public function setSearchBuilder($searchBuilder) {
        $this->searchBuilder = $searchBuilder;
    }

    /**
     * @return array(array(string))
     */
    public function getRepositoryFilter() {
        return $this->repositoryFilter;
    }

    // generate data for the repository filter
    public function setRepositoryFilter() {
        // Get an array of selected repository indexes
        $selectedRepositories = $this->getSearchBuilder()->getSelectedElasticSearchIndexes();
        $selectedRepositories = explode(",", $selectedRepositories);

        foreach ($this->searchBuilder->getRepositories()->getRepositories() as $repository) {
            if ($selectedRepositories != NULL && in_array($repository->index, $selectedRepositories)) {
                $this->repositoryFilter[$repository->repoShowName]['selected'] = true;
                $this->repositoryFilter[$repository->repoShowName]['id'] = $repository->id;
                $this->repositoryFilter[$repository->repoShowName]['rows'] = ($repository->num == NULL ? 0 : $repository->num);
                $this->repositoryFilter[$repository->repoShowName]['wholeName'] = $repository->wholeName;
            } else {
                if ($repository->num > 0) {
                    $this->repositoryFilter[$repository->repoShowName]['selected'] = false;
                    $this->repositoryFilter[$repository->repoShowName]['id'] = $repository->id;
                    $this->repositoryFilter[$repository->repoShowName]['rows'] = ($repository->num == NULL ? 0 : $repository->num);
                    $this->repositoryFilter[$repository->repoShowName]['wholeName'] = $repository->wholeName;
                }
            }
        }
        $this->repositoryFilter = $this->sortByNum($this->repositoryFilter);
    }

    /**
     * @return array(array(string))
     */
    public function getDataTypeFilter() {
        return $this->dataTypeFilter;
    }

    /**
     * generate data for the data type filter
     * If the number of search results is 0, this data type will not be returned
     */
    public function setDataTypeFilter() {
        foreach ($this->ALL_DATA_TYPES as $data_type) {
            $rows = 0;
            foreach ($this->searchBuilder->getRepositories()->getRepositories() as $repository) {

                if (in_array($repository->index, $this->DATATYPES_MAPPING[$data_type])) {
                    $rows += $repository->num;
                }
            }
            if ($rows > 0) {
                $this->dataTypeFilter[$data_type]['rows'] = $rows;
                $this->dataTypeFilter[$data_type]['selected'] = false;
                if ($this->searchBuilder->getSelectedDatatypes() != NULL && in_array($data_type, $this->searchBuilder->getSelectedDatatypes())) {
                    $this->dataTypeFilter[$data_type]['selected'] = true;
                }
            }
        }
        $this->dataTypeFilter = $this->sortByNum($this->dataTypeFilter);
    }

    /**
     * @return array(array(string))
     */
    public function getAccessFilter() {
        return $this->accessFilter;
    }

    /**
     * generate data for the accessibility filter
     */
    public function setAccessFilter() {
        foreach ($this->ALL_ACCESS_TYPES as $access) {
            $rows = 0;
            foreach ($this->searchBuilder->getRepositories()->getRepositories() as $repository) {
                if (in_array($repository->index, $this->ACCESS_MAPPING[$access])) {
                    $rows += $repository->num;
                }
            }

            if ($rows > 0) {
                $this->accessFilter[$access]['rows'] = $rows;
                $this->accessFilter[$access]['selected'] = false;
                if ($this->searchBuilder->getSelectedAccess() != NULL && in_array($access, $this->searchBuilder->getSelectedAccess())) {
                    $this->accessFilter[$access]['selected'] = true;
                }
            }
        }
        $this->accessFilter = $this->sortByNum($this->accessFilter);
    }

    /**
     * @return array(array(string))
     */
    public function getAuthorizationFilter() {
        return $this->authorizationFilter;
    }

    /**
     * generate data for the authorization filter
     */
    public function setAuthorizationFilter() {
        foreach ($this->ALL_AUTH_TYPES as $auth) {
            $rows = 0;
            foreach ($this->searchBuilder->getRepositories()->getRepositories() as $repository) {
                if (in_array($repository->index, $this->AUTH_MAPPING[$auth])) {
                    $rows += $repository->num;
                }
            }

            if ($rows > 0) {
                $this->authorizationFilter[$auth]['rows'] = $rows;
                $this->authorizationFilter[$auth]['selected'] = false;
                if ($this->searchBuilder->getSelectedAuth() != NULL && in_array($auth, $this->searchBuilder->getSelectedAuth())) {
                    $this->authorizationFilter[$auth]['selected'] = true;
                }
            }
        }
        $this->authorizationFilter = $this->sortByNum($this->authorizationFilter);
    }

    /**
     * @return array(array(string))
     */
    public function getSearchResults() {
        return $this->searchResults;
    }
    public function getSearchResults_without_duplicate(){
        return $this->searchResults_without_duplicate;
    }
    /**
     * format the data for the search results column
     */
    public function setSearchResults() {
        $es_results = $this->searchBuilder->getElasticSearchResults();
        $this->searchResults = $this->getSearchResultsinFormat($es_results);
    }

    public function setSearchResults_without_duplicate() {
        $es_results = $this->searchBuilder->getElasticSearchResults_without_duplicate();
        $this->searchResults_without_duplicate=$this->getSearchResultsinFormat($es_results);
    }

    public function getSearchResultsinFormat($es_results){
        $es_items = $es_results['hits']['hits'];
        //var_dump($es_items);
        $index_type_header = [];
        foreach ($this->searchBuilder->getRepositories()->getRepositories() as $repo) {
            $index_type_header[$repo->index . '_' . $repo->type] = [$repo->searchPageField, // used on search page, key of the fields
                $repo->source,
                $repo->repoShowName, // complete name of the repository
                $repo->id,
                $repo->link_field, // may not being used
                $repo->searchPageHeader];  // map key of the fields to label
        }
        return $this->constructResults($es_items, $index_type_header);
    }
    /**
     * Construct search results for displaying
     * @input: $es_item, an array of search results from elasticsearch
     *         $index_type_header, an array of field names to be displayed
     * @ouput: $results_hyperlink: an array of search results with hyperlinks
     */
    private function constructResults($es_items, $index_type_header) {
        $results_hyperlink = [];
        if(is_null($es_items)){
           return $results_hyperlink;
        }
        foreach ($es_items as $item) {
            $key = explode("_", $item['_index'])[0] . '_' . $item['_type'];     // change "indexname_date" to "indexname_type"
            $visible_fields = $this->constructResultsFields($item, $index_type_header, $key);
            if ($visible_fields != NULL) {
                if ($this->searchBuilder->getSearchType() != 'repository') {
                    $visible_fields['ref'] = 'display-item.php?repository=' . $index_type_header[$key][3] . '&id=' . $item['_id'];
                    $visible_fields['ref_raw'] = 'share-item-repository=' . $index_type_header[$key][3] . '&id=' . $item['_id'];
                } else {
                    $visible_fields['ref'] = $item['_source']['url'];
                    $visible_fields['ref_raw'] = $item['_source']['url'];
                }
                $visible_fields['source'] = $index_type_header[$key][2];
                $visible_fields['source_ref'] = $index_type_header[$key][3];
                $visible_fields['es_id'] = $item["_id"];
                array_push($results_hyperlink, $visible_fields);
            }

        }
        return $results_hyperlink;
    }

    // Construct search result fields for displaying
    private function constructResultsFields($item, $index_type_header, $key) {
        if (array_key_exists($key, $index_type_header)) {
            $headersId = $index_type_header[$key][0];

            $show_item = [];
            for ($i = 0; $i < sizeof($headersId); $i++) {
                $newName = $index_type_header[$key][5][$headersId[$i]];
                if (isset($item['highlight'][$headersId[$i]])) {
                    $show_item[$newName] = $item['highlight'][$headersId[$i]][0];
                    continue;
                }
                $fields = explode('.', $headersId[$i]);

                if (count($fields) == 2) {
                    if (!isset($item['_source'][$fields[0]][$fields[1]])) {
                        continue;
                    }
                    $show_item[$newName] = is_array($item['_source'][$fields[0]][$fields[1]]) ? implode(' ', $item['_source'][$fields[0]][$fields[1]]) : $item['_source'][$fields[0]][$fields[1]];

                    //handle the case like niddkcr disease.name field
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
                if($show_item[$key]=='' and $key=='Title'){//for nursa some item does not have title
                    $show_item[$key]=$show_item['ID'];
                }
                if ((bool) (strtotime($show_item[$key])) && !in_array($key,['ID','Title'])) { //some id field is recognized as time and show incorrectly
                    $show_item[$key] = format_time($show_item[$key]);//date("m-d-Y", strtotime($show_item[$key]));
                }
                $show_item[$key] = get_dom_value($show_item[$key]);
                //var_dump($show_item);
            }
            return $show_item;
        }
        return NULL;
    }

    /*
     * Check if any repository has been selected.
     * If true, show "Clear All" label
     * @return: boolean
     * */

    public function isRepositorySelected() {
        $repositoryList = $this->getRepositoryFilter();
        if ($repositoryList != NULL) {
            foreach ($repositoryList as $repositoryName => $details) {
                if ($details['selected'] == true) {
                    return true;
                }
            }
        }
        return false;
    }

    /*
     * Sort repositories or data types by number of search results
     * @param: array(array(string))
     * @return: array(array(string))
     * */

    public function sortByNum($input) {
        $num = array();
        if ($input != NULL && sizeof($input)!=0) {
            foreach ($input as $key => $value) {
                if (isset($value['rows'])) {
                    $num[$key] = $value['rows'];
                } elseif(isset($value['count'])) {
                    $num[$key] = $value['count'];
                }
            }

          /*  echo "<pre>";
            var_dump($num);
            echo "</pre>";

            echo "<pre>";
            var_dump($input);
            echo "</pre>";
          */

            array_multisort($num, SORT_DESC, $input);
        }
        return $input;
    }

    /*
     * Generate URL with query
     * @return: string, URL
     * */

    public function getUrlWithQuery($page_name=null) {
        if (isset($page_name)) {
            return $page_name . '?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery();
        }
        return $this->PAGE_NAME . '?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery();
    }

    public function getUrlWithSearchType() {
        return '&searchtype=' . $this->searchBuilder->getSearchType();
    }

    public function getURLWithRepository() {
        if (($this->searchBuilder->getSelectedRepositories()) != NULL) {
            $currentID = $this->searchBuilder->getSelectedRepositories();
            return '&repository=' . implode(',', $currentID);
        } else {
            return '';
        }
    }

    public function getURLWithDataTypes() {
        if (($this->searchBuilder->getSelectedDataTypes()) != NULL) {
            $currentID = $this->searchBuilder->getSelectedDataTypes();
            return '&datatypes=' . implode(',', $currentID);
        } else {
            return '';
        }
    }

    /*
     * Generate url by ID of selected repository
     * @param: string, ID of a repository
     * @return: string, URL
     * */

    public function getUrlBySelectedRepository($repoID) {

        $currentID = [];

        // Get IDs of currently selected repositories
        if (($this->searchBuilder->getSelectedRepositories()) != NULL) {
            $currentID = $this->searchBuilder->getSelectedRepositories();
        }

        // Add new ID if it is not in $currentID
        if (!in_array($repoID, $currentID)) {
            array_push($currentID, $repoID);
        } else {
            $currentID = array_diff($currentID, [$repoID]);
        }

        if (count($currentID) > 0) {
            return $this->getUrlWithQuery("search.php") . '&offset=1' . '&repository=' . implode(',', $currentID);
        }
        return $this->getUrlWithQuery("search.php") . '&offset=1';
    }

    /*
     * Generate url by ID of selected data type
     * @param: string, name of a data type
     * @return: string, URL
     * */

    public function getUrlByDatatype($dataType) {
        $currentType = [];

        if ($this->searchBuilder->getSelectedDatatypes() != NULL) {
            $currentType = $this->searchBuilder->getSelectedDatatypes();
        }
        // Add new data type if it is not in $currentType
        if (!in_array($dataType, $currentType)) {
            array_push($currentType, $dataType);
        } else {
            $currentType = array_diff($currentType, [$dataType]);
        }
        if (count($currentType) > 0) {
            return  'search.php?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery() . '&offset=1' . '&datatypes=' . implode(',', $currentType);
        }
        return 'search.php?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery() . '&offset=1';
    }

    public function getUrlByAuth($auth) {
        $currentAuth = [];

        if ($this->searchBuilder->getSelectedAuth() != NULL) {
            $currentAuth = $this->searchBuilder->getSelectedAuth();
        }
        // Add new data type if it is not in $currentType
        if (!in_array($auth, $currentAuth)) {
            array_push($currentAuth, $auth);
        } else {
            $currentAuth = array_diff($currentAuth, [$auth]);
        }
        if (count($currentAuth) > 0) {
            return 'search.php?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery()  . '&offset=1' . '&auth=' . implode(',', $currentAuth);
        }
        return 'search.php?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery()  . '&offset=1';
    }

    public function getUrlByAccessibility($access) {
        $currentAccess = [];

        if ($this->searchBuilder->getSelectedAccess() != NULL) {
            $currentAccess = $this->searchBuilder->getSelectedAccess();
        }
        // Add new data type if it is not in $currentType
        if (!in_array($access, $currentAccess)) {
            array_push($currentAccess, $access);
        } else {
            $currentAccess = array_diff($currentAccess, [$access]);
        }
        if (count($currentAccess) > 0) {
            return 'search.php?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery()  . '&offset=1' . '&access=' . implode(',', $currentAccess);
        }
        return 'search.php?searchtype=' . $this->searchBuilder->getSearchType() . '&query=' . $this->searchBuilder->getQuery()  . '&offset=1';
    }

    /*
     * @param: string, id of a repository
     * @return: string, URL
     * */

    public function switchView($repository) {
        if ($this->PAGE_NAME == 'search.php') {
            return 'search-repository.php?query=' . $this->searchBuilder->getQuery() . '&searchtype=' . $this->searchBuilder->getSearchType() . '&repository=' . $repository;
        } else {
            return 'search.php?query=' . $this->searchBuilder->getQuery() . '&searchtype=' . $this->searchBuilder->getSearchType() . '&repository=' . $repository;
        }
    }

    /*
     * @param: int, offset
     * @return: string, URL
     * */

    public function getUrlByOffset($newOffset) {
        $returnValue = $this->getUrlWithQuery()
                . '&offset=' . $newOffset
                . '&rowsPerPage='.$this->searchBuilder->getRowsPerPage()
                . $this->getURLWithRepository()
                . $this->getURLWithDataTypes()
                . $this->getCurrentSort()
                ;

        return $returnValue;
    }

    /*
     * @param: int, number of rows per page
     * @return: string, URL
     * */

    function getRowsPerPageUrl($rowsPerPage) {
        return $this->getUrlByRowsPerPage($rowsPerPage) . $this->getCurrentSort();
    }

    function getRowsPerPageStyle($row) {
        if ($row == $this->searchBuilder->getRowsPerPage()) {
            return 'style="background-color: #D8D7D7;"';
        }
        return '';
    }

    /*
     * @param: int, number of rows per page
     * @return: string, URL
     * */

    public function getUrlByRowsPerPage($rowsPerPage) {

        return $this->getUrlWithQuery() .
                '&offset=1&rowsPerPage=' .
                strval($rowsPerPage) .
                $this->getURLWithRepository() .
                $this->getCurrentSort();
    }

    public function getSortUrl($sortValue='') {
        return $this->getUrlWithQuery() .
                $this->getURLWithRepository() .
                '&sort=' . $sortValue;
    }

    public function getCurrentSort() {
        return '&sort=' . $this->searchBuilder->getSort();
    }

    public function clearAllURL() {
        return $this->getUrlWithQuery();
    }

    /*
     * Display search results
     * */

    function process_strong_highlight($field) {
        if (strpos($field, '<strong>') !== false) {
            $start = substr_count($field, '<strong>');
            $end = substr_count($field, '</strong>');
            if ($start > $end) {
                $last = 11 - strpos(substr($field, -11, 11), "</");
                $field = substr($field, 0, strlen($field) - $last);
                $field = $field . '</strong>';
            }
        }
        return $field;
    }

    /*
     * get previous offset index
     * */

    function get_previsoue($offset) {

        if ($offset > 1) {
            $offset = $offset - 1;
        }
        return $offset;
    }

    /*
     * get next offset index
     * */

    function get_next($offset, $num, $N) {

        if ($offset < $num / $N) {
            $offset = $offset + 1;
        }
        return $offset;
    }

    /*
     * show the record number on the current page
     * */

    function show_current_record_number($offset, $num, $N) {

        if ($offset < $num / $N) {
            return ((($offset - 1) * $N) + 1) . "-" . ($offset) * $N;
        } else {
            return ((($offset - 1) * $N) + 1) . "-" . $num;
        }
    }

}
