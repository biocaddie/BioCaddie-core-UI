<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

/* The PDB archive contains information about experimentally-determined structures of proteins, 
 * nucleic acids, and complex assemblies. As a member of the wwPDB, the RCSB PDB curates and 
 * annotates PDB data according to agreed upon standards. The RCSB PDB also provides a variety 
 * of tools and resources. Users can perform simple and advanced searches based on annotations 
 * relating to sequence, structure and function. These molecules are visualized, downloaded, 
 * and analyzed by users who range from students to specialized scientists.
 */

class PdbRepository extends RepositoryBase {

    public $repoShowName = 'PDB';
    public $wholeName ='RCSB&nbsp;Protein&nbsp;Data&nbsp;Bank';
    public $id = '0002';
    public $source = "http://www.rcsb.org/pdb/explore/explore.do?structureId=";
    //public $searchFields = ['dataAcquisition.name',
   //                         'dataset.ID','dataset.creators', 'dataset.title', 'dataset.keywords', 'dataset.description','dataset.types',
   //                         'primaryPublications.ID','primaryPublications.alternateID','primaryPublications.title', 'primaryPublications.author','primaryPublications.year',
   //                         'identifiers.ID', 'gene.name', 'material.name','material.formula','material.role', 'citationInfo.citations',
    //                        'taxonomicInformation.ID','taxonomicInformation.name','taxonomicInformation.species','taxonomicInformation.strain'];
    public $facetsFields = ["dataset.keywords",'gene.name'];
    public $facetsShowName = [
        "dataset.keywords" => 'Keywords',
        'gene.name'=>"Gene"
        ];
    public $index = 'pdb';
    public $type = 'dataset';
    public $searchRepoHeader = ['Dataset Title', 'Dataset ID', 'Primary Publications', 'Keywords'];
    public $searchRepoField = [ 'dataset.title', 'dataset.ID', 'primaryPublications.title', 'dataset.keywords'];

    public $searchPageField  = ['dataset.title', 'dataset.ID', 'dataset.description'];
    public $searchPageHeader  = [
        'dataset.ID' => 'ID',
        'dataset.title' => 'Title',
        'dataset.description' => 'Description'];


    public $source_main_page = 'http://www.rcsb.org/pdb/home/home.do';
    public $sort_field = '';
    public $description='The Protein Data Bank (PDB) archive is the single worldwide repository of information about the 3D structures of large biological molecules, including proteins and nucleic acids found in all organisms including bacteria, yeast, plants, flies, other animals, and humans.';


    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);
        $search_results['title'][2]=$this->source.substr($rows['dataset']['ID'],4);

        return $search_results;
    }

    /*public function getDisplayItemView($rows)
    {

        $search_results = [];
        $logo_link_icon = '&nbsp;&nbsp;&nbsp;<img style="height: 20px ;width:40px" src="./img/repositories/'. $this->id.'.png">';
        $search_results['logo']=$logo_link_icon;
        $search_results['repo_id']=$this->id;
        $search_results['show_order']=['dataset','identifiers','taxonomicInformation','dataAcquisition','gene','primaryPublications','citationInfo','material','datasetDistributions','organization','dataRepository'];
        foreach(array_keys($rows) as $key){
            if(in_array($key,['dataItem'])){
                continue;
            }
            $newrows = $rows[$key];

            $search_results[$key]=[];

            if($key=='identifiers'){
                $search_results[$key]=[['ID',convert_array_to_string($rows[$key],'ID')]];
                continue;
            }
            if($key=='datasetDistributions'){
                $result = convert_datasetDist_to_array($rows[$key]);
                foreach($result as $item){
                    array_push($search_results[$key],$item);
                }
                continue;
            }
            if($key==='primaryPublications'){
                $newrows = $rows[$key][0];
            }
            foreach(array_keys($newrows) as $subkey){
                if(sizeof($newrows[$subkey])==0){
                    continue;
                }

                if(($key=='datasetDistributions')){
                    $subnewrows = $newrows[$subkey];
                    foreach(array_keys($subnewrows) as $subkey2){
                        $display_value = $subnewrows[$subkey2];
                        array_push($search_results[$key][$subkey],[$subkey2,$display_value]);
                    }
                }

                if($subkey=='author'){
                    $display_value = is_array($newrows[$subkey]) ? convert_array_to_string_one_level($newrows[$subkey]['name']) : $newrows[$subkey];
                }
                else{
                    $display_value = is_array($newrows[$subkey]) ? convert_array_to_string_one_level($newrows[$subkey]) : $newrows[$subkey];

                }
                if($key=='organism' || $key=='material' || $key=='taxonomicInformation'||$key=='gene'){
                    $display_value = is_array($newrows[$subkey]) ? convert_array_to_string_two_level($newrows[$subkey]) : $newrows[$subkey];
                    array_push($search_results[$key],[$subkey,$display_value]);
                    continue;
                }

                $a = check_url($display_value);

                if($key=='dataset'&&$subkey=='title') {

                    $search_results['title']=[$subkey, $display_value, $this->source.substr($rows['dataset']['ID'],4)];
                }
                elseif($a && $subkey!=='journal'){
                    array_push($search_results[$key],[$subkey,$display_value,$display_value]);
                }
                elseif($key=='primaryPublications' and $subkey=='ID'){
                    array_push($search_results[$key],[$subkey,substr($display_value,5),'https://www.ncbi.nlm.nih.gov/pubmed/'.substr($display_value,5)]);
                }
                else{
                    array_push($search_results[$key],[$subkey,$display_value]);
                }

            }
        }

        return $search_results;
    }*/

}

?>