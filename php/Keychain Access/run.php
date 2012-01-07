<?php

// 
//  run.php
//  Keychain Acess With Alfred
//  
//  Created by Samet Gültekin on 2012-01-06.
//  Copyright 2012 Samet Gültekin. All rights reserved.
// 

include './functions.php';
/*SETTINGS*/

$settings =loadSettings();
$keychain = $settings->keychain;

/*SETTINGS*/

$query = explode(" ",$argv[1]);
$queryLenght  = count($query);


if($query[0] == "add"){
	$name = openAppleInputDialog("Enter a name","gmail");
	$account = openAppleInputDialog("Enter account name","johndoe@gmail.com");
	$where = openAppleInputDialog("Enter domain","http://www.gmail.com");
	$password = openAppleInputDialog("Enter password","Ad9iA0¡$#");
	$comment = openAppleInputDialog("Enter comment","comment");
	
	addRecord($name, $account, $where, $password, $comment, $keychain);
	exit();
}


else if($query [0] == "get"){
	if($query[1] == "help" || $query[1] == NULL){
		echo "Usage:\n\n \"kyc get {name}\": \n Gets  keychain named {name}.\n\n";
		exit();
	}
	
	$res = findRecord($query[1], $keychain);
	exit();
}

else if($query[0] == "add-keychain"){
	if($query[1] == NULL || $query[1] == "help"){
		echo "Usage: kyc add-keychain {name}";
		exit();
	}
	$res = addNewKeychain($query[1]);
	
}
else if($query[0] == "lock-keychain"){
	if($query[1] == "help"){
		echo "Usage:\n\n \"kyc lock-keychain {name}\": \n Locks given keychain.\n\n";
		echo "\"kyc lock-keychain\": \n Locks all keychains.";
		exit();
	}
	
	$res = lockKeychain($query[1]);
}
else if($query[0] == "change-keychain"){
	if($query[1] == "help" || $query[1] == NULL){
		echo "Usage:\n\n \"kyc change-keychain {name}\": \n Changes keychain which is used by extension.\n\n";
		exit();
	}
	$res = saveSettings($query[1]);
}
else if($query[0] == ""){
	openKeychainAccess();
}

?>