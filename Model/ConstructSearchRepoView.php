<?php

/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 7/13/16
 * Time: 4:35 PM
 */
require_once dirname(__FILE__) . '/ConstructSearchView.php';
require_once dirname(__FILE__) . '/Utilities.php';

class ConstructSearchRepoView extends ConstructSearchView {
    /*
     * Header of the search results table on search repository page
     * @var array(string)
     * */

    private $repoHeader;
    /*
     * Search Results
     * @var array(string)
     * */
    private $searchResults;

    /*
     * an array of data for repository specific filters
     * @var array(array(string))
     * */
    private $repositoryFacets;

    public function __construct($searchBuilder) {
        parent::__construct($searchBuilder);

        $searchBuilder = $this->getSearchBuilder();
        $this->setSearchResults();

        $repo_id = $searchBuilder->getSelectedRepositories()[0];
        $repository = $searchBuilder->getRepositories()->getRepository($repo_id);
        $this->setRepositoryFacets($searchBuilder->getSingleRepoResultsNoFilter(), $searchBuilder->getSingleRepoElasticSearchResults(), $repository);
    }

    /**
     * @return array(string)
     */

    /**
     * @return mixed
     */
    public function getRepositoryFacets() {
        return $this->repositoryFacets;
    }

    /**
     * generate an array of data for repository specific filters on the search repository page
     * @param
     *  $esResultsNoFilter: object
     *  $esResults: object
     *  $repository: object
     * @return array(array(string))
     * Filter
     *  1) key:elasticsearch field name
     *  2) display_name: name displayed on user interface
     *  3) term: an array of filter items
     * Filter items
     *  1) tag_display_name: name displayed on user interface
     *  2) name: elasticsearch field name
     *  3) count: number of search results
     *  4) selected: true or false
     *  5) url
     */
    public function setRepositoryFacets($esResultsNoFilter, $esResults, $repository) {
        $result = [];
        $keys = array_keys($esResults['aggregations']);
        $selected_filters = $this->getSearchBuilder()->getSelectedFilters();
        $displayed_terms = [];

        foreach ($keys as $key) {
            $filter_item = [];
            $termsNoFilter = str_replace('"', '', $esResultsNoFilter['aggregations'][$key]['buckets']);
            $terms = str_replace('"', '', $esResults['aggregations'][$key]['buckets']);
            $displayed_terms = [];
            $selected_flag = 0;

            // For selected filters
            foreach ($terms as $term) {
                isset($term['key_as_string']) ? $term_key = 'key_as_string' : $term_key = 'key';
                $name = encodeFacetsTerm($key, $term[$term_key]);
                $tag_display_name = $term[$term_key];
                $selected = (isset($selected_filters) && array_key_exists($key, $selected_filters) && in_array($tag_display_name, $selected_filters[$key])) ? true : false;
                $url = $this->getUrlByFilter($key, $tag_display_name);
                $display = true;

                if ($tag_display_name != "") {
                    array_push($filter_item, ['tag_display_name' => $tag_display_name, 'name' => $name, 'count' => $term['doc_count'], 'selected' => $selected, 'url' => $url, 'display' => $display]);
                    array_push($displayed_terms, $tag_display_name);
                }
            }

            // For other filters
            foreach ($termsNoFilter as $term) {
                isset($term['key_as_string']) ? $term_key = 'key_as_string' : $term_key = 'key';
                $name = encodeFacetsTerm($key, $term[$term_key]);
                $tag_display_name = $term[$term_key];

                if (!in_array($tag_display_name, $displayed_terms) && $tag_display_name != "") {

                    $selected = false;
                    $url = $this->getUrlByFilter($key, $tag_display_name);
                    $display = false;
                    array_push($filter_item, ['tag_display_name' => $tag_display_name, 'name' => $name, 'count' => $term['doc_count'], 'selected' => $selected, 'url' => $url, 'display' => $display]);
                }
            }

            $displayName = $repository->facetsShowName[$key];
            array_push($result, ['key' => $key, 'display_name' => $displayName, 'terms' => $filter_item]);
        }
        $this->repositoryFacets = $this->sortByNum($result);
    }

    public function getURLWithFilter($facet, $filter) {
        $filters = [];
        $selected_filters = $this->getSearchBuilder()->getSelectedFilters();
        if ($selected_filters != NULL) {
            $filters = $selected_filters;
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
            return '&filters=' . $filtersText;
        } else {
            return '';
        }
    }

    public function getUrlByFilter($facet, $filter) {
        $filtersText = $this->getURLWithFilter($facet, $filter);
        return $this->getSortUrl(). '&offset=1' . $filtersText;
    }

    public function getURLWithFilters() {
        $selected_filters = $this->getSearchBuilder()->getSelectedFilters();
        if ($selected_filters != NULL) {
            $filtersText = '';
            foreach ($selected_filters as $facetItem => $filterItems) {
                $filtersText .= $facetItem . '@';
                $filtersText .= implode(',', $filterItems);
                $filtersText .= '$';
            }
            return '&filters=' . substr($filtersText, 0, -1);
        }
        return '';
    }

    /*
     * @param: int, offset
     * @return: string, URL
     * */

    public function getUrlByOffset($newOffset) {
        return $this->getUrlWithQuery()
                . '&offset=' . $newOffset
                . $this->getURLWithRepository()
                . $this->getURLWithFilters()
                . $this->getCurrentSort();
    }

    public function clearAllFiltersURL() {
        return $this->getUrlWithQuery() . $this->getURLWithRepository();
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
                $this->getURLWithFilters() .
                $this->getCurrentSort();
    }

    public function getRepoHeader() {
        return $this->repoHeader;
    }

    /**
     * @return mixed
     */
    public function getSearchResults() {
        return $this->searchResults;
    }

    /*
     * Generate url by ID of selected repository
     * @param: string, ID of a repository
     * @return: string, URL
     * */

    public function getUrlBySelectedRepository($repoID) {
        $currentID = [];
        $url = 'search.php?query=' . $this->getSearchBuilder()->getQuery() . '&searchtype=' . $this->getSearchBuilder()->getSearchType() . '&offset=1';

        // Get IDs of currently selected repositories
        if (($this->getSearchBuilder()->getSelectedRepositories()) != NULL) {
            $currentID = $this->getSearchBuilder()->getSelectedRepositories();
        }

        // Add new ID if it is not in $currentID
        if (!in_array($repoID, $currentID)) {
            array_push($currentID, $repoID);
        } else {
            $currentID = array_diff($currentID, [$repoID]);
        }

        if (count($currentID) > 0) {
            return $url . '&repository=' . implode(',', $currentID);
        }
        return $url;
    }

    /**
     * format the data for the search results column
     */
    public function setSearchResults() {
        $es_results = $this->getSearchBuilder()->getSingleRepoElasticSearchResults();
        $es_items = $es_results['hits']['hits'];

        // Get selected repository
        $repo_id = $this->getSearchBuilder()->getSelectedRepositories()[0];
        $repository = $this->getSearchBuilder()->getRepositories()->getRepository($repo_id);

        $this->repoHeader = $repository->searchRepoHeader;
        $this->searchResults = $repository->show_table($es_items, $this->getSearchBuilder()->getQuery());
    }

    public function cancel_repository_if_unselect($id, $newid) {
        ($id == $newid) ? $url = '' : $url = '&repository=' . $id . ',' . $newid;
        return $url;
    }

}
