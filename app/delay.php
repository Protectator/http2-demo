<?php
    chdir("..");
    $amount = intval($_GET['delay']);
    $cmd = "sudo ./setDelay ".$amount;
    exec($cmd, $output, $returnVal);
    if ($returnVal != 0) {
        echo "ERROR : ".$returnVal;
        var_dump($output);
    } else {
        echo $amount;
    }
?>
