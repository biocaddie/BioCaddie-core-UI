<?php

header('Content-type', 'application/json');

require_once dirname(__FILE__) . '/../config/config.php';
global $scigraph;
echo file_get_contents($scigraph.'vocabulary/autocomplete/' . urlencode($_GET['q']));
?>
