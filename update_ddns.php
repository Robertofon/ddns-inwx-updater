<?php

/**
 * DDNS-INWX-Updater
 *
 * Copyright (c) Patrick Mosch
 *
 * @link http://xuad.net
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
// Set utf-8 header
//header('Content-type: text/plain; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="de_DE">
<head>
<meta charset="utf-8">
<title>Feste-IP.NET- Fritzbox</title>
</head>
<body>
<div >
<?php
//echo "Hallo";

// Load classes
require_once 'DDNSManager.php';

// Some variables declaration
$key = "";
$ip = "";
$ip6 = "";

// Read accesskey
if(isset($_GET['key']))
{
	$key = $_GET['key'];
}

// Read ip
if(isset($_GET['ip']))
{
	$ip = $_GET['ip'];
}

// Read ip6
if(isset($_GET['ip6']))
{
	$ip6 = $_GET['ip6'];
}

//echo "key da, ip da: ".$ip.", ip6=".$ip6."; key=".$key;

// Initialize ddnsmanager
$ddns = new DDNSManager($key);

// Update ip
$ddns->updateIP($ip, $ip6);
//echo "ende";
?>
</div>
</body>
</html>