<?php

/**
 * Represents repositories base functionality.
 */
class RepositoryBase
{

    /**
     * The repository unique id, assigned manually to distinguish different instances.
     * @var string
     */
    public $id;

    /**
     * The repository unique index name used in ElasticSearch.
     * This field will be used to run queries against ElasticSearch.
     * @var string
     */
    public $index;

    /**
     * The repository type.
     * [NEEDS DISCUSSION]
     * @var string
     */
    public $type;

    /**
     * The display name that should be used
     * @var string
     */
    public $repoShowName;
    /**
     * The display name of repo when use tooltip
     * @var string
     */
    public $wholeName;
    /**
     * Repository URL to access data.
     * @var string
     */
    public $source; //may not need

    /**
     * Home page of the repository.
     * @var string
     */
    public $sourceMainPage;//may not need

    /**
     * Specifies the fields ElasticSearch uses to run the search.
     * @var array(string)
     */
    public $searchFields;

    /**
     * Specifies the list of fields displayed in "search.php" page.
     * @var array(string)
     */
    //public $datasource_headers;
    public $searchPageField;
    public $searchPageHeader;
    /**
     * Specifies a list of fields displayed in "search-repository.php" page.
     * @var array(string)
     */
    //public $headers;
    public $searchRepoHeader;

    /**
     * Specifies a list of display values for headers in "search-repository.php" page.
     * @var array(string)
     */
    //public $header_ids;
    public $searchRepoField=['_all'];

    /*
     *  Specifies the list of fields to be used for facets' filtering.
     * @var array(string)
     */
    public $facetsFields;

    /**
     *  Indicates the list of display values for facets' filtering.
     * @var array(key(string), value(string))
     */
    public $facetsShowName;

    /**
     * THIS SHOULD BE REMOVED
     * @var string
     */
    public $num = '';
    public $link_field;
    // REPLACE WITH THREE NEW METHODS.
    public function show_table($results, $query)
    {
        $show_array = get_search_repo_common_view($this->searchRepoField, $results, $query, $this->id);
        return $show_array;
    }

    /**
     * Returns an array of results to be displayed in "search-repository.php" page.
     * @param type $model
     * @return array(key(string), value(string))
     */
    public function getSearchRepositoryView($model)
    {
        // Title
        // Url
    }

    /**
     * Returns an array of results to be displayed in "display-item.php" page.
     * @param type $model
     * @return array(key(string), value(string))
     */
    public function getDisplayItemView($rows)
    {
        // Main Panel: Key/Value Array of Items
        // Array of Entities (Key/Value Pairs)
        // Each Entity Represents an Array of Properties (Key/Value Pairs)
        $search_results = get_display_item_common_view($this->id,$rows);
        return $search_results;
    }

    /**
     * Decodes ElasticSearch facets to be used for the search query. [Moved To Utility Class]
     * @param UNKNOWN $post_arrays
     * @return array(UNKNOWN)
     */
    /*public function decode_filter_fields($post_arrays)
    {
        $result = [];
        $keys = array_keys($post_arrays);
        foreach ($keys as $key) {
            $values = explode(':', $key);
            $filter = $values[0];
            $replaceList = ['____' => '.', '___', ' '];
            $filter_value = str_replace(array_keys($replaceList), array_keys(array_values), $values[1]);
            if (array_key_exists($key, $result)) {
                array_push($result[$filter], $filter_value);
            } else {
                $result[$filter] = [$filter_value];
            }
        }
        return $result;
    }*/

}

?>