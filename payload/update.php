<?php
try {
    // Decode the payload json string
	$payload = json_decode($_REQUEST['payload']);

} catch(Exception $e) {
	
        exit(0);
}
// Pushed to master
if ($payload->ref === 'refs/heads/master') {

	$where = $payload->commits[0]->committer->name;
	$commit_msg  = $payload->commits[0]->message;
	$mail = $payload->commits[0]->committer->email;
	
    $a = exec("git pull origin master 2>&1", $r, $rr);
    
    file_put_contents('github.txt', print_r($where."_".$commit_msg, TRUE), FILE_APPEND);
	file_put_contents('github.txt', print_r($r, TRUE), FILE_APPEND);
	file_put_contents('github.txt', print_r($rr, TRUE), FILE_APPEND);
	
	mail($mail, 'GIT TEST 1', print_r($a, TRUE));
	mail($mail, 'GIT TEST 2', print_r($r, TRUE));
	mail($mail, 'GIT TEST 3', print_r($rr, TRUE));
	
	if(is_array($r) && $rr == 0) {
		
		if($r[0] == "Already up-to-date.") {
			//no thing to do		
			
		} else {
			
			$m = $commit_msg."\n\n";
			
			foreach($r AS $c) {
				$m.= $c."\n";
			}
			mail($mail, 'GIT BOT', $m);
			
			//we have a description?
			$dm = explode("\n\n", $commit_msg);
			if(count($dm) > 1) {
				$text = $dm[1]."\n\nFrom: ".$where;
				
				//nun Slack was senden
				$slackWebhookUrl = 'https://hooks.slack.com/services/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';
				
				$opt = [
					'http' => [
						'method' => 'POST',
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'content' => 'payload='.json_encode([
							'username' => 'git_deploy',
							'icon_emoji' => ':arrow_down:',
							'text' => $text
							])
						]
					];
				$context = stream_context_create($opt);
				$response = file_get_contents($slackWebhookUrl, false, $context);
			}
		}
		
	} else if(is_array($r) && $rr > 0) {
		
		//error in die DB Pumpen!
		$error = "git ".$a."\n\n";
		
		foreach($r AS $c) {
			$m.= $c."\n";
		}
		
		$error.= $m."\n\n".$where;
		mail($mail, "GIT BOT", $error);
		
	} else {
		//error in die DB Pumpen!
		$error = "git ".$a."\n\n";
		
		foreach($r AS $c) {
			$m.= $c."\n";
		}
		
		$error.= $m."\n\n".$where;
		mail($mail, "GIT BOT", $error);
	}
}

?>