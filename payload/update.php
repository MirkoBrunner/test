<?php

$x = exec("cd ../ git pull origin master", $i, $o);
file_put_contents('test.log', $x.$i.$o);

?>