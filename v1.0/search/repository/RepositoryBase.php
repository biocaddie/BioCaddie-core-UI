<?php

/**
 * Represents repositories base funcitonalities.
 */
class RepositoryBase {

    /**
     * The repository unique id, assigned mannually to distinguish different instances.
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
    public $show_name;

    /**
     * Repository URL to access data.
     * This URL is only used to review the repository content mannually.
     * @var string 
     */
    public $source;

    /**
     * DUPLICATE?
     * @var string
     */
    public $source_main_page;

    /**
     * Specifies the fields ElasticSearch uses to run the search.
     * @var array(string)
     */
    public $search_fields;

    /**
     * Specifies the list of fields displayed in "search.php" page.
     * @var array(string)
     */
    public $datasource_headers;

    /**
     * Specifies a list of fields displayed in "search-repository.php" page.
     * @var array(string)
     */
    public $headers;

    /**
     * Specifies a list of display values for headers in "search-repository.php" page.
     * @var array(string)
     */
    public $header_ids;

    /**
     * Specifies the list of fields displayed in "display-item.php" page.
     * @var array(string)
     */
    public $core_fields;

    /**
     *  Specifies the list of fields to be used for facets' filtering.
     * @var array(string)
     */
    public $facets_fields;

    /**
     *  Indicates the list of display values for facets' filtering.
     * @var array(key(string), value(string))
     */
    public $facets_show_name;

    /**
     * UNKNOWN
     * @var string
     */
    public $link_field;

    /**
     * THIS SHOULD BE REMOVED
     * @var string
     */
    public $num = '';

    public function shorten($value) {
        $maxLen = 100;
        return strlen($value) > $maxLen ? substr($value, 0, $maxLen) . '...' : $value;
    }

    // REPLACE WITH THREE NEW METHODS.
    public function show_table($results, $query, $filter) {
        
    }

    /**
     * Returns an array of results to be displayed in "search.php" page.
     * @param type $model
     * @return array(key(string), value(string))
     */
    public function get_search_view($model) {
        
    }

    /**
     * Returns an array of results to be displayed in "search-repository.php" page.
     * @param type $model
     * @return array(key(string), value(string))
     */
    public function get_search_repository_view($model) {
        
    }

    /**
     * Returns an array of results to be displayed in "display-item.php" page.
     * @param type $model
     * @return array(key(string), value(string))
     */
    public function get_display_item_view($model) {
        
    }

    /**
     * UNKNOWN
     * @param UNKNOWN $post_arrays
     * @return array(UNKNOWN)
     */
    public function decode_filter_fields($post_arrays) {
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
    }

}

?>