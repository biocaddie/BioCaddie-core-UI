<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class EmdbRepository extends RepositoryBase
{

    public $repoShowName = 'PDBe:EMDB';
    public $wholeName = 'The&nbsp;Electron&nbsp;Microscopy&nbsp;Data&nbsp;Bank&nbsp;(EMDB)&nbsp;at&nbsp;PDBe';
    public $id = '0031';
    public $source = "http://www.ebi.ac.uk/pdbe/emdb/";
   // public $searchFields = ['dataset.ID', 'dataset.title', 'dataset.creators', 'dataset.types', 'primaryPublication.title', 'primaryPublication.authors', 'MolecularEntity.ID', 'Instument.name'];
    public $facetsFields = ['Instrument.name.raw', 'molecularEntity.ID.raw', 'dataset.types.raw'];
    public $facetsShowName = [
        'Instrument.name.raw' => 'Instrument',
        'molecularEntity.ID.raw' => 'Molecular Entity',
        'dataset.types.raw' => 'Dataset Types'
    ];
    public $index = 'emdb';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.types', 'dataset.creators', 'dataset.dateReleased'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.types' => 'Types',
        'dataset.creators' => 'Person',
        'dataset.dateReleased' => 'Release Date'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Types', 'Instument', 'Released Date'];
    public $searchRepoField = ['dataset.title', 'dataset.types', 'Instrument.name', 'dataset.dateReleased'];

    public $source_main_page = "http://thedata.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Electron Microscopy Data Bank (EMDB) is a public repository for electron microscopy density maps of macromolecular complexes and subcellular structures. It covers a variety of techniques, including single-particle analysis, electron tomography, and electron (2D) crystallography.
The EMDB was founded at EBI in 2002, under the leadership of Kim Henrick. Since 2007 it has been operated jointly by the PDBe, and the Research Collaboratory for Structural Bioinformatics (RCSB PDB) as a part of EMDataBank which is funded by a joint NIH grant to PDBe, the RCSB and the National Center for Macromolecular Imaging (NCMI).';
    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);

        for($i=0;$i<sizeof($search_results['dataset']);$i++){
            if($search_results['dataset'][$i][0]=='hasPart'){
                $search_results['dataset'][$i][1]='';
                $search_results['dataset'][$i][2]='';
            }
        }


        return $search_results;
    }
}

?>