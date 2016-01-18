<?php
	require_once 'pubmed_publication.php';

	$xml=file_get_contents(urlencode ("http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=%20A%20Genome-Wide%20Association%20Study%20of%20Lung%20Cancer%20Risk"));
	echo $xml;
?>