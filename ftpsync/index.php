<?php

		set_time_limit(0);
		
		// Define host, username, password
		define('FTP_HOST', '202.86.17.228');
		define('FTP_USER', 'indial');
		define('FTP_PASS', 'FTPindial');
		$debug=true;
		
		// Include the FTP class
		include('ftp_class.php');

		// Create the FTP object
		$ftpObj = new FTPClient();
		
		// Connect
		if ($ftpObj -> connect(FTP_HOST, FTP_USER, FTP_PASS)) {
						
			$File = "logfile_".date('Ymd').".txt"; 
			$handle = fopen($File, 'a');

			//fwrite($Handle, $Data); 
			$starttime =	"\n===============start time of script ".date("l F d, Y, h:i A")."===================";
			
			$ftpObj->writeLog($handle,$starttime);
			
			$today = date('Ymd',strtotime("270 minutes")); // date format for folder  YYYYMMDD i.e. 20110617
			$yesterday = date('Ymd'); // previous date YYYYMMDD i.e. 20110617
			
			//$h = date('H');
			$h = date('H',strtotime("270 minutes"));
			
			if(($h=="00")||($h=="01")||($h=="02")||($h=="03")||($h=="04")||($h=="05")||($h=="06")||($h=="07")){
				$hourwisedir	=	"24";
				$dir			=	$yesterday."/".$hourwisedir;
			}
			if(($h=="08")||($h=="09")||($h=="10")||($h=="11")||($h=="12")||($h=="13")||($h=="14")||($h=="15")){
				$hourwisedir	=	"16";
				$dir			=	$today."/".$hourwisedir;
			}
			if(($h=="16")||($h=="17")||($h=="18")||($h=="19")||($h=="20")||($h=="21")||($h=="22")||($h=="23")){
				$hourwisedir	=	"08";
				$dir			=	$today."/".$hourwisedir;
			}
			
			$dir	= "one97/".$dir;		
			
			## --------------------------------------------------------
				
			// Change directory
			$ftpObj->changeDir($dir);

			//$ftpObj->setCurrentPath($dir);
			if($debug==true) {
				print("\n<BR>After changeDir-----");
				print_r($ftpObj -> getMessages());
			}
			
			// Get folder contents
			$contentsArray = $ftpObj->getDirListing();

			if($debug==true) {
				print("\n<BR>After Dir Listing-----");
				print_r($ftpObj -> getMessages());
			}
			
			// Output our array of folder contents
			echo "<h4>AVAILABLE FILES</h4>";
			echo '<pre>';
			print_r($contentsArray);
			echo '</pre>';
			echo '<p><em>There are '.	sizeof($contentsArray) . ' available files.</em></p>';
			
			## --------------------------------------------------------
			
			$j	=	0;
			$k	=	0;
			
			for($j=0;$j<sizeof($contentsArray);$j++){
				$pos = strpos($contentsArray[$j],".wav");
				if($pos === false) {
				 // string needle NOT found in haystack
				}
				else {
					$k	=	$k+1;
					$filenames.=	"\n".$contentsArray[$j];
				}
				//$ftpObj->writeLog($handle,"\n".$contentsArray[$j] );
			}
			$totalfiles	=	"\nTotal number of files at ftp location = ".$k;
			if ($k==0)
			{
				$endtime =	"\n===============End time of script ".date("l F d, Y, h:i A")."===================";
				$ftpObj->writeLog($handle,$endtime );
				exit();
			}	
			$ftpObj->writeLog($handle,$totalfiles );
			$ftpObj->writeLog($handle,"\nFile names are  " );
			$ftpObj->writeLog($handle,$filenames );
			
			## --------------------------------------------------------
			
			echo "In directory: ".$cd =	$ftpObj->getCurrentDir();

			$fileFrom = '';		# The location on the one97 server
			$fileTo = '/home/rrbaker/webapps/watertracker/media/uploads';	# Local dir to save to
			
			$i	=	0;
			for($i=0;$i<sizeof($contentsArray);$i++){
				echo "<h4>File: ".$contentsArray[$i]."</h4>";
				$fileFrom	=	$cd."/".$contentsArray[$i];
				echo "<pre>Downloading from: ".$fileFrom ."</pre>";
				$sfilename = "\n one97 file path: ".$fileFrom;
				$ftpObj->writeLog($handle,$sfilename);
				$fileTo	=	$fileTo."/".$contentsArray[$i];
				echo "<pre>Uploading to: ".$fileTo."</pre>";
				$dfilename	=	"\n WebFaction local path: ".$fileTo;
				$ftpObj->writeLog($handle,$dfilename);
				$ftpObj->downloadFile($fileFrom, $fileTo);
				// reset $fileTo
				$fileTo = '/home/rrbaker/webapps/watertracker/media/uploads';
				echo "<hr>";
			}
			// *** Download file
			
			$endtime =	"\n===============End time of script ".date("l F d, Y, h:i A")."===================";
			$ftpObj->writeLog($handle,$endtime);
			
		} 
?>
