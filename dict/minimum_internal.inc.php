<?php
$attributes = array (
		     2 => 
		     array (
			    'name' => 'User-Password',
			    'type' => 'string',
			    ),
		     4 => 
		     array (
			    'name' => 'NAS-IP-Address',
			    'type' => 'ipaddr',
			    ),
		     5 => 
		     array (
			    'name' => 'NAS-Port',
			    'type' => 'integer',
			    ),
		     6 => 
		     array (
			    'name' => 'Service-Type',
			    'type' => 'integer',
			    'values' => 
			    array (
				   12 => 'Voice',
				   13 => 'Fax',
				   14 => 'Modem-Relay',
				   15 => 'IAPP-Register',
				   16 => 'IAPP-AP-Check',
				   ),
			    ),
		     8 => 
		     array (
			    'name' => 'Framed-IP-Address',
			    'type' => 'ipaddr',
			    ),
		     9 => 
		     array (
			    'name' => 'Framed-IP-Netmask',
			    'type' => 'ipaddr',
			    ),
		     11 => 
		     array (
			    'name' => 'Filter-Id',
			    'type' => 'string',
			    ),
		     14 => 
		     array (
			    'name' => 'Login-IP-Host',
			    'type' => 'ipaddr',
			    ),
		     16 => 
		     array (
			    'name' => 'Login-TCP-Port',
			    'type' => 'integer',
			    'values' => 
			    array (
				   23 => 'Telnet',
				   513 => 'Rlogin',
				   514 => 'Rsh',
				   ),
			    ),
		     17 => 
		     array (
			    'name' => 'Old-Password',
			    'type' => 'string',
			    ),
		     18 => 
		     array (
			    'name' => 'Reply-Message',
			    'type' => 'string',
			    ),
		     19 => 
		     array (
			    'name' => 'Callback-Number',
			    'type' => 'string',
			    ),
		     20 => 
		     array (
			    'name' => 'Callback-Id',
			    'type' => 'string',
			    ),
		     24 => 
		     array (
			    'name' => 'State',
			    'type' => 'octets',
			    ),
		     30 => 
		     array (
			    'name' => 'Called-Station-Id',
			    'type' => 'string',
			    ),
		     31 => 
		     array (
			    'name' => 'Calling-Station-Id',
			    'type' => 'string',
			    ),
		     1 => 
		     array (
			    'name' => 'User-Name',
			    'type' => 'string',
			    ),
		     3 => 
		     array (
			    'name' => 'CHAP-Password',
			    'type' => 'octets',
			    ),
		     7 => 
		     array (
			    'name' => 'Framed-Protocol',
			    'type' => 'integer',
			    'values' => 
			    array (
				   7 => 'GPRS-PDP-Context',
				   9 => 'PPTP',
				   ),
			    ),
		     10 => 
		     array (
			    'name' => 'Framed-Routing',
			    'type' => 'integer',
			    'values' => 
			    array (
				   0 => 'None',
				   1 => 'Broadcast',
				   2 => 'Listen',
				   3 => 'Broadcast-Listen',
				   ),
			    ),
		     12 => 
		     array (
			    'name' => 'Framed-MTU',
			    'type' => 'integer',
			    ),
		     13 => 
		     array (
			    'name' => 'Framed-Compression',
			    'type' => 'integer',
			    'values' => 
			    array (
				   0 => 'None',
				   1 => 'Van-Jacobson-TCP-IP',
				   2 => 'IPX-Header-Compression',
				   3 => 'Stac-LZS',
				   ),
			    ),
		     15 => 
		     array (
			    'name' => 'Login-Service',
			    'type' => 'integer',
			    'values' => 
			    array (
				   0 => 'Telnet',
				   1 => 'Rlogin',
				   2 => 'TCP-Clear',
				   3 => 'PortMaster',
				   4 => 'LAT',
				   5 => 'X25-PAD',
				   6 => 'X25-T3POS',
				   8 => 'TCP-Clear-Quiet',
				   ),
			    ),
		     22 => 
		     array (
			    'name' => 'Framed-Route',
			    'type' => 'string',
			    ),
		     23 => 
		     array (
			    'name' => 'Framed-IPX-Network',
			    'type' => 'ipaddr',
			    ),
		     25 => 
		     array (
			    'name' => 'Class',
			    'type' => 'octets',
			    ),
		     26 => 
		     array (
			    'name' => 'Vendor-Specific',
			    'type' => 'octets',
			    ),
		     27 => 
		     array (
			    'name' => 'Session-Timeout',
			    'type' => 'integer',
			    ),
		     28 => 
		     array (
			    'name' => 'Idle-Timeout',
			    'type' => 'integer',
			    ),
		     29 => 
		     array (
			    'name' => 'Termination-Action',
			    'type' => 'integer',
			    'values' => 
			    array (
				   0 => 'Default',
				   1 => 'RADIUS-Request',
				   ),
			    ),
		     32 => 
		     array (
			    'name' => 'NAS-Identifier',
			    'type' => 'string',
			    ),
		     33 => 
		     array (
			    'name' => 'Proxy-State',
			    'type' => 'octets',
			    ),
		     34 => 
		     array (
			    'name' => 'Login-LAT-Service',
			    'type' => 'string',
			    ),
		     35 => 
		     array (
			    'name' => 'Login-LAT-Node',
			    'type' => 'string',
			    ),
		     36 => 
		     array (
			    'name' => 'Login-LAT-Group',
			    'type' => 'octets',
			    ),
		     37 => 
		     array (
			    'name' => 'Framed-AppleTalk-Link',
			    'type' => 'integer',
			    ),
		     38 => 
		     array (
			    'name' => 'Framed-AppleTalk-Network',
			    'type' => 'integer',
			    ),
		     39 => 
		     array (
			    'name' => 'Framed-AppleTalk-Zone',
			    'type' => 'string',
			    ),
		     60 => 
		     array (
			    'name' => 'CHAP-Challenge',
			    'type' => 'octets',
			    ),
		     61 => 
		     array (
			    'name' => 'NAS-Port-Type',
			    'type' => 'integer',
			    'values' => 
			    array (
				   22 => 'Wireless-CDMA2000',
				   23 => 'Wireless-UMTS',
				   24 => 'Wireless-1X-EV',
				   25 => 'IAPP',
				   26 => 'FTTP',
				   27 => 'Wireless-802.16',
				   28 => 'Wireless-802.20',
				   29 => 'Wireless-802.22',
				   35 => 'xPON',
				   36 => 'Wireless-XGP',
				   ),
			    ),
		     62 => 
		     array (
			    'name' => 'Port-Limit',
			    'type' => 'integer',
			    ),
		     63 => 
		     array (
			    'name' => 'Login-LAT-Port',
			    'type' => 'string',
			    ),
		     40 => 
		     array (
			    'name' => 'Acct-Status-Type',
			    'type' => 'integer',
			    'values' => 
			    array (
				   9 => 'Tunnel-Start',
				   10 => 'Tunnel-Stop',
				   11 => 'Tunnel-Reject',
				   12 => 'Tunnel-Link-Start',
				   13 => 'Tunnel-Link-Stop',
				   14 => 'Tunnel-Link-Reject',
				   ),
			    ),
		     41 => 
		     array (
			    'name' => 'Acct-Delay-Time',
			    'type' => 'integer',
			    ),
		     42 => 
		     array (
			    'name' => 'Acct-Input-Octets',
			    'type' => 'integer',
			    ),
		     43 => 
		     array (
			    'name' => 'Acct-Output-Octets',
			    'type' => 'integer',
			    ),
		     44 => 
		     array (
			    'name' => 'Acct-Session-Id',
			    'type' => 'string',
			    ),
		     45 => 
		     array (
			    'name' => 'Acct-Authentic',
			    'type' => 'integer',
			    'values' => 
			    array (
				   1 => 'RADIUS',
				   2 => 'Local',
				   3 => 'Remote',
				   4 => 'Diameter',
				   ),
			    ),
		     46 => 
		     array (
			    'name' => 'Acct-Session-Time',
			    'type' => 'integer',
			    ),
		     47 => 
		     array (
			    'name' => 'Acct-Input-Packets',
			    'type' => 'integer',
			    ),
		     48 => 
		     array (
			    'name' => 'Acct-Output-Packets',
			    'type' => 'integer',
			    ),
		     49 => 
		     array (
			    'name' => 'Acct-Terminate-Cause',
			    'type' => 'integer',
			    'values' => 
			    array (
				   19 => 'Supplicant-Restart',
				   20 => 'Reauthentication-Failure',
				   21 => 'Port-Reinit',
				   22 => 'Port-Disabled',
				   ),
			    ),
		     50 => 
		     array (
			    'name' => 'Acct-Multi-Session-Id',
			    'type' => 'string',
			    ),
		     51 => 
		     array (
			    'name' => 'Acct-Link-Count',
			    'type' => 'integer',
			    ),
		     68 => 
		     array (
			    'name' => 'Acct-Tunnel-Connection',
			    'type' => 'string',
			    ),
		     86 => 
		     array (
			    'name' => 'Acct-Tunnel-Packets-Lost',
			    'type' => 'integer',
			    ),
		     64 => 
		     array (
			    'name' => 'Tunnel-Type',
			    'type' => 'integer',
			    'values' => 
			    array (
				   13 => 'VLAN',
				   ),
			    ),
		     65 => 
		     array (
			    'name' => 'Tunnel-Medium-Type',
			    'type' => 'integer',
			    'values' => 
			    array (
				   1 => 'IPv4',
				   2 => 'IPv6',
				   3 => 'NSAP',
				   4 => 'HDLC',
				   5 => 'BBN-1822',
				   6 => 'IEEE-802',
				   7 => 'E.163',
				   8 => 'E.164',
				   9 => 'F.69',
				   10 => 'X.121',
				   11 => 'IPX',
				   12 => 'Appletalk',
				   13 => 'DecNet-IV',
				   14 => 'Banyan-Vines',
				   15 => 'E.164-NSAP',
				   ),
			    ),
		     66 => 
		     array (
			    'name' => 'Tunnel-Client-Endpoint',
			    'type' => 'string',
			    ),
		     67 => 
		     array (
			    'name' => 'Tunnel-Server-Endpoint',
			    'type' => 'string',
			    ),
		     69 => 
		     array (
			    'name' => 'Tunnel-Password',
			    'type' => 'string',
			    ),
		     81 => 
		     array (
			    'name' => 'Tunnel-Private-Group-Id',
			    'type' => 'string',
			    ),
		     82 => 
		     array (
			    'name' => 'Tunnel-Assignment-Id',
			    'type' => 'string',
			    ),
		     83 => 
		     array (
			    'name' => 'Tunnel-Preference',
			    'type' => 'integer',
			    ),
		     90 => 
		     array (
			    'name' => 'Tunnel-Client-Auth-Id',
			    'type' => 'string',
			    ),
		     91 => 
		     array (
			    'name' => 'Tunnel-Server-Auth-Id',
			    'type' => 'string',
			    ),
		     52 => 
		     array (
			    'name' => 'Acct-Input-Gigawords',
			    'type' => 'integer',
			    ),
		     53 => 
		     array (
			    'name' => 'Acct-Output-Gigawords',
			    'type' => 'integer',
			    ),
		     55 => 
		     array (
			    'name' => 'Event-Timestamp',
			    'type' => 'date',
			    ),
		     70 => 
		     array (
			    'name' => 'ARAP-Password',
			    'type' => 'octets',
			    ),
		     71 => 
		     array (
			    'name' => 'ARAP-Features',
			    'type' => 'octets',
			    ),
		     72 => 
		     array (
			    'name' => 'ARAP-Zone-Access',
			    'type' => 'integer',
			    'values' => 
			    array (
				   1 => 'Default-Zone',
				   2 => 'Zone-Filter-Inclusive',
				   4 => 'Zone-Filter-Exclusive',
				   ),
			    ),
		     73 => 
		     array (
			    'name' => 'ARAP-Security',
			    'type' => 'integer',
			    ),
		     74 => 
		     array (
			    'name' => 'ARAP-Security-Data',
			    'type' => 'string',
			    ),
		     75 => 
		     array (
			    'name' => 'Password-Retry',
			    'type' => 'integer',
			    ),
		     76 => 
		     array (
			    'name' => 'Prompt',
			    'type' => 'integer',
			    'values' => 
			    array (
				   0 => 'No-Echo',
				   1 => 'Echo',
				   ),
			    ),
		     1047 => 
		     array (
			    'name' => 'Packet-Type',
			    'type' => 'integer',
			    'values' => 
			    array (
				   1 => 'Access-Request',
				   2 => 'Access-Accept',
				   3 => 'Access-Reject',
				   4 => 'Accounting-Request',
				   5 => 'Accounting-Response',
				   6 => 'Accounting-Status',
				   7 => 'Password-Request',
				   8 => 'Password-Accept',
				   9 => 'Password-Reject',
				   10 => 'Accounting-Message',
				   11 => 'Access-Challenge',
				   12 => 'Status-Server',
				   13 => 'Status-Client',
				   21 => 'Resource-Free-Request',
				   22 => 'Resource-Free-Response',
				   23 => 'Resource-Query-Request',
				   24 => 'Resource-Query-Response',
				   25 => 'Alternate-Resource-Reclaim-Request',
				   26 => 'NAS-Reboot-Request',
				   27 => 'NAS-Reboot-Response',
				   29 => 'Next-Passcode',
				   30 => 'New-Pin',
				   31 => 'Terminate-Session',
				   32 => 'Password-Expired',
				   33 => 'Event-Request',
				   34 => 'Event-Response',
				   40 => 'Disconnect-Request',
				   41 => 'Disconnect-ACK',
				   42 => 'Disconnect-NAK',
				   43 => 'CoA-Request',
				   44 => 'CoA-ACK',
				   45 => 'CoA-NAK',
				   50 => 'IP-Address-Allocate',
				   51 => 'IP-Address-Release',
				   ),
			    ),
		     1080 => 
		     array (
			    'name' => 'Response-Packet-Type',
			    'type' => 'integer',
			    'values' => 
			    array (
				   1 => 'Access-Request',
				   2 => 'Access-Accept',
				   3 => 'Access-Reject',
				   4 => 'Accounting-Request',
				   5 => 'Accounting-Response',
				   6 => 'Accounting-Status',
				   7 => 'Password-Request',
				   8 => 'Password-Accept',
				   9 => 'Password-Reject',
				   10 => 'Accounting-Message',
				   11 => 'Access-Challenge',
				   12 => 'Status-Server',
				   13 => 'Status-Client',
				   40 => 'Disconnect-Request',
				   41 => 'Disconnect-ACK',
				   42 => 'Disconnect-NAK',
				   43 => 'CoA-Request',
				   44 => 'CoA-ACK',
				   45 => 'CoA-NAK',
				   256 => 'Do-Not-Respond',
				   ),
			    ),
		     );
?>