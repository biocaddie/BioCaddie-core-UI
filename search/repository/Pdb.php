<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

/* The PDB archive contains information about experimentally-determined structures of proteins, 
 * nucleic acids, and complex assemblies. As a member of the wwPDB, the RCSB PDB curates and 
 * annotates PDB data according to agreed upon standards. The RCSB PDB also provides a variety 
 * of tools and resources. Users can perform simple and advanced searches based on annotations 
 * relating to sequence, structure and function. These molecules are visualized, downloaded, 
 * and analyzed by users who range from students to specialized scientists.
 */

class PdbRepository extends RepositoryBase {

    public $show_name = 'PDB';
    public $whole_name ='RCSB&nbsp;Protein&nbsp;Data&nbsp;Bank';
    public $id = '0002';
    public $source = "http://www.rcsb.org/pdb/explore/explore.do?structureId=";
    public $search_fields = ['dataItem.ID', 'dataItem.title', 'dataItem.keywords', 'dataItem.description',
        'citation.title', 'citation.journal'];
    public $facets_fields = ['citation.journal', "dataItem.keywords",'organism.source.scientificName'];
    public $facets_show_name = [
        'citation.journal' => 'Journal',
        "dataItem.keywords" => 'Keywords',
        'organism.source.scientificName'=>"Organism Source"
        ];
    public $index = 'pdb';
    public $type = 'dataset';
    public $headers = ['Dataset Title', 'Dataset ID', 'Citation Title', 'Source Scientific Name'];
    public $header_ids = [ 'dataItem.title', 'dataItem.ID', 'citation.title', 'organism.source.scientificName'];
    public $datasource_headers = ['dataItem.title', 'dataItem.ID', 'dataItem.description'];
    public $core_fields = [
        'dataItem.ID',
        'dataItem.dataTypes',
        'dataItem.depositionDate',
        'dataItem.title',
        'dataItem.keywords',
        'dataItem.description',
        'dataItem.releaseDate',
        'Citation',
        'citation.title',
        'citation.journal',
        'citation.year',
        'citation.author.name',
        'citation.PMID',
        'citation.firstPage',
        'citation.lastPage',
        'citation.journalISSN',
        'citation.DOI',
        'Organism',
        'organism.source',
        'organism.host',
        'DataResource',
        'dataResource.name',
        'dataResource.url',
        'dataResource.description',
        'dataResource.keywords'];
    public $core_fields_show_name = [
        'dataItem.ID' => 'ID',
        'dataItem.dataTypes' => 'Data types',
        'dataItem.depositionDate' => 'Deposition date',
        'dataItem.title' => 'Title',
        'dataItem.keywords' => 'Keywords',
        'dataItem.description' => 'Description',
        'dataItem.releaseDate' => 'Release date',
        'citation.title' => 'Title',
        'citation.journal' => 'Journal',
        'citation.year' => 'Year',
        'citation.author.name' => 'Author names',
        'citation.PMID' => 'PMID',
        'citation.firstPage' => 'First page',
        'citation.lastPage' => 'Last page',
        'citation.journalISSN' => 'Journal ISSN',
        'citation.DOI' => 'DOI',
        'organism.source' => 'Source',
        'organism.host' => 'Host',
        'dataResource.name' => 'Name',
        'dataResource.url' => 'URL',
        'dataResource.description' => 'Description',
        'dataResource.keywords' => 'Keywords',
        'Citation' => '<strong>Citation</strong>',
        'Organism' => '<strong>Organism</strong>',
        'DataResource' => '<strong>Data Resource</strong>'];
    public $link_field = 'dataItem.title';
    public $source_main_page = 'http://www.rcsb.org/pdb/home/home.do';
    public $sort_field = 'citation.year';

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
                $idLevel = count($id_list);

                $id0 = $id_list[0];
                $id1 = $id_list[1];

                if ($idLevel == 3) {
                    $id2 = $id_list[2];

                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1][$id2] = implode(' ', $r['highlight'][$id]);
                    }
                    if (isset($r['_source'][$id0][$id1][0][$id2])) {
                        $show = parent::shorten($r['_source'][$id0][$id1][0][$id2]);                        
                        if ($r['_source'][$id0][$id1][0][$id2] == '' || $r['_source'][$id0][$id1][0][$id2] == ' ') {
                            $show = 'n/a';
                        }
                    }
                } else {
                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                    }
                    if (isset($r['_source'][$id0][$id1])) {
                        $show = parent::shorten($r['_source'][$id0][$id1]);
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = 'n/a';
                        }
                    }
                }

                // Link data set to display-item page
                if ($id == 'dataItem.title') {
                    //$show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataItem.ID&id=' . $r['_source']['dataItem']['ID'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataItem']['title'] . '</a>';
                    $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataItem']['title'] . '</a>';
                }

                // Link citation to original citation page
                if ($id == 'citation.title') {
                    if (isset($r['_source']['citation']['PMID'])) {
                        $citationPMID = explode(':', $r['_source']['citation']['PMID'])[1];
                        $citationLink = "http://www.ncbi.nlm.nih.gov/pubmed/" . $citationPMID;
                        $show = $show . '<a class=hyperlink href="' . $citationLink . '" target="_blank">PubMed</a>';
                    } else {
                        $show = $show;
                    }
                }
                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }

    public function decode_filter_fields($post_arrays) {
        $result = [];
        $keys = array_keys($post_arrays);
        foreach ($keys as $key) {
            $values = explode(':', $key);
            $filter = $values[0];
            $filter = str_replace('_', '.', $filter);
            $filter_value = $values[1];

            $filter_value = str_replace('____', '.', $filter_value);
            $filter_value = str_replace('___', ' ', $filter_value);
            if (array_key_exists($filter, $result)) {
                array_push($result[$filter], $filter_value);
            } else {
                $result[$filter] = [$filter_value];
            }
        }
        return $result;
    }

}

?>