<?php

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$creds = require_once 'creds.php';
$mail = new Zend_Mail_Storage_Imap($creds);

if ($_GET['folder']) {
	$folderParts = explode('/', $_GET['folder']);
	$folder = $mail->getFolders();
	foreach($folderParts as $part) {
		$folder = $folder->$part;
	}

	$mail->selectFolder($folder);
	foreach($mail as $messageNum => $message) {
		echo $message->from .'<br />----'. $message->subject.'<br/><br/>';
	}
}

$folders = new RecursiveIteratorIterator($mail->getFolders(),
                                         RecursiveIteratorIterator::SELF_FIRST);

foreach ($folders as $localName => $folder) {
    	echo str_repeat('&mdash;', $folders->getDepth());
	echo '<a href="/index.php?folder='.$folder.'">'.$localName.'</a><br />';
}
