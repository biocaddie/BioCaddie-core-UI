<?php

require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/Repositories.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';
require_once dirname(__FILE__) . '/ExpansionSearch.php';
require_once dirname(__FILE__) . '/NLPSearch.php';
require_once dirname(__FILE__) . '/../write_mysql_log.php';
require_once dirname(__FILE__) . '/../dbcontroller.php';
date_default_timezone_set('America/Chicago');

class SearchRepositoryBuilder {

    private $expanflag = 0; //0 for expansion, 1 for no expansion
    // An instance of repositories database.
    private $repositoryHolder;
    // User search query string.
    private $query;
    private $searchtype;
    private $currentRepositoryname;

    public function getQuery() {
        return $this->query;
    }

    // Current selected repository.
    private $currentRepository;

    public function getSearchType() {
        if (!$this->searchtype) {
            $this->searchtype = "data";
        }
        return $this->searchtype;
    }

    public function getCurrentRepository() {
        return $this->currentRepository;
    }

    public function getCurrentRepositoryname() {
        return $this->currentRepositoryname;
    }

    // Page number.
    private $offset;

    public function getOffset() {
        return $this->offset;
    }

    // Sort order.
    private $sort;

    public function getSort() {
        return $this->sort;
    }

    // Rows per page.
    private $rowsPerPage;

    // Number of rows per page.
    public function getRowsPerPage() {
        return $this->rowsPerPage;
    }

    private $totalRows;

    public function getTotalRows() {
        return $this->totalRows;
    }

    // Current page headers set.
    private $searchHeaders;

    public function getSearchHeaders() {
        return $this->searchHeaders;
    }

    // Current page result set.
    private $searchResults;

    public function getSearchResults() {
        return $this->searchResults;
    }

    // Current repositores.
    private $repositories;

    public function getRepositories() {
        return $this->repositories;
    }

    // Current page filter set.
    private $filters;

    public function getFilters() {
        return $this->filters;
    }

    // Current page selected filter set.
    private $selectedFilters;

    public function getSelectedFilters() {
        return $this->selectedFilters;
    }

    function __construct() {

        $this->loadSearchType();
        if (!$this->loadQuery() || !$this->loadCurrentRepository()) {
            header('Location: index.php');
            exit;
        }

        /* Track user's activity */
        $log_date = date("Y-m-d H:i:s");
        ;
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

        $this->repositoryHolder = new Repositories();

        $this->setPageNumber();
        $this->setRowsPerPageField();
        $this->setSortField();
        $this->setRowRange();
        $this->setSelectedFilters();

        $this->setSearchResults();
        $this->setRepositories();
    }

    private function loadSearchType() {
        $this->searchtype = filter_input(INPUT_GET, "searchtype", FILTER_SANITIZE_STRING);
        return true;
    }

    // Load Search Query String.
    private function loadQuery() {
        $this->query = filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING);
        if ($this->query === NULL || strlen($this->query) == 0) {
            $this->query = " ";
        }



