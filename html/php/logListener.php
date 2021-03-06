#!/usr/bin/php

<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function storeLogs($message, $fileName){
	$file = fopen("./logs/$fileName".".log", "a" );
        fwrite($file, $message);
	fclose($file); 
}

function requestProcessor($request){
  	echo "Received Request:\n\n";
	var_dump($request);

	if(!isset($request['type'])){
    		return "ERROR: unsupported message type";
	}

	switch ($request['type']){
		case "logErrors":
      			$msg = storeLogs($request['message'], "logError");
			#return storeLogs($request['message'], "logError");
			break;
		case "log-login":
			$msg = storeLogs($request['message'], "logLogin");
			break;
		case "log-register":
			$msg = storeLogs($request['message'], "logRegister");
			break;
		case "log-SQL":
			$msg = storeLogs($request['message'], "logSQL");
			break;
		return array("returnCode" => '0', 'message'=>"Server received request and processed");
	}
}

$server = new rabbitMQServer('testRabbitMQ.ini', 'logServer');
$server->process_requests('requestProcessor');
   
?>
