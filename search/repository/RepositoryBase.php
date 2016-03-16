<?php

class RepositoryBase {

    protected $show_name;
    protected $id;
    protected $source;
    public $search_fields;
    protected $facets_fields;
    protected $index;
    protected $type;
    public $num = '';
    public $datasource_headers;
    public $core_fields;
    public $link_field;
    public $headers;
    public $header_ids;
    public $source_main_page;

    public function set_name($newName) {
        $this->name = $newName;
    }

    public function get_name() {
        return $this->name;
    }

    public function set_id($newID) {
        $this->id = $newID;
    }

    public function get_id() {
        return $this->id;
    }

    public function set_source($newSource) {
        $this->source = $newSource;
    }

    public function get_source() {
        return $this->source;
    }

    public function shorten($value) {
        $maxLen = 100;
        return strlen($value) > $maxLen ? substr($value, 0, $maxLen) . '...' : $value;
    }

    public function show_table($results, $query, $filter) {
        
    }

    //public decode_filter_fields();
    public function decode_filter_fields($post_arrays) {
        //echo print_r($post_arrays);
        // $post_arrays ='year:2012' => on, 'citation_year:2013 => 'on' ,'name:phenylalanine' =>'on'];
        //return $filters = ['year=>['2012','2013'],
        //                      'name'=>['phenylalanine']];
        $result = [];
        $keys = array_keys($post_arrays);
        foreach ($keys as $key) {
            $values = explode(':', $key);
            $filter = $values[0];
            $filter_value = $values[1];
            $filter_value = str_replace('____', '.', $filter_value);
            $filter_value = str_replace('___', ' ', $filter_value);
            if (array_key_exists($key, $result)) {
                array_push($result[$filter], $filter_value);
            } else {
                $result[$filter] = [$filter_value];
            }
        }
        return $result;
    }

    //abstract protected function show_table();
    //abstract protected function get_search_fields();
    //abstract protected function get_facets_info();
    //abstract protected function get_es_query_info();
}

?>