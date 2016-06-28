<?php

$shell_instruction = 'cd ..; /srv/http2-demo/downloadSite ';
$shell_instruction .= escapeshellarg($_GET['url']);

echo "Command : <pre>".$shell_instruction."</pre>";

echo "Starting transfer...</br>Output :";

while (@ ob_end_flush()); // end all output buffers if any
$proc = popen($shell_instruction, 'r');
echo '<pre>';
while (!feof($proc))
{
    echo fread($proc, 4096);
    @ flush();
}
echo '</pre>';
$status = pclose($proc);
if ($status == 0) {
    echo "Download complete";
} else {
    echo "Download failed : " + $status;
}
?>
