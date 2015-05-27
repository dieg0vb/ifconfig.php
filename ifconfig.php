<?php


// Array de valores
$user = array(
	'ip' 		=> $_SERVER['REMOTE_ADDR'],
	'host' 		=> (isset($_SERVER['REMOTE_ADDR']) ? gethostbyaddr($_SERVER['REMOTE_ADDR']) : ""),
	'port' 		=> $_SERVER['REMOTE_PORT'],
	'ua' 		=> $_SERVER['HTTP_USER_AGENT'],
	'lang' 		=> $_SERVER['HTTP_ACCEPT_LANGUAGE'],
	'mime' 		=> $_SERVER['HTTP_ACCEPT'],
	'encoding' 	=> $_SERVER['HTTP_ACCEPT_ENCODING'],
	'charset' 	=> $_SERVER['HTTP_ACCEPT_CHARSET'],
	'connection' => $_SERVER['HTTP_CONNECTION'],
	'cache' 	=> $_SERVER['HTTP_CACHE_CONTROL'],
	'cookie' 	=> $_SERVER['HTTP_COOKIE'],
	'referer' 	=> $_SERVER['HTTP_REFERER'],
	'real_ip' 	=> $_SERVER['HTTP_X_REAL_IP'],
	'fwd_ip' 	=> $_SERVER['HTTP_X_FORWARDED_FOR'],
	'fwd_host' 	=> (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']) : ""),
	'dnt' 		=> $_SERVER['HTTP_DNT']
	);

// Comprobar request ( ifconfig.php?q=ip)
$query=trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($_GET['q'])))))); 


if (isset($query) && array_key_exists($query, $user)) {
	empty($user[$query]) ? die() : die($user[$query]."\n");
}

elseif (isset($query) && (($query=="text") || ($query=="all"))) {
	header('Content-Type: text/plain');
	foreach($user as $key => $value) {
		echo $key.": ".$value."\n";
	}
	die();

} elseif (isset($query) && ($query=="xml")) {
	header('Content-Type: text/xml');


function array_to_xml(array $arr, SimpleXMLElement $xml)
{
    foreach ($arr as $k => $v) {
        is_array($v)
            ? array_to_xml($v, $xml->addChild($k))
            : $xml->addChild($k, $v);
    }
    return $xml;
}
echo array_to_xml($user, new SimpleXMLElement('<info/>'))->asXML();

} elseif (isset($query) && ($query=="json")) {
	header('Content-Type: application/json');
	die(json_encode($user));

} else {
	header('Content-Type: text/html');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>info</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<style>
		body {
			background: #FDFDFD;
			color: #111;
			font-size: .9em;
		}
		p {
			font-family: "Source Code Pro", "Droid Mono", "Courier New", "Consolas", "Terminal", fixed;
			line-height: 1em;
		}
		.small, .small > a {
			font-size: 9pt;
			color: #777;
			text-align: center;
			text-decoration: none;
		}
	</style>
</head>
<body>
<?
	foreach($user as $key => $value) {
		echo '	<p id="'.$key.'">'.$key.': '.$value.'</p>'."\n";
	}
?>
<br/>
</body>
</html>
<?
}
die();
?>
