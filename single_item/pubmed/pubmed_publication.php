<?php
	/*
	 * Get related publication from pubmed using their API -- E-utilities
	 * author: Ruiling
	 * date: 9/24/2015
	 */
	class Pubmed{
		protected $pmid;
		protected $title;
		protected $source;
		protected $pubDate;

		public function set_pmid($newPmid){
			$this->pmid=$newPmid;
		}
		public function get_pmid(){
			return $this->pmid;
		}

		public function set_title($newTitle){
			$this->title=$newTitle;
		}
		public function get_title(){
			return $this->title;
		}
		public function set_source($newSource){
			$this->source=$newSource;
		}
		public function get_source(){
			return $this->source;
		}
		public function set_pubDate($newPubDate){
			$this ->pubDate=$newPubDate;
		}
		public function get_pubDate(){
			return $this->pubDate;
		}
	}

	class PubmedPublication{

		/*
		*	Get a list of pmid 
		*/
		function get_pmid($query){

			$href="http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".urlencode($query);
			$resultXML=file_get_contents($href);


			// Parse returned xml
			$xmlArray=simplexml_load_string($resultXML) 
						or die("Error: SimpleXML cannot create object");

			// Copy IdList to an array
			$numId= count($xmlArray -> IdList ->Id);
			$idArray=$xmlArray -> IdList ->Id;

			return $idArray;
		}

		/*
		*	Get publication details using pmid
		*/
		function get_publication($idArray, $count){

			$href="http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id=";
			$pubmedArray=array();	
			$i=0;

			if(count($idArray)>0){
				foreach ($idArray as $id) {
				if($i<$count){


				$hrefFull=$href.$id;
				$resultPage = file_get_contents($hrefFull);

				// Parse returned page
				$xmlArray=simplexml_load_string($resultPage)
							or die("Error: SimpleXML cannot create object");
				$pubmed=new Pubmed();

				$pubmed -> set_pmid($id);
				$pubmed -> set_title ($xmlArray -> DocSum -> Item[5]);
				$pubmed -> set_source($xmlArray -> DocSum -> Item[2]);
				$pubmed -> set_pubDate($xmlArray -> DocSum -> Item[0]);

				array_push($pubmedArray, $pubmed);
			
			}
			$i++;
				
			}
			}

			

			return $pubmedArray;
		}

		function show_publication($query){
			// Get the pmid
			$idArray = $this -> get_pmid($query);
			
			// Get publication details using pmid
			$itemArray=$this ->get_publication($idArray,5);
			
			// Display details in a table
			require_once 'array_to_table.php';
		}
	}


?>