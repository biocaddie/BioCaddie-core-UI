<?php

//some helper functions 
function get_previsoue($offset) {
    //get previous offset index
    if ($offset > 1) {
        $offset = $offset - 1;
    }
    return $offset;
}

function get_next($offset, $num, $N) {
    // get next offset index
    if ($offset < $num / $N) {
        $offset = $offset + 1;
    }
    return $offset;
}

function show_current_record_number($offset, $num, $N) {
    //show the record number in the current page
    if ($offset < $num / $N) {
        return ((($offset - 1) * $N) + 1) . "-" . ($offset) * $N;
    } else {
        return ((($offset - 1) * $N) + 1) . "-" . $num;
    }
}

//get facets from the query result
function get_facets1($query) {
    $keys = array_keys($query['facets']);
    //$keys = array_keys($query['aggregations']);
    echo print_r($keys);
    $result = [];
    foreach ($keys as $key) {
        $terms = $query['facets'][$key]['terms'];
        //$terms = $query['aggregations'][$key]['buckets'];
        $terms = str_replace('"', '', $terms);
        $term_array = [];
        foreach ($terms as $term) {
            $name = encode_facets_term($key, $term['term']);
            array_push($term_array, ['show_name' => $term['term'], 'name' => $name, 'count' => $term['count']]);
        }
        array_push($result, ['key' => $key,
            'term_array' => $term_array]);
    }
    return $result;
}

function get_facets($query) {
    //$keys = array_keys($query['facets']);
    $keys = array_keys($query['aggregations']);
    $result = [];
    foreach ($keys as $key) {
        //$terms = $query['facets'][$key]['terms'];
        $terms = $query['aggregations'][$key]['buckets'];
        $terms = str_replace('"', '', $terms);
        $term_array = [];
        foreach ($terms as $term) {
            if (isset($term['key_as_string'])) {
                $name = encode_facets_term($key, $term['key_as_string']);
                array_push($term_array, ['show_name' => $term['key_as_string'], 'name' => $name, 'count' => $term['doc_count']]);
            } else {
                $name = encode_facets_term($key, $term['key']);
                array_push($term_array, ['show_name' => $term['key'], 'name' => $name, 'count' => $term['doc_count']]);
            }
        }
        array_push($result, ['key' => $key,
            'term_array' => $term_array]);
    }
    return $result;
}

function encode_facets_term($key, $value) {
    //replace space as '___'
    //repalce . as '____'
    $value = str_replace('.', '____', $value);
    $value = str_replace(' ', '___', $value);
    $value = str_replace('"', '', $value);
    $term = $key . ':' . $value;
    return $term;
}

function convert_facets_post($string) {
    $terms = explode(':', $string);
    $key = str_replace('.', '_', $terms[0]);
    $value = $terms[1];
    $value = str_replace('"', '', $value);
    $newString = $key . ':' . $value;
    return $newString;
}

function get_rid_of_sessionname($var) {
    $result = [];
    foreach (array_keys($var) as $key) {
        if ($key == 'SessionName') {
            continue;
        }
        $result[$key] = $var[$key];
    }
    return $result;
}

?>