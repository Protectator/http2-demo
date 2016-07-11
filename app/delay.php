<?php
    $amount = intval($_GET['delay']);
    $cmd = "sudo ../setDelay ".$amount;
    exec($cmd, $output, $returnVal);
    if ($returnVal != 0) {
        echo "ERROR : ".$returnVal;
    } else {
        echo $amount;
    }
?>