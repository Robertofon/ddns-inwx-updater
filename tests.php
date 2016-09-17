<?php

/**
Bei Problemen bitte die Update-Url nur mit "http" anstatt "https" eintragen, also ohne SSL.

IPv6
Wenn der Anschluss IPv6 unterstützt, ist es möglich die IPv6 und IPv4 Adresse zugleich zu aktualisieren.
Die Update-Url dafür lautet:

http://deinhoster.de/tests.php?ip=<ipaddr>&ip6=<ip6addr>

*/



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

echo "Die übergebene IP ist $ip und die IPv6 ist $ip6".$ip;
echo "</br>Die übergebene IP ist ".$_GET["ip"]." und die IPv6 ist ".$_GET["ip6"];

?>

</div>
</body>
