<?php
/*
    MPHTTPUtilities.php
    iPhoneBackend
  
    Created by cb on 29.09.10.
    Copyright (c) 2010 __MyCompanyName__. All rights reserved.
*/

//define('MPP_DEFAULT_PROXY', "proxy:8080");
define('MPP_DEFAULT_PROXY', "");
function MPCurlRequestURLUsingPost($url, $data, &$errorString, $customOpts=null)
{
	if(!$customOpts)
		$customOpts = array();

	$customOpts[CURLOPT_POST] = true;
	$customOpts[CURLOPT_POSTFIELDS] = $data;

	return MPCurlRequestURL($url, $errorString, $customOpts);
}

function MPCurlRequestURLUsingGet($url, &$errorString, $customOpts=null)
{
	return MPCurlRequestURL($url, $errorString, $customOpts);
}

function MPCurlRequestURL($url, &$errorString, $customOpts=null)
{
	$ch = curl_init();
	
	if($customOpts)
	{
		curl_setopt_array($ch, $customOpts);
	}

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	if (constant('MPP_DEFAULT_PROXY'))
	{
		curl_setopt($ch, CURLOPT_PROXY, MPP_DEFAULT_PROXY);
	}

	$result = curl_exec($ch);

	if (($error = curl_error($ch)) != '')
	{
		curl_close($ch);
		$errorString = "Curl error: ".$error." for url: ".$url;
		return null;
	}

	curl_close($ch);
	return $result;
}

function MPCurlRequestURL2File($url, &$errorString, $customOpts=null, $fileName)
{
	$ch = curl_init();

	if($customOpts)
	{
		curl_setopt_array($ch, $customOpts);
	}

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FILE, fopen( $fileName, 'w' ));
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	if (constant('MPP_DEFAULT_PROXY'))
	{
		curl_setopt($ch, CURLOPT_PROXY, MPP_DEFAULT_PROXY);
	}

	$result = curl_exec($ch);

	if (($error = curl_error($ch)) != '')
	{
		curl_close($ch);
		$errorString = "Curl error: ".$error." for url: ".$url;
		return null;
	}

	curl_close($ch);
	return $result;
}

function MPFWRequestURLUsingSystemCurl($url, $customOpts=null, $runInBackground=false)
{
	$systemStr = "";
	$backgroundOpts = "";
	if($customOpts)
	{
		$systemStr = " ";
		foreach($customOpts as $opt => $value)
		{
			$systemStr.= $opt." ".$value;
		}
	}
	
	if($runInBackground)
	{
		$backgroundOpts = " > /dev/null 2>&1 &";
	}
	
	$proxyStr = '';
	if (constant('MPP_DEFAULT_PROXY'))
	{
		$proxyStr = ' -x '.MPP_DEFAULT_PROXY;
	}
	return exec(escapeshellcmd(MPP_CURLPATH." -L -k -s".$proxyStr.$systemStr." '".$url."'").$backgroundOpts);
}

function MPHTTPStatusCodeRequest($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_NOBODY, true); // this is what sets it as HEAD request
	if (constant('MPP_DEFAULT_PROXY') && !preg_match("/\.mpros\.de/", $url)) // dirty hack
	{
		curl_setopt($ch, CURLOPT_PROXY, MPP_DEFAULT_PROXY);
	}
	curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return $httpCode;
}

?>