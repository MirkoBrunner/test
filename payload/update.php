<?php

$x = exec("cd ../ git pull origin master", $i, $o);
file_put_contents('test.log', print_r($x, 1));

?>