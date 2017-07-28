<?php
$schemaorg_array=$service->getDisplayItemData()["dataset"];
echo "<script type=\"application/ld+json\">
		{
		\"@context\": \"http://schema.org\",
		\"@type\": \"Dataset\",
		\"name\": \"".$service->getDisplayItemData()["title"][1]."\"";


/*
 foreach($schemaorg_array as $value){
 $value[1]=str_ireplace("<br>",",",$value[1]);
 if($value[1]!="" && array_key_exists($value[0],$n)){
 echo ",\n\"$value[0]\" : \"$value[1]\"";
 }

 }
 */

foreach($schemaorg_array as $value){
	$value[1]=str_ireplace("<br>",",",$value[1]);
	$value[1]=str_ireplace('"',"'",$value[1]);
	if($value[1]!="" && array_key_exists($value[0],$mapping)){
		$v=$mapping[$value[0]];
		echo ",\n		\"$v\" : \"$value[1]\"";  
	}
	
	else if($value[1]!="" && in_array($value[0],$dataset_array)){
		echo ",\n		\"$value[0]\" : \"$value[1]\"";
	}

}
echo "\n		}
		</script>\n\n";
?>