<?php
 
$password = "1234567890123456789012345678901234";
$md5 = md5($password);
$hash = hash('sha256',$password);
$bcrypt = password_hash($password, PASSWORD_DEFAULT);
 
echo "MD5 Hash: $md5<br>";
echo "Data size: " . strlen($md5) . " bytes<br>";
echo "Data type: " . gettype($md5) . "<br>";
 
echo "bcrypt: $bcrypt<br>";
echo "Data size: " . strlen($bcrypt) . " bytes<br>";
echo "Data type: " . gettype($bcrypt) . "<br>";
 
 
echo "Hash: $hash<br>";
echo "Data size: " . strlen($hash) . " bytes<br>";
echo "Data type: " . gettype($hash) . "<br>";
 
?>