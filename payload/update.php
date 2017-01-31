<?php

//$x = exec("sh pull.sh", $i, $o);
$x = exec("git pull origin master", $i, $o);
var_dump($x);
echo '<pre>';
var_dump($i);
echo '</pre>';
var_dump($o);

if(is_array($i)) {
	
	if($i[0] == "Already up-to-date.") {
		//do nothing
		
	}
}

?>