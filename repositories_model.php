<?php
class repository{
	protected $show_name;
    protected $id;
    protected $source;
    public $search_fields;// = ['_all'];
    protected $facets_fields;
    protected $index;
    protected $type;
    public $num = '';
    public $datasource_headers;
    //public $num_after_filter;

    public $headers;
    public $header_ids;

    public function set_name($newName){
       $this->name = $newName;
	}
	public function get_name(){
		return $this->name;
	}
	public function set_id($newID){
       $this->id = $newID;
	}
    public function get_id(){
       return $this->id;
	}
    public function set_source($newSource){
       $this->source = $newSource;
	}
    public function get_source(){
       return $this->source;
	}
	public function show_table($results){}
    //public decode_filter_fields();
    public function decode_filter_fields($post_arrays){
        // $post_arrays ='year:2012' => on, 'citation_year:2013 => 'on' ,'name:phenylalanine' =>'on'];
        //return $filters = ['year=>['2012','2013'],
        //                      'name'=>['phenylalanine']];
  	    $result = [];
        $keys=array_keys($post_arrays);
        foreach($keys as $key){
        	$values = explode(':',$key);
        	$filter = $values[0];
        	$filter_value = $values[1];
        	$filter_value = str_replace('____','.',$filter_value);
            $filter_value = str_replace('___',' ',$filter_value);
            if(array_key_exists($key,$result)){
            	array_push($result[$filter],$filter_value);
            }
            else{
            	$result[$filter]=[$filter_value];
            }
           }
        return $result;
    }

	//abstract protected function show_table();
	//abstract protected function get_search_fields();
	//abstract protected function get_facets_info();
	//abstract protected function get_es_query_info();

}

class dbgap_data extends repository{
	public $show_name = 'database of Genotypes and Phenotypes (dbGaP)';
	public $id = '0001';
	public $source = "http://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/dataset.cgi?study_id=";
	public $search_fields = ['title','category','disease','MESHterm'];
	public $facets_fields = ['ConsentType','IRB'];
	public $index = 'phenodisco';
	public $type = 'dbgap';
	public $headers = ['Title','Details','Sample Size','Embargo Relase','Platform','Geography','IRB','Consent Type','Topic disease'];
	public $header_ids = ['title','phenID','cohort','Type','platform','geography','IRB','ConsentType','category'];
	public $datasource_headers=['title','category','Type','ConsentType','path'];
	public function show_table($results){
       	$show_array = [];
       	$ids = $this->header_ids;
       	for($i=0;$i<count($results);$i++){
       		$show_line = [];
             $r = $results[$i];
             foreach($ids as $id){
                if(isset($r['highlight'][$id])){
                    $r['_source'][$id] = implode(' ',$r['highlight'][$id]);
                 }  
                if(isset($r['_source'][$id])){
                	if($id=='title'){
                		$show = '<a href="'.$this->source.$r['_source']['path'].'" target="_blank"><u>'.$r['_source']['path'].' '.$r['_source'][$id].'</u></a>';
                     }
                     elseif($id=='phenID'){
                     	$show = 'study has '.count(explode(' ',$r['_source'][$id])).' variable components';
                     }
                     else{
                     	if($r['_source'][$id]=='' ||$r['_source'][$id]==' '){
		     				$show = 'n/a';
		                  }
		                else{
		                	$show='';
		                    foreach(explode(';',$r['_source'][$id]) as $single){
			 				   $show = $show.$single.'<br>';
			                }
			            }
                     }
             }  
             array_push($show_line,$show);   
     }
     array_push($show_array,$show_line);
  }
   return $show_array;
  }
}



class PDB_data extends repository{
	public $show_name = 'RCSB Protein Data Bank';
	public $id = '0002';
	public $source = "http://www.rcsb.org/pdb/explore/explore.do?structureId=";
	public $search_fields = ['dataItem.ID','dataItem.title','dataItem.keywords','dataItem.description','citation.title'];
	public $facets_fields = ['citation.year',"materialEntity.name","dataItem.keywords"];
	public $index = 'pdb2';
	public $type = 'pdb2';
	public $headers =['ID','Title','Description','Citiation Title','Citiation Journal','Citiation year'];//,'Citation PMID',];//,'Citiation Authors'];
	public $header_ids =['dataItem.ID','dataItem.title','dataItem.description','citation.title','citation.journal','citation.year'];//,'citation.PMID',];//,'citation.author'];
	public $datasource_headers=['dataItem.title','dataItem.ID','dataItem.description','dataItem.ID'];
	
