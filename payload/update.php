<?php
error_reporting(0);
try {
        // Decode the payload json string
		$payload = json_decode($_REQUEST['payload']);
		
} catch(Exception $e) {
	
        exit(0);
}
// Pushed to master
if ($payload->ref === 'refs/heads/master')
{
        // Log the payload object
        
        #file_put_contents('github.txt', print_r($payload, TRUE), FILE_APPEND);
		
		$where = $payload->commits[0]->committer->name;
		$commit_msg  = $payload->commits[0]->message;
		file_put_contents('github.txt', print_r($where."_".$commit_msg, TRUE), FILE_APPEND);
        // Run the build script as a background process
        exec("git pull origin master", $r, $rr);
		file_put_contents('github.txt', print_r($r, TRUE), FILE_APPEND);
		file_put_contents('github.txt', print_r($rr, TRUE), FILE_APPEND);
}

?>