<?php
	/*
	* Get similar dataset for pdb data using Dr. Trevor Cohen's PhendiscoVectors algorithm
	* author: Ruiling
	* date: 9/24/2015
	*/

	error_reporting(0);
	ini_set('display_errors', 0);

	class PDBDataset{
		protected $pdbid;
		protected $structureTitle;
		protected $experimentalTechnique;

		public function set_pdbid($newPdbid){
			$this->pdbid = $newPdbid;
		}

		public function get_pdbid(){
			return $this->pdbid;
		}

		public function set_structureTitle($newStructureTitle){
			$this ->structureTitle = $newStructureTitle;
		}

		public function get_structureTitle(){
			return $this ->structureTitle;
		}

		public function set_experimentalTechnique($newExperimentalTechnique){
			$this ->experimentalTechnique = $newExperimentalTechnique;
		}

		public function get_experimentalTechnique(){
			return $this ->experimentalTechnique;
		}
	}

	class PDBSimilarData{

		/*
		*  Get a list of PDB id of similar dataset
		*/
		function get_pdbId($pdbid){

			// Get json file
			$href="http://localhost:8085/".$pdbid;
	        $json = exec("curl  -H \"Connection: Keep-Alive\" -XGET ".$href);

	        // Parse json object
	        $data = json_decode($json, true);
	        $num = count($data["nodes"]);

	        // Push pdb id into an array
	        $pdbidArray=array();

	        if($num >0){
	        	for($i=0; $i < $num; $i++){
	          		array_push($pdbidArray, $data["nodes"][$i]["name"]);
	        	}
	        }

	        return $pdbidArray;
		}

		function get_dataset($idArray, $count){
			// Convert array to string
			$pdbidString = implode(",",$idArray);
			$href = "http://www.rcsb.org/pdb/rest/customReport.xml?pdbids=".$pdbidString."&customReportColumns=structureId,structureTitle,experimentalTechnique";
			$pdbArray = array();

			// Get dataset details
			$resultXML = file_get_contents($href);

			// Parse xml
			$dataset = simplexml_load_string($resultXML);
            $json = json_encode($dataset);
            $array = json_decode($json, TRUE);
            $i=0;
            

            if(array_key_exists("record",$array) && count($array["record"]) > 0){
            	foreach($array["record"] as $record){

            		if($i<$count && $i >0){

	            		// Add dataset into to PDBDataset object
	            		$pdbDataset = new PDBDataset();
	            		
	            		$pdbDataset -> set_pdbid($record["dimStructure.structureId"]);
	            		$pdbDataset -> set_structureTitle($record["dimStructure.structureTitle"]);
	            		$pdbDataset -> set_experimentalTechnique($record["dimStructure.experimentalTechnique"]);

	            		array_push($pdbArray, $pdbDataset);
            		}
            		$i++;
            	}
            }
            return $pdbArray;
		}

		function show_similar_dataset($pdbid){
			// Get the pdb id
			$idArray = $this -> get_pdbId(strtolower($pdbid));


			// Get dataset details using pdb id
			$itemArray = $this -> get_dataset($idArray,6);

			// Display details in a table
			require_once 'show_similar_tbl.php';

		}
	}
?>
