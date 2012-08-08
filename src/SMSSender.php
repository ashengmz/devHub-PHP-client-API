<?php

/*

	Author  : S S Rajapaksha <ssrajapaksha@outlook.com>
	Licence : Apache License, Version 2.0
	
	SMSSender class for send SMS

*/

require_once 'SMSServiceException.php';
class SMSSender{
	private $applicationId,
			$password,
			$serverURL;
	
	public function __construct($serverURL, $applicationId, $password){
		if(!(isset($serverURL,
				   $applicationId,
				   $password)))
			throw new SMSServiceException('Request Invalid.', 'E1312');
		else {
			$this->applicationId = $applicationId;
			$this->password = $password;
			$this->serverURL = $serverURL;
		}
	}
	
	public function broadcastMessage($message){
		return $this->sendMessage($message, array('tel:all'));
	}
	
	public function sendMessage($message, $addresses){
		if(empty($addresses))
			throw new SMSServiceException('Format of the address is invalid.', 'E1325');
		else {
			$jsonStream = (is_string($addresses))?$this->resolveJsonStream($message, array($addresses)):(is_array($addresses)?$this->resolveJsonStream($message, $addresses):null);
			return ($jsonStream!=null)?$this->sendRequest($jsonStream):false;
		}
	}
	
	private function sendRequest($jsonStream){
		$opts = array('http'=>array('method'=>'POST',
									'header'=>'Content-type: application/json',
									'content'=>$jsonStream));
		$context = stream_context_create($opts);
		$response = file_get_contents($this->serverURL, 0, $context);
		
		return $this->handleResponse(json_decode($response));
	}
	
	private function handleResponse($jsonResponse){
		$statusCode = $jsonResponse->statusCode;
		$statusDetail = $jsonResponse->statusDetail;
		
		if(empty($jsonResponse))
			throw new SMSServiceException('Invalid server URL', '500');
		else if(strcmp($statusCode, 'S1000')==0)
			return true;
		else
			throw new SMSServiceException($statusDetail, $statusCode);
	}
	
	private function resolveJsonStream($message, $addresses){
		// $addresses is a array
		
		$messageDetails = array('message'=>$message,
								'destinationAddresses'=>$addresses);
		
		$applicationDetails = array('applicationId'=>$this->applicationId,
									'password'=>$this->password);
		
		$jsonStream = json_encode($applicationDetails+$messageDetails);
		
		return $jsonStream;
	}	
}
?>