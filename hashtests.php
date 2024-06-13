<?php
 
    $password = "120101spstaff";
    $md5 = md5($password);
    $hash = hash('sha256',$password);
    $bcrypt = password_hash($password, PASSWORD_DEFAULT);

    echo "bcrypt: $bcrypt<br>";
    echo "Data size: " . strlen($bcrypt) . " bytes<br>";
    echo "Data type: " . gettype($bcrypt) . "<br>";