<?php
	require_once 'pdb_similar_dataset.php';
	$pdbSimilar=new PDBSimilarData;
	echo "new class";
	$data = $pdbSimilar -> get_pdbId("2wfx");

?>