<?php

Class FTPClient {
	var $connectionId;
	var $loginOk = false;
	var $messageArray = array();
	
	// log
	function writeLog($handle, $message) {
		//$this->messageArray[] = $message;
		fwrite($handle, $message); 
	}

	## --------------------------------------------------------
	
	function logMessage($message, $clear=true) {
		if ($clear) {$this->messageArray = array();}
		$this->messageArray[] = $message;
	}
	
	## --------------------------------------------------------
	
	function getMessages() {
		return $this->messageArray;
	}
	
	## --------------------------------------------------------
	
	function connect ($server, $ftpUser, $ftpPassword, $isPassive = false) {
		// Set up basic connection
		$this->connectionId = ftp_connect($server);
		
		// Login with username and password
		$loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);

		// *** Sets passive mode on/off (default off)
		ftp_pasv($this->connectionId, $isPassive);

		// *** Check connection
		if ((!$this->connectionId) || (!$loginResult)) {
			$this->logMessage('FTP connection has failed!');
			$this->logMessage('Attempted to connect to ' . $server . ' for user ' . $ftpUser, true);
			return false;
		} else {
			$this->logMessage('Connected to ' . $server . ', for user ' . $ftpUser);
			$this->loginOk = true;
			return true;
		}
	}
			
	function changeDir($directory) {
		if (ftp_chdir($this->connectionId, $directory)) {
			$this->logMessage('Current directory is now: ' . ftp_pwd($this->connectionId));
			echo ftp_pwd($this->connectionId);
			return true;
		} else { 
			$this->logMessage('Couldn\'t change directory');
			echo "\nMYDIR: no change \n";
			return false;
		}
	}

	function getCurrentDir() {
		return ftp_pwd($this->connectionId);
	}

	## --------------------------------------------------------
	
	function getDirListing($directory = '.', $parameters = '-a') {
		// get contents of the current directory
		//$contentsArray = ftp_nlist($this->connectionId, $parameters . '  ' . $directory);
		$contentsArray = ftp_nlist($this->connectionId, $directory);

		return $contentsArray;
	}

	## --------------------------------------------------------

	function downloadFile ($fileFrom, $fileTo) {
		$mode = FTP_BINARY;

		// open some file to write to
		// $handle = fopen($fileTo, 'w');

		// try to download $remote_file and save it to $handle
		if (ftp_get($this->connectionId, $fileTo, $fileFrom, $mode, 0)) {
			return true;
			$this->logMessage('File "' . $fileTo . '" successfully downloaded.');
		} else {
			return false;
			$this->logMessage('There was an error downloading file "' . $fileFrom . '" to "' . $fileTo . '"');
		}
	}

	## --------------------------------------------------------
	
	 function __deconstruct() {
		if ($this->connectionId) {
			ftp_close($this->connectionId);
		}
	}
				
}
?>
