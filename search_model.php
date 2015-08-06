<?php
//Define class for Elasticsearch 
//require_once 'app/init.php';
require_once 'vendor/autoload.php';
//require_once 'search.php';

$es = new Elasticsearch\Client([
			//'hosts'=>['127.0.0.1:9200']
			'hosts'=>['129.106.149.72:9200']
			]);

abstract class BasicElasticSearch{
    
    //abstract protected $facets;
    public $search_fields;
    public $filter_fields;
    public $facets_fields;
    public $aggs_fields;
    public $query;
    public $offset;
    public $No_page;
    public $es_index;
    public $es_type;
    public $highlight;

    abstract protected function generate_query();
    abstract protected function generate_filter();
    abstract protected function generate_highlight();
    abstract protected function generate_facets();
    abstract protected function generate_aggs();
    abstract protected function generate_search_body();
   // abstract protected function decode_filter_fields();
}

class ElasticSearch extends BasicElasticSearch{
	public $offset = 1;
	public $No_page = 10;
    public $highlight=['fields'=>['*'=>[ "pre_tags"=>["<b>"],
                                    "post_tags"=> ["</b>"] ] ] ];

    public function generate_facets(){
       $facets = [];
       for($i=0;$i<count($this->facets_fields);$i++){
    	$facets[$this->facets_fields[$i]]=[
    		'terms'=>[
    		    'field'=>$this->facets_fields[$i],
    		    'size'=>10]
    		    ];
    	}
       return $facets;
    }
    public function generate_highlight(){
    	return $this->highlight;
    }
    public function generate_filter(){
    	// $this->filter_fields=['year'=>['2004','2005']];
		$j = 0;
		$terms = [];
        foreach(array_keys($this->filter_fields) as $key){  
			$filter_field = $key;
		    $filter_value = $this->filter_fields[$key];
		   	$terms[$j]=['terms'=>[$filter_field=>[$filter_value]]];
		    $j += 1;
		}

		/*foreach(array_keys($this->filter_fields) as $key){
		  $keys = decode_facets_term($key);//need reconsider
		  $filter_field = $keys[0];
		  $filter_value = $keys[1];

		  $index = check_multiple_value_filters($terms,$filter_field);
		  if($index>=0){
		   	   $pre_value=$terms[$index]['terms'][$filter_field];
		   	   array_push($pre_value,$filter_value);
		   	   $terms[$index]['terms'][$filter_field]=$pre_value;
		   }
		  else{
		   	   $terms[$j]=['terms'=>[$filter_field=>[$filter_value]]];
		   	    $j += 1;
		   }
		}*/
		$filter=['and'=>$terms];
		return $filter;
    }

    public function generate_aggs(){
    	return [];
    }

    public function generate_query(){
    	//echo print_r($this->search_fields);
    	if(count(array_keys($this->filter_fields))<1){
    		$query_part = [
				 'bool'=>[
				'should'=>[
					'multi_match'=>[
						'query'=>$this->query,
						'fields'=>$this->search_fields,
						'operator'=>'and',
						'type'=>'most_fields']
			             ]
			         ]
			       ];

			  }
	     else{
	     	$filter = $this->generate_filter();
	     	$query_part = [
				'filtered'=>[
					'query'=>[
					'multi_match'=>[
						'query'=>$this->query,
						'fields'=>$this->search_fields,
						'operator'=>'and'
						//'type'=>'most_fields'
				      ]
			         ],
					'filter'=>$filter
				   ]
				
			];
	     }
	    return $query_part;
    }

    public function generate_search_body(){
       $facets = $this->generate_facets();
       $search_query = $this->generate_query();
       if(count($facets)>0){
       	$body = ['from'=>($this->offset-1)*$this->No_page,
			    'size'=>$this->No_page,
			    'query'=>$search_query,
			    'highlight'=>$this->highlight,
			    'facets'=>$facets
			    ];
			}
		else{
	     $body = ['from'=>($this->offset-1)*$this->No_page,
			    'size'=>$this->No_page,
			    'query'=>$search_query,
			    'highlight'=>$this->highlight
			    
			    ];}
	   return $body;
    }

    public function get_search_result(){
    	global $es;
    	$body = $this->generate_search_body();
    	//echo '<pre>';
    	//echo print_r($body);
    	//echo '</pre>';
    	if(sizeof($this->es_type)>0){
	    	$result=$es->search([
				'index'=>$this->es_index,
				'type'=>$this->es_type,
				'body'=>$body
			]);
	    }
	    else{
	    	//echo 'here';
	    	$result=$es->search([
				'index'=>$this->es_index,
				'body'=>$body
			]);
	    }
    return $result;

    }
}

