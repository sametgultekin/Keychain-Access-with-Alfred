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

$keychain = "login.keychain";

/*SETTINGS*/

$query = explode(" ",$argv[1]);
$queryLenght  = count($query);

/*--------------------------------*/
/* Adding New Password to Keychain*/
if($query[0] == "add"){
	$name = openAppleInputDialog("Enter a name","i.e: gmail");
	$account = openAppleInputDialog("Enter account name","i.e: johndoe@gmail.com");
	$where = openAppleInputDialog("Enter domain","http://www.gmail.com");
	$password = openAppleInputDialog("Enter password","http://www.gmail.com");
	$comment = openAppleInputDialog("Enter comment","bilibilib");
	
	addRecord($name, $account, $where, $password, $comment, $keychain);
	exit();
}
/* END of Adding New Password to Keychain*/
/*--------------------------------*/

else if($query [0] == "get"){
		
	$res = findRecord($query[1], $keychain);
	exit();
}

else if($query[0] == "add-keychain"){
	if($query[1] == NULL || $query[1] == "help"){
		echo "Usage: passwd add-keychain {name}";
		exit();
	}
	$res = addNewKeychain($query[1]);
	
}
else if($query[0] == "lock-keychain"){
	if($query[1] == "help"){
		echo "Usage:\n\n \"passwd lock-keychain {name}\": \n Locks given keychain.\n\n";
		echo "\"passwd lock-keychain\": \n Locks all keychains.";
		exit();
	}
	$res = lockKeychain($query[1]);
}
else if($query[0] == ""){
	openKeychainAccess();
}

?>