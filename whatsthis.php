<?php

header('Content-type', 'application/json');

echo file_get_contents('http://localhost:9000/scigraph/vocabulary/autocomplete/' . urlencode($_GET['q']));