/*
$search_dbgap = new ElasticSearch();
$search_dbgap->search_fields = ['title','category','disease','MESHterm'];
$search_dbgap->facets_fields = ['ConsentType','IRB'];
$search_dbgap->query = 'cancer';
$search_dbgap->es_index = 'phenodisco';
$search_dbgap->filter_fields = ['IRB' => 'on'
                                ];
//$body = $search_dbgap->generate_search_body();
$result = $search_dbgap->get_search_result();
echo '<pre>';
echo print_r($result);
echo '<pre>';
*/
/*$search_pdb = new ElasticSearch();
$search_pdb->search_fields = ['dataItem.ID','dataItem.title','dataItem.keywords','dataItem.description'];
$search_pdb->facets_fields = ["citation.year","citation.journal",'dataItem.keywords'];
$search_pdb->query = 'cancer';
$search_pdb->es_index = 'pdb2';
//$search_pdb->filter_fields = [ 'citation_year:2014' => 'on' ,
//                               'citation_year:2009' => 'on' ];
$search_pdb->filter_fields = [ 'citation.year'=>['2014','2009']];
$body = $search_pdb->generate_search_body();
echo '<pre>';
//echo print_r($body);
echo '<pre>';
$result = $search_pdb->get_search_result();
echo '<pre>';
//echo print_r($result);
echo '<pre>';
*/
/*$search_geo = new ElasticSearch();
$search_geo->search_fields = ['title','summary','Type','Series','Organism','ID'];
$search_geo->facets_fields = ["Organism","platform","entry_type"];
$search_geo->query = 'gpl570';
$search_geo->es_index = 'geo';
//$search_geo->filter_fields = ['platform:gpl80' => 'on',
 //                             'platform:gpl570' => 'on'];

$search_geo->filter_fields = ['platform' => ['gpl80','gpl570']];                         
$body = $search_geo->generate_search_body();
echo '<pre>';
//echo print_r($body);
echo '<pre>';
$result = $search_geo->get_search_result();
echo '<pre>';
//echo print_r($result);
echo '<pre>';*/

/*$search_pdb = new ElasticSearch();
$search_pdb->search_fields = ['title','description','source_name','Type','series','ID'];
$search_pdb->facets_fields = ["organism","platform",'entry_type'];
$search_pdb->query = 'cancer';
$search_pdb->es_index = 'geo';
//$search_pdb->filter_fields = [ 'entry_type' => 'series'];
//                               'citation_year:2009' => 'on' ];
//$search_pdb->filter_fields = [ 'citation.year'=>['2014','2009']];
$body = $search_pdb->generate_search_body();
echo '<pre>';
echo print_r($body);
echo '<pre>';
$result = $search_pdb->get_search_result();
echo '<pre>';
echo print_r($result);
echo '<pre>';
*/
/*$search_pdb = new ElasticSearch();
//$search_pdb->search_fields = ['dataItem.ID','dataItem.title','dataItem.keywords','dataItem.description','title','category','disease','MESHterm','description','source_name','Type','series','ID','accession',
//'organism','title','geo_accession'];//['_all'];
$search_pdb->search_fields = ['title','category',
                                            'disease',
                                            'MESHterm',
                                            'dataItem.ID',
                                            'dataItem.title',
                                            'dataItem.keywords',
                                            'dataItem.description',
                                            'citation.title',
                                           'description',
                                             'source_name',
                                            'Type',
                                            'series',
                                            'ID',
                                            'protocol_reference',
                                            'organism',
                                            'geo_accession',
                                            'accession'];
  $search_pdb->search_fields = [             'dataItem.ID',
                                            'dataItem.title',
                                            'dataItem.keywords',
                                            'dataItem.description',
                                            'citation.title'
                                            /*'ID',
                                            'protocol_reference',
                                            'accession',
                                            'geo_accession',
                                            'series',
                                            'Type',
                                            'source_name',
                                            'description',
                                            'MESHterm',
                                            'disease',
                                            'category',
                                            'title',
                                            'citation.title'*/

                                         //  ];
//$search_pdb->facets_fields = ["organism","platform",'entry_type'];
/*$search_pdb->query = 'cancer';
$search_pdb->es_index = 'pdb2';
$search_pdb->filter_fields = [];
//$search_pdb->filter_fields = [ 'citation.year'=>['2014','2009']];
$body = $search_pdb->generate_search_body();
//echo '<pre>';
//echo 'why';
//echo print_r($body);
echo '<pre>';
$result = $search_pdb->get_search_result();
echo '<pre>';
echo $result['hits']['total'];
//echo print_r($result);
echo '<pre>';*/

?>