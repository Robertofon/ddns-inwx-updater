ddns-inwx-updater
=================

Dynamic update of a inwx subdomain. Code is under LGPL license.
Updates IPv4 and IPv6 addresses at once.

Quick setup
-----
1. Copy updater to your webspace. 
2. Rename ".config.default.ini" to ".config.ini".
3. Edit ".config.ini" and insert your account data.
4. Get inwx's domrobot class for php and put it in the inwx dir.
5. Run update_ddns.php at your webspace.
   pass GET-parameters ip and optional ip6
   You need support to pass your ipv6 address to the url. If missed out.
   only Ipv4 will be set and nothing else happens
   this is an example for a fritz.box :
   http://mydomain.de/scripts/update_ddns.php?ip=<ipaddr>&ip6=<ip6addr>&key=a_key_that_is_inconfig.ini
   Fritzbox will replace <ipaddr> and <ip6addr>.. other vendors may vary.

About
-----

Create your own DDNS server. For more informations visit my instruction-page: http://xuad.net/artikel/ddns-mit-tomatousb-oder-dd-wrt-und-einer-subdomain-von-inwx.html
