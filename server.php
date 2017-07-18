<?php

set_time_limit(0);

$address = '127.0.0.1';
$port = 10005;
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_bind($sock, $address, $port);

socket_listen($sock);

while (true) 
 {
	$msgsock = socket_accept($sock);
	
	if (!$msgsock)
		break;

	$read=""; 

	$read=socket_read($msgsock, 1024);
	

	socket_write($msgsock, $read);
	
	echo "ok";

	socket_close($msgsock);

}



socket_close($sock);



?>