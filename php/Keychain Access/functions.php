<?php
// 
//  functions.php
//  Keychain Access with Alfred
//  
//  Created by Samet Gültekin on 2012-01-06.
//  Copyright 2012 Samet Gültekin. All rights reserved.
// 


function openAppleInputDialog($question, $defaultAnswer){
	$command = "osascript -e 'tell app \"System Events\" to display dialog \"$question\""; 
	$command .= "default answer \"$defaultAnswer\"'";
	$command .= " -e 'set theAnswer to (text returned of result)' -e 'theAnswer'";
	
	exec($command, &$output);
	
	if($output == NULL) exit();
	return escapeshellarg($output[0]);
}

function addRecord($name, $account, $where, $password, $comment, $keychain){
	
	$command = "security 2>&1 >/dev/null -v add-generic-password -a $account -s $where -l $name -p $password -j $comment ./$keychain";
	exec($command, &$output);
	
	if($output[1] != NULL){
		echo "New Password not added.";
	}
	else{
		echo "New Password added.";
	}
	
	return $output[0]; 
}

function findRecord($name, $keychain){
	
	$name = escapeshellarg($name);
	
	$commandPass = "security 2>&1 >/dev/null find-generic-password -gl $name ./$keychain";
		
	exec($commandPass, &$outputPass);
	
	$regExPattern = "/password: \"(.*)\"/";
	$string = $outputPass[0];
	
	if (preg_match($regExPattern, $string, $matches)){
		
		$commandInfo = "security find-generic-password -gl $name ./$keychain";
		exec($commandInfo, &$outputInfo);
		reformatOutput($outputInfo);
		
		copyClipboard($matches[1]);
	}
	else{
		echo "Not found";
	}
	
}

function reformatOutput($output){

	//3 name
	//5	account
	//11 comment
	//17 where

	$namePattern = "/0x00000007 <blob>=\"(.*)\"/";
	$accountPattern = "/\"acct\"<blob>=\"(.*)\"/";
	$wherePattern = "/\"svce\"<blob>=\"(.*)\"/";
	$commentPattern = "/\"icmt\"<blob>=\"(.*)\"/";
	
	preg_match($namePattern, $output[3], $nameMatches);
	preg_match($accountPattern, $output[5], $accountMatches);
	preg_match($wherePattern, $output[17], $whereMatches);
	preg_match($commentPattern, $output[11], $commentMatches);
	
	$name = $nameMatches[1];
	$account = $accountMatches[1];
	$where = $whereMatches[1];
	$comment = $commentMatches[1];
	
	echo "\nPassword for $name\n\n";
	echo "Account: $account\n";
	echo "Where: $where\n";
	echo "Comment: $comment\n";
		
}

function copyClipboard($string){
	
	$string = escapeshellarg($string);
	
	$command = "echo $string | pbcopy";
	exec($command, &$output);
	
	echo "\nPassword is copied to clipboard.";
	
}


function addNewKeychain($name){
	$name = escapeshellarg($name);
	
	$command = "security 2>&1 >/dev/null create-keychain -P  $name.keychain";
	exec($command, &$output);
	
	if(preg_match("(already exist)", $output[0], $nameMatches)){
		echo "A keychain with the same name already exist.";
		exit();
	}
	else if (preg_match("(cancel)", $output[0], $nameMatches)){
		echo "The operation was canceled by the user.";
		exit();
	}
	else if($output == NULL){
		echo "New keychain added: $name";
		exit();
	}
			
}

function lockKeychain($name){
	$name = escapeshellarg($name);
	
	if($name){
		$command = "security 2>&1 >/dev/null lock-keychain $name.keychain";
		exec($command, &$output);
	
		if(preg_match("(could not be found)", $output[0], $nameMatches)){
			echo "The specified keychain could not be found.";
			print_r($output);
			exit();
			}
			else if($output == NULL){
				echo "Keychain locked: $name";
				exit();
			}
	}
	
	else{
		$command = "security 2>&1 >/dev/null lock-keychain -a";
		exec($command, &$output);
		echo "All keychains are locked.";
		exit();
		
	}
}

function openKeychainAccess(){
	$command = "open /Applications/Utilities/Keychain\ Access.app/";
	exec($command, $output);
}


?>