        // To track database's search history
        if (!isset($_SESSION["history"])) {
            $_SESSION["history"] = array();
        }
        if (in_array($this->getQuery(), $_SESSION["history"])) {
            $_SESSION["history"] = array_diff($_SESSION["history"], [$this->getQuery()]);
        }
        array_push($_SESSION["history"], $this->getQuery());
        return true;
    }

    // Load Selected Repository Id.
    private function loadCurrentRepository() {
        $this->currentRepository = filter_input(INPUT_GET, "repository", FILTER_SANITIZE_STRING);
        if ($this->currentRepository === NULL || strlen($this->currentRepository) == 0) {
            return false;
        }
        return true;
    }

    // Load Page Number.
    private function setPageNumber() {
        $this->offset = filter_input(INPUT_GET, "offset", FILTER_SANITIZE_STRING);
        if ($this->offset === NULL) {
            $this->offset = 1;
        } else {
            $this->offset = intval($this->offset);
        }
    }

    private function setRowsPerPageField() {
        $this->rowsPerPage = filter_input(INPUT_GET, "rowsPerPage", FILTER_SANITIZE_STRING);
        if (!isset($this->rowsPerPage)) {
            $this->rowsPerPage = 20;
        }
    }

    // Loads selected field to sort the result.
    private function setSortField() {
        $this->sort = filter_input(INPUT_GET, "sort", FILTER_SANITIZE_STRING);
        if (!isset($this->sort)) {
            $this->sort = "relevance";
        }
    }

    // Loads selected field to sort the result.
    private function setSelectedFilters() {
        $filters = filter_input(INPUT_GET, "filters", FILTER_SANITIZE_STRING);
        if (isset($filters)) {
            $this->selectedFilters = [];
            $facets = explode('$', $filters);
            foreach ($facets as $facet) {
                $facetParts = explode('@', $facet);
                $keys = explode(',', $facetParts[1]);
                $this->selectedFilters[$facetParts[0]] = $keys;
            }
        }
    }

    // Count the total number of relavant results from the query.
    private function setSearchResults() {

        foreach ($this->repositoryHolder->getRepositories() as $repository) {

            if ($repository->id == $this->getCurrentRepository()) {
                if ($this->expanflag == 1 || $this->query==" ") { // if query term is null, don't use query expansion
                    $search = new ElasticSearch();
                } else {
                    $search = new ExpansionSearch();
                    //$search = new NLPSearch();
                }
                $search->search_fields = $repository->search_fields;
                $search->facets_fields = $repository->facets_fields;
                $search->query = $this->getQuery();
                $search->filter_fields = isset($this->selectedFilters) ? $this->selectedFilters : [];
                $search->es_index = $repository->index;
                $search->es_type = $repository->type;
                $search->rowsPerPage = $this->getRowsPerPage();
                $search->offset = $this->getOffset();
                $search->sort = $this->getSort();

                $esResults = $search->getSearchResult();

                $this->setFilters($esResults, $repository);

                $esItems = $esResults['hits']['hits'];
                $this->totalRows = $esResults['hits']['total'];

                $this->searchHeaders = $repository->headers;
                $this->searchResults = $repository->show_table($esItems, $this->getQuery(), $this->getSelectedFilters());
                $this->currentRepositoryname = $repository->show_name;
                break;
            }
        }
    }

    // Rcurrent repository filter set.
    private function setRepositories() {
        $resultSet = [];
        $search_all = $this->get_search_object_of_all_repositories();
        $esResults_all = $search_all->getSearchResult();

        foreach ($esResults_all['aggregations']['_index']['buckets'] as $bucket) {
            $key = explode('_', $bucket['key'])[0];
            $repositories_counts[$key] = $bucket['doc_count'];
        }
        foreach ($this->repositoryHolder->getRepositories() as $repository) {
            $repositoryHits = $repositories_counts[$repository->index];

            $repository->num = $repositoryHits;

            $resultSet[$repository->show_name]['id'] = $repository->id;
            $resultSet[$repository->show_name]['selected'] = $repository->id == $this->getCurrentRepository() ? true : false;
            $resultSet[$repository->show_name]['rows'] = ($repositoryHits == NULL ? 0 : $repository->num);
            $resultSet[$repository->show_name]['whole'] = $repository->whole_name;
        }
        $this->repositories = $resultSet;
    }

    private function setFilters($esResults, $repository) {
        $keys = array_keys($esResults['aggregations']);

        $result = [];
        foreach ($keys as $key) {
            $terms = str_replace('"', '', $esResults['aggregations'][$key]['buckets']);
            $term_array = [];
            foreach ($terms as $term) {
                $name = '';
                $termKey = '';
                if (isset($term['key_as_string'])) {
                    $name = $this->encodeFacetsTerm($key, $term['key_as_string']);
                    $termKey = $term['key_as_string'];
                } else {
                    $name = $this->encodeFacetsTerm($key, $term['key']);
                    $termKey = $term['key'];
                }
                $selected = (isset($this->selectedFilters) && array_key_exists($key, $this->selectedFilters) && in_array($termKey, $this->selectedFilters[$key])) ? true : false;
                array_push($term_array, ['tag_display_name' => $termKey, 'name' => $name, 'count' => $term['doc_count'], 'selected' => $selected]);
            }
            $displayName = $repository->facets_show_name[$key];
            array_push($result, ['key' => $key, 'display_name' => $displayName, 'terms' => $term_array]);
        }
        $this->filters = $result;
    }

    private function encodeFacetsTerm($key, $value) {
        // Replace . with '____'
        $value1 = str_replace('.', '____', $value);
        // Replace space as '___'
        $value2 = str_replace(' ', '___', $value1);
        $value3 = str_replace('"', '', $value2);
        return $key . ':' . $value3;
    }

    public function setRowRange() {
        $start = ((($this->offset - 1) * $this->getRowsPerPage()) + 1);
        $end = (($this->offset - 1) * $this->getRowsPerPage()) + $this->getRowsPerPage();
        $this->rowRange = $start . '-' . $end;
    }

    public function getUrlWithQyery() {
        return 'search-repository.php?query=' . $this->getQuery() . '&repository=' . $this->getCurrentRepository() . '&searchtype=' . $this->searchtype;
    }

    public function getUrlByOffset($newOffset) {
        $returnValue = $this->getUrlWithQyery() . '&offset=' . $newOffset;
        $returnValue .= $this->getSortUrl();
        $returnValue .= $this->getRowsPerPageQueryString();
        $returnValue .= $this->getFiltersUrl();
        return $returnValue;
    }

    public function getUrlBySort($sort) {
        $returnValue = $this->getUrlWithQyery() . '&offset=1&sort=' . $sort;
        $returnValue .= $this->getRowsPerPageQueryString();
        $returnValue .= $this->getFiltersUrl();
        return $returnValue;
    }

    public function getUrlByRowsPerPage($rowsPerPage) {
        $returnValue = $this->getUrlWithQyery() . '&offset=1&rowsPerPage=' . strval($rowsPerPage);
        $returnValue .= $this->getSortUrl();
        $returnValue .= $this->getFiltersUrl();
        return $returnValue;
    }

    public function getUrlByFilter($facet, $filter) {
        // return $filter;
        $filters = [];
        if (isset($this->selectedFilters)) {
            $filters = $this->selectedFilters;
        }
        if (array_key_exists($facet, $filters)) {
            if (in_array($filter, $filters[$facet])) {
                $filters[$facet] = array_diff($filters[$facet], [$filter]);
                if (count($filters[$facet]) == 0) {
                    unset($filters[$facet]);
                }
            } else {
                array_push($filters[$facet], $filter);
            }
        } else {
            $filters[$facet] = [$filter];
        }

        if (count($filters) > 0) {
            $filtersText = '';
            foreach ($filters as $facetItem => $filterItems) {
                $filtersText .= $facetItem . '@';
                $filtersText .= implode(',', $filterItems);
                $filtersText .= '$';
            }
            $filtersText = substr($filtersText, 0, -1);
            return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl() . $this->getRowsPerPageQueryString() . '&filters=' . $filtersText;
        }
        return $this->getUrlWithQyery() . '&offset=1' . $this->getSortUrl() . $this->getRowsPerPageQueryString();
    }

    public function getUrlByRepository($repository) {
        return 'search-repository.php?query=' . $this->getQuery() . '&repository=' . $repository . '&searchtype=' . $this->searchtype;
    }

    public function getbackUrlByRepository($repository) {
        return 'search.php?query=' . $this->getQuery() . '&repository=' . $repository . '&searchtype=' . $this->searchtype;
    }

    private function getRowsPerPageQueryString() {
        if (isset($this->rowsPerPage) && $this->rowsPerPage != 20) {
            return '&rowsPerPage=' . $this->rowsPerPage;
        }
        return '';
    }

    private function getSortUrl() {
        if (isset($this->sort) && $this->getSort() != 'relevance') {
            return '&sort=' . $this->getSort();
        }
        return '';
    }

    private function getFiltersUrl() {
        if (isset($this->selectedFilters)) {
            $filtersText = '';
            foreach ($this->selectedFilters as $facetItem => $filterItems) {
                $filtersText .= $facetItem . '@';
                $filtersText .= implode(',', $filterItems);
                $filtersText .= '$';
            }
            return '&filters=' . substr($filtersText, 0, -1);
        }
        return '';
    }

    public function getSearchResultsNoFilter() {
        $originalFacets = [];
        foreach ($this->repositoryHolder->getRepositories() as $repository) {
            if ($repository->id == $this->getCurrentRepository()) {
                if ($this->expanflag == 1 || $this->query==" ") { // if query term is null, don't use query expansion
                    $search = new ElasticSearch();
                } else {
                    $search = new ExpansionSearch();
                    //$search = new NLPSearch();
                }
                $search->search_fields = $repository->search_fields;
                $search->facets_fields = $repository->facets_fields;
                $search->query = $this->getQuery();
                $search->filter_fields = [];
                $search->es_index = $repository->index;
                $search->es_type = $repository->type;
                $search->offset = $this->getOffset();
                $search->sort = $this->getSort();
                $esResults = $search->getSearchResult();
                $keys = array_keys($esResults['aggregations']);

                foreach ($keys as $key) {
                    $terms = str_replace('"', '', $esResults['aggregations'][$key]['buckets']);
                    $term_array = [];
                    foreach ($terms as $term) {
                        if (isset($term['key_as_string'])) {
                            $name = $this->encodeFacetsTerm($key, $term['key_as_string']);
                            $termKey = $term['key_as_string'];
                        } else {
                            $name = $this->encodeFacetsTerm($key, $term['key']);
                            $termKey = $term['key'];
                        }
                        $selected = false;
                        array_push($term_array, ['tag_display_name' => $termKey, 'name' => $name, 'count' => $term['doc_count'], 'selected' => $selected]);
                    }
                    $displayName = $repository->facets_show_name[$key];
                    $originalFacets[$key] = ['display_name' => $displayName, 'terms' => $term_array];
                }
                return $originalFacets;
            }
        }
    }

    private function get_search_object_of_all_repositories() {
        $elasticSearchIndexes = getElasticSearchIndexes();

        if ($this->expanflag == 1 || $this->query==" ") { // if query term is null, don't use query expansion
            $search = new ElasticSearch();
        } else {
            $search = new ExpansionSearch();
            //$search = new NLPSearch();
        }


        $search->search_fields = $this->repositoryHolder->getSearchFields();
        $search->facets_fields = ['_index'];
        $search->facet_size = 300;
        $search->query = $this->getQuery();
        $search->filter_fields = [];
        $search->es_index = $elasticSearchIndexes;
        $search->es_type = '';
        $search->offset = $this->getOffset();
        $search->sort = $this->getSort();
        return $search;
    }

    public function get_selected_filters_info() {
        $show = '';
        foreach (array_keys($this->selectedFilters) as $key) {
            foreach ($this->selectedFilters[$key] as $value) {
                if (strlen($show) == 0) {
                    $show = $value;
                } else {
                    $show = $show . ', ' . $value;
                }
            }
        }
        if (strlen($show) > 0) {
            $show = '&nbsp;&nbsp;&nbsp;&nbsp;Filters activated: <strong>' . $show . '</strong>';
        }
        return $show;
    }

    public function isBoolSearch($query) {
        if (preg_match('/(AND|OR|NOT|\[|\])/', $query)) {
            return true;
        } else {
            return false;
        }
    }
}
