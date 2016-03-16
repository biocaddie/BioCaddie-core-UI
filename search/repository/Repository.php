<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class RepRepository extends RepositoryBase {

    public $show_name = 'repository';
    public $id = '0001';
    public $source = "http://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/dataset.cgi?study_id=";
    //public $search_fields = ['resource_name','resource_type','keyword'];
    public $search_fields = ['resource_name', 'resource_type', 'keyword', 'e_uid', 'abbrev', 'curationstatus', 'parent_organization', 'data_created', 'related_disease', 'species', 'supporting_agency', 'synonym', 'availability', 'uses', 'listedby', 'lists', 'relatedto'];
    public $facets_fields = ['resource_type'];
    public $facets_show_name = ['resource_type' => 'Resource Type'];
    public $index = 'repository';
    public $type = 'list';
    //public $headers = ['Resource Name','Resource Type','Keyword','Supporting Agency','LINK', 'EUID','Abbrevation','Curation Status','Parent Organization','Data Created','Related Disease','Species','Synonum','Availability','Uses','Listedby','Lists','Relatedto'];
    //public $header_ids = ['resource_name','resource_type','keyword','supporting_agency','url','euid','abbrev','curationstatus','parent_organization','data_created','related_disease','species','supporting_agency','synonym','availability','uses','listedby','lists','relatedto'];
    public $datasource_headers = ['resource_name', 'resource_type', 'keyword', 'supporting_agency'];
    //public $datasource_headers=['resource_name','resource_type','keyword','supporting_agency','url','e_uid','abbrev','curationstatus','parent_organization','data_created','related_disease','species','supporting_agency','synonym','availability','uses','listedby','lists','relatedto'];

    public $core_fields = ['resource_name', 'resource_type', 'keyword', 'supporting_agency'];
    public $core_fields_show_name = ['resource_name' => 'Resource Name',
        'resource_type' => 'Resource Type',
        'keyword' => 'Keyword',
        'supporting_agency' => 'Supporting Agency'];
    //public $link_field = 'url';
    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/gap';
    public $sort_field = 'resource_name';

    public function show_table($results, $query, $filters) {
        $show_array = [];
        $ids = $this->header_ids;

        $filtersText = "";
        foreach ($filters as $facetItem => $filterItems) {
            $filtersText .= $facetItem . '@';
            $filtersText .= implode(',', $filterItems);
            $filtersText .= '$';
        }
        if (isset($filters)) {
            $filtersText = '&filters=' . substr($filtersText, 0, strlen($filtersText) - 1);
        }

        for ($i = 0; $i < count($results); $i++) {
            $show_line = [];
            $r = $results[$i];

            foreach ($ids as $id) {
                $id_list = explode('.', $id);
                $id0 = $id_list[0];
                $id1 = $id_list[1];

                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                }

                if (isset($r['_source'][$id0][$id1])) {                    
                    $show = parent::shorten($r['_source'][$id0][$id1]);
                    if ($id == 'dataItem.title') {
                        $show = '<a database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataItem.ID&id=' . $r['_source']['dataItem']['ID'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataItem']['title'] . '</a>';
                    }
                    if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                        $show = 'n/a';
                    }
                }
                if ($id == 'dataItem.title' || $id == 'dataItem.description' || $id == 'citation.title') {
                    $show = '<div database="comment">' . parent::shorten($show) . '</div>';
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }

}

?>
