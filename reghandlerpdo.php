<?php
session_start();
require "custconxpdo.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $firstname = $_POST["first_name"];
    $surname = $_POST["surname"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT); //hash algo bcrypt
    $city = $_POST["city"];
    $province = $_POST["province"];
    $type = 1;

    // SQL statement to select user by email
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE Uemail = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // CHANGE INTO ALERT NA LANG
        echo "Email already exists. Please use a different email address.";
        //header("Location: regpage.html");
        exit();
    } else {
        //  SQL statement to insert user data into tbl_user table
        $stmt = $pdo->prepare("INSERT INTO tbl_user (Ufname, Usname, Umobile, Uemail, Upass, Ucity, Uprovince, Utype)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // Execute the statement
        $stmt->execute([$firstname, $surname, $mobile, $email, $hashedpassword, $city, $province, $type]);

            //get newly inserted ID
         $userId = $pdo->lastInsertId();

        // Insert audit trail record for the registration
        $activity_time = date("Y-m-d H:i:s");
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $readstat = "unread";
        $acttype = "flag";

        $auditStmt = $pdo->prepare("INSERT INTO 
            user_activity_audit (user_id, activity, activity_time, ip_address, readstat, acttype) VALUES (?, ?, ?, ?, ?, ?)");
        $auditStmt->execute([$userId, 'User registered', $activity_time, $ip_address, $readstat, $acttype]);

        
        header("Location: createsuccess.html");
        exit();
    }

} else {
    header("Location: regpage.html");
    exit();
}

// Close the PDO connection by unsetting the PDO object
unset($pdo);
?>
