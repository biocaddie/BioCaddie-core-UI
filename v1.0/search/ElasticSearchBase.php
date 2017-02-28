<?php

abstract class ElasticSearchBase {

    public $search_fields;
    public $filter_fields;
    public $facets_fields;
    public $aggs_fields;
    public $query;
    public $offset;
    public $rowsPerPage;
    public $es_index;
    public $es_type;
    public $highlight;
    public $sort;
    public $order;

    abstract protected function getSearchRowCount();

    abstract protected function generateQuery();

    abstract protected function generateSearchBody();
    
    abstract protected function generateFilter();

    abstract protected function generateHighlight();

    abstract protected function generateFacets();

    abstract protected function generateAggs();

    
}

?>