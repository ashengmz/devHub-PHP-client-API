Ideamart PHP Wrapper for sending and receiving SMS
=====================================================

-src/ folder contains the library files to be used in a client application written in php.

-sample/ folder contains a basic MO listener in php which receives messages, replies back to the same user and broadcast a message.

Creating a Listener
-------------------

Import SMSReceiver.php and create the receiver object as follows.

	$receiver = new SMSReceiver(file_get_contents('php://input'));
	
	Received message : $receiver->getMessage()
	Sender Address: $receiver->getAddress()

Creating a Sender
-----------------

Import SMSSender.php and create the sender object as follows.

	$sender = new SMSSender( SERVER_URL, APP_ID,  APP_PASSWORD);

(where SERVER_URL is the listening url of SDP, APP_ID is the application id, APP _PASSWORD is the password for your application. Both APP_ID and APP_PASSWORD are given when your application is provisioned.)

**To send a SMS to a user**

	$sender->sendMessage( MESSAGE, ADDRESS)

**To send a SMS to number of users**

	$sender->sendMessage( MESSAGE, array(ADDRESS1, ADDRESS2, ...))

**To broadcast a SMS**

	$sender->broadcastMessage( MESSAGE)

- Refer the sample listener for more.
- or just tweet @Sriyan31
