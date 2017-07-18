<?php
header("Content-type:text/html;charset=utf-8");

$service_port = 10005;
$address = '127.0.0.1';

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_connect($socket, $address, $service_port);

socket_write($socket, "小同志");

$out="";
 
$out = socket_read($socket, 1024);
echo $out;

socket_close($socket);

?>
