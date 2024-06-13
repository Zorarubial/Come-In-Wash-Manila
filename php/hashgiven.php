<?php
$input = "Hello, World!";
$md5Hash = md5($input);
echo $md5Hash;
?>

<?php
$input = "Hello, World!";
$sha256Hash = hash('sha256',$input);
echo "SHA-256 HASH: $sha256Hash";
?>

<?php
 $password = "secret";
 $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
 echo "Hashed Password (bcrypt): $hashedPassword";
?>