	public function show_table($results){
		//echo 'yes yes';
       	$show_array = [];
       	$ids = $this->header_ids;
       	//echo print_r($results);
       	for($i=0;$i<count($results);$i++){
       		 $show_line = [];
             $r = $results[$i];
             
             foreach($ids as $id){
             	$id_list = explode('.',$id);
             	$id0=$id_list[0];
             	$id1=$id_list[1];
                if(isset($r['highlight'][$id])){
                    $r['_source'][$id0][$id1] = implode(' ',$r['highlight'][$id]);
                 }  
                if(isset($r['_source'][$id0][$id1])){
                	$show = $r['_source'][$id0][$id1];

                	if($id=='dataItem.ID'){
                		$show = '<a href="'.$this->source.$r['_source']['dataItem']['ID'].'" target="_blank"><u>'.$r['_source']['dataItem']['ID'].'</u></a>';
                     }

                    if($r['_source'][$id0][$id1]=='' ||$r['_source'][$id0][$id1]==' '){
		     				$show = 'n/a';
		               }      
             }  
             array_push($show_line,$show);   
     }
     array_push($show_array,$show_line);
  }
   return $show_array;
  }

   public function decode_filter_fields($post_arrays){
        // $post_arrays ='citation_year:2012' => on, 'citation_year:2013 => 'on' ,'materialEntity_name:phenylalanine' =>'on'];
        //return $filters = ['citation.year=>['2012','2013'],
        //                      'materialEntity.name'=>['phenylalanine']];
  	    $result = [];
        $keys=array_keys($post_arrays);
        foreach($keys as $key){
        	$values = explode(':',$key);
        	$filter = $values[0];
        	$filter = str_replace('_','.',$filter);
        	$filter_value = $values[1];
        	$filter_value = str_replace('____','.',$filter_value);
            $filter_value = str_replace('___',' ',$filter_value);
            if(array_key_exists($key,$result)){
            	array_push($result[$filter],$filter_value);
            }
            else{
            	$result[$filter]=[$filter_value];
            }
           }
        return $result;
    }

}


class GEO_data extends repository{
	public $show_name = 'Gene Expression Omnibus';
	public $id = '0003';
	//public $source = "http://www.ncbi.nlm.nih.gov/sites/GDSbrowser?acc=";
	public $source ="http://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=";
	public $search_fields = ['title','description','source_name','Type','series','ID'];
	public $facets_fields = ["organism","platform",'entry_type'];
	public $index = 'geo';
	public $type = 'geo';
	public $headers = ['Title','Description','Type','Assays','GEO Accession','Organism','Platform','Series'];//,'Link','Source name'];
	public $header_ids = ['title','description','Type','assays','geo_accession','organism','platform','series'];//,'link','source_name'];
	public $datasource_headers=['title','geo_accession','platform','series','geo_accession'];
	public function show_table($results){
		$show_array = [];
       	$ids = $this->header_ids;
       	for($i=0;$i<count($results);$i++){
       		 $show_line = [];
             $r = $results[$i];
             
             foreach($ids as $id){
             	
                if(isset($r['highlight'][$id])){
                    $r['_source'][$id] = implode(' ',$r['highlight'][$id]);
                 }  
                if(isset($r['_source'][$id])){
                	$show = $r['_source'][$id];

                	if($id=='title'){
                		$show = '<a href="'.$this->source.$r['_source']['geo_accession'].'" target="_blank"><u>'.$r['_source']['title'].'</u></a>';
                     }

                    if($r['_source'][$id]=='' ||$r['_source'][$id]==' '){
		     				$show = 'n/a';
		               } 
		            if(is_array($show)){
		            	$show = implode('<br>',$show);
		            }     
             }  
             array_push($show_line,$show);   
     }
     array_push($show_array,$show_line);
  }
   return $show_array;
  }

	
}

class LINCS_data extends repository{
	public $show_name = 'LINCS';
	public $id = '0004';
	public $source = "https://lincs.hms.harvard.edu/db/datasets/";
	public $search_fields = ['title','description','protocol_reference'];
	public $facets_fields = ['Type'];
	public $index = 'geo';
	public $type = 'LINCS';
	public $headers = ['ID','Title','Description','Type','Protocol reference','Released','Most recently updated'];
  	public $header_ids =  ['ID','title','Type','description','protocol_reference','released','most_recently_updated'];
  	public $datasource_headers = ['title','ID','description','ID'];
  	public function show_table($results){
		$show_array = [];
       	$ids = $this->header_ids;
       	for($i=0;$i<count($results);$i++){
       		 $show_line = [];
             $r = $results[$i];
             foreach($ids as $id){
                if(isset($r['highlight'][$id])){
                    $r['_source'][$id] = implode(' ',$r['highlight'][$id]);
                 }  
                if(isset($r['_source'][$id])){
                	$show = $r['_source'][$id];

                	if($id=='ID'){
                		$show = '<a href="'.$this->source.$r['_source']['ID'].'" target="_blank"><u>'.$r['_source']['ID'].'</u></a>';
                     }

                    if($r['_source'][$id]=='' ||$r['_source'][$id]==' '){
		     				$show = 'n/a';
		               } 
		            if(is_array($show)){
		            	$show = implode('<br>',$show);
		            }     
             }  
             array_push($show_line,$show);   
     }
     array_push($show_array,$show_line);
  }
   return $show_array;
  }
	
}

class GEMMA_data extends repository{
	public $show_name = 'GEMMA';
	public $id = '0005';
	//public $source = "http://www.chibi.ubc.ca/Gemma/expressionExperiment/showExpressionExperiment.html?id=369";
	public $source = "http://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=";//GSE60304
	public $search_fields = ['organism','title','geo_accession'];
	public $facets_fields = ['organism'];
	public $index = 'geo';
	public $type = 'gemma';
	public $headers = ['Title','Accession','Organism','Assays'];
  	public $header_ids = ['title','geo_accession','organism','assays'];
    public $datasource_headers = ['title','geo_accession','organism','assays','geo_accession'];
   
	public function show_table($results){
		$show_array = [];
       	$ids = $this->header_ids;
       	for($i=0;$i<count($results);$i++){
       		 $show_line = [];
             $r = $results[$i];
             
             foreach($ids as $id){
             	
                if(isset($r['highlight'][$id])){
                    $r['_source'][$id] = implode(' ',$r['highlight'][$id]);
                 }  
                if(isset($r['_source'][$id])){
                	$show = $r['_source'][$id];

                	if($id=='title'){
                    if(strncasecmp($r['_source']['geo_accession'], "GSE",3) == 0){

                		  $show = '<a href="'.$this->source.$r['_source']['geo_accession'].'" target="_blank"><u>'.$r['_source']['title'].'</u></a>';
                     }
                    else{
                      $show = '<u>'.$r['_source']['title'].'</u>';
                      
                    }
                  }
                    if($r['_source'][$id]=='' ||$r['_source'][$id]==' '){
		     				$show = 'n/a';
		               } 
		            if(is_array($show)){
		            	$show = implode('<br>',$show);
		            }     
             }  
             array_push($show_line,$show);   
     }
     array_push($show_array,$show_line);
  }
   return $show_array;
  }
	
}


class ArrayExpress_data extends repository{
	public $show_name = 'Array Express';
	public $id = '0006';
	public $source = "";
	public $search_fields = ['title','accession'];
	public $facets_fields = ['organism','Type'];
	public $index = 'geo';
	public $type = 'array_express';
	public $headers= ['Title','Accession','Organism','Type','Assays','Released'];
  public $header_ids = ['title','accession','organism','Type','assays','released'];
  public $datasource_headers = ['title','accession','organism','Type','link'];
  public function show_table($results){
		$show_array = [];
       	$ids = $this->header_ids;
       	for($i=0;$i<count($results);$i++){
       		 $show_line = [];
             $r = $results[$i];
             
             foreach($ids as $id){
             	
                if(isset($r['highlight'][$id])){
                    $r['_source'][$id] = implode(' ',$r['highlight'][$id]);
                 }  
                if(isset($r['_source'][$id])){
                	$show = $r['_source'][$id];

                	if($id=='title'){
                		$show = '<a href="'.$this->source.$r['_source']['link'].'" target="_blank"><u>'.$r['_source']['title'].'</u></a>';
                     }

                    if($r['_source'][$id]=='' ||$r['_source'][$id]==' '){
		     				$show = 'n/a';
		               } 
		            if(is_array($show)){
		            	$show = implode('<br>',$show);
		            }     
             }  
             array_push($show_line,$show);   
     }
     array_push($show_array,$show_line);
  }
   return $show_array;
  }
}

$all_repositories=[];

$dbgap_data = new dbgap_data();
array_push($all_repositories,$dbgap_data);
$PDB_data = new PDB_data();
array_push($all_repositories,$PDB_data);
$GEO_data = new GEO_data();
array_push($all_repositories,$GEO_data);
$LINCS_data =new LINCS_data();
array_push($all_repositories,$LINCS_data);
$GEMMA_data = new GEMMA_data();
array_push($all_repositories,$GEMMA_data);
$ArrayExpress_data = new ArrayExpress_data();
array_push($all_repositories,$ArrayExpress_data);

$all_search_fields=[];
foreach($all_repositories as $repository){
     $all_search_fields=array_merge($all_search_fields,$repository->search_fields);
   }
//echo print_r($all_search_fields);
//$all_search_fields = array_unique($all_search_fields);
$all_search_fields = array_keys(array_flip($all_search_fields));
//echo print_R($all_repositories);
//echo '<br>';
//echo print_r($all_search_fields);









?>