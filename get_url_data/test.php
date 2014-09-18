<?php

function getUrlList($host)
{
	$json = @file_get_contents($host);
	$data = json_decode($json, true);
	return $data['urls'];
}

function getUrlData($url)
{
	try {
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		$timeout = 20;	
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		$json = @curl_exec($ch);
		@curl_close($ch);
		$data = json_decode($json, true);
		return $data['status_code'];
	}
	catch (Exception $e) {
		echo $e->getMessage();
		return null;
	}
}

function printResult($response=null)
{
	switch($response) {
		case '200' : echo "[200 OK]"; break;
		case '400' : echo "[400 Not Found]"; break;
		case '500' : echo "[500 Server Error]"; break;
		default: echo "[$response Unknown Error]";
	}
}

function idle()
{
	echo '.';
}

function main()
{
	$host = 'http://coreinterview.sendgrid.net/sample?n=5';
	$urls = getUrlList($host);
	foreach($urls as $url) {
		idle();
		$response = getUrlData($url['url']);
		printResult($response);
	}
}

main();
