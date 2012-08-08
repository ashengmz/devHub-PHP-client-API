<?php

/*

	Author  : S S Rajapaksha <ssrajapaksha@outlook.com>
	Licence : Apache License, Version 2.0

	Demonstrates using of SMSReceiver and SMSSender classes
*/

require_once 'lib/Log.php';
require_once 'lib/SMSReceiver.php';
require_once 'lib/SMSSender.php';

define('SERVER_URL', 'http://localhost:7007/service');
define('APP_ID', 'appid');
define('APP_PASSWORD', 'pass');
$logger = new Logger();

try{
	// Creating a receiver
	$receiver = new SMSReceiver(file_get_contents('php://input')); // Passes the json request using file_get_contents('php://input')
	
	//Creating a sender
	$sender = new SMSSender( SERVER_URL, APP_ID, APP_PASSWORD);
	
	// Sending message to a single user
	$response = $sender->sendMessage("Hi friend. The message received is : ".$receiver->getMessage(), $receiver->getAddress());
	if($response)
		$logger->WriteLog("Message successfully sent to ".$receiver->getAddress());
	else
		$logger->WriteLog("Message sending failed.");
	
	// Brodcasting message
	$response = $sender->broadcastMessage("Hi all. This is a broadcasting message.");
	if($response)
		$logger->WriteLog("Broadcasting message successfully sent.");
	else
		$logger->WriteLog("Broadcasting failed.");

}catch(SMSServiceException $e){
	$logger->WriteLog($e->getErrorCode().' '.$e->getErrorMessage());
}

?>