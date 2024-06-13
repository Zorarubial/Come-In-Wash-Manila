<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'custconxpdo.php';

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE User_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($row) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle updating other user fields
        if (isset($_POST['field']) && isset($_POST['value'])) {
            $field = $_POST['field'];
            $value = $_POST['value'];

            $validFields = ['Uemail', 'Umobile', 'Ucity', 'Uprovince'];
            
            if (in_array($field, $validFields)) {
                try {
                    $stmt = $pdo->prepare("UPDATE tbl_user SET $field = ?, last_updated = CURRENT_TIMESTAMP WHERE User_id = ?");
                    $stmt->execute([$value, $userId]);

                    $activity = 'profile update';
                    $activity_time = date("Y-m-d H:i:s");
                    $ip_address = $_SERVER['REMOTE_ADDR'];

                    $sql = "INSERT INTO user_activity_audit (user_id, activity, activity_time, ip_address) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$userId, $activity, $activity_time, $ip_address]);

                    header("Location: user_profile.php"); // Reload the page to reflect changes
                    exit();
                } catch (PDOException $e) {
                    echo "Error updating $field: " . $e->getMessage();
                }
            }
        }

        // Handle profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $pictureTmpName = $_FILES['profile_picture']['tmp_name'];
            $pictureName = $_FILES['profile_picture']['name'];

            // for moving uploaded picture to designated folder
            $uploadDirectory = 'profile_pictures/';
            $newPicturePath = $uploadDirectory . $pictureName;
            if (move_uploaded_file($pictureTmpName, $newPicturePath)) {
                try {
                    // Update the database with the path to the picture
                    $stmt = $pdo->prepare("UPDATE tbl_user SET Upicture = ?, last_updated = CURRENT_TIMESTAMP WHERE User_id = ?");
                    $stmt->execute([$newPicturePath, $userId]);

                    // Log the profile picture change in the audit trail
                    $activity = 'upload pfp';
                    $activity_time = date("Y-m-d H:i:s");
                    $ip_address = $_SERVER['REMOTE_ADDR'];

                    $sql = "INSERT INTO user_activity_audit (user_id, activity, activity_time, ip_address) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$userId, $activity, $activity_time, $ip_address]);

                    // Reload the page to reflect changes
                    header("Location: user_profile.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Error updating profile picture: " . $e->getMessage();
                }
            } else {
                echo "Error uploading picture.";
            }
        }

        // Handle password update
        if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($password === $confirmPassword) {
                try {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE tbl_user SET Upassword = ?, last_updated = CURRENT_TIMESTAMP WHERE User_id = ?");
                    $stmt->execute([$hashedPassword, $userId]);

                    $activity = 'password update';
                    $activity_time = date("Y-m-d H:i:s");
                    $ip_address = $_SERVER['REMOTE_ADDR'];

                    $sql = "INSERT INTO user_activity_audit (user_id, activity, activity_time, ip_address) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$userId, $activity, $activity_time, $ip_address]);

                    // Reload the page to reflect changes
                    header("Location: user_profile.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Error updating password: " . $e->getMessage();
                }
            } else {
                echo "Passwords do not match.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>My Profile</title>
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>
        body {
            background-image: url('assets/img/backdrop.jpg');
        }

        #update-form {
            margin: 20px 0;
        }

        #update-form input[type="text"], #update-form input[type="password"] {
            padding: 5px;
            margin-right: 10px;
        }

        #update-form button {
            padding: 5px 10px;
            background-color: yellow;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #update-form button:hover {
            background-color: #ffd700;
        }
    </style>
</head>
<body>
    <h1>Hi, <?php echo $row['Ufname'] . ' ' . $row['Usname']; ?></h1>
    <table>
        <tr>
            <th>Field</th>
            <th>User Data</th>
            <th>Update</th>
        </tr>
        <tr>
            <td>Profile Picture:</td>
            <td> <?php if (!empty($row['Upicture'])): ?>
                <img src="<?php echo $row['Upicture']; ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;" >
                <?php endif; ?>
                <form id="update-picture-form" method="post" enctype="multipart/form-data" onsubmit="refreshPage()">
                    <input type="file" name="profile_picture">
                    <button type="submit">Upload</button>
                </form>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>User ID:</td>
            <td><?php echo $row['User_id']; ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?php echo $row['Uemail']; ?></td>
            <td>
                <form id="update-form" method="post">
                    <input type="hidden" name="field" value="Uemail">
                    <input type="text" name="value" placeholder="New email">
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <tr>
            <td>Mobile:</td>
            <td><?php echo $row['Umobile']; ?></td>
            <td>
                <form id="update-form" method="post">
                    <input type="hidden" name="field" value="Umobile">
                    <input type="text" name="value" placeholder="New mobile">
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <tr>
            <td>City:</td>
            <td><?php echo $row['Ucity']; ?></td>
            <td>
                <form id="update-form" method="post">
                    <input type="hidden" name="field" value="Ucity">
                    <input type="text" name="value" placeholder="New city">
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <tr>
            <td>Province:</td>
            <td><?php echo $row['Uprovince']; ?></td>
            <td>
                <form id="update-form" method="post">
                    <input type="hidden" name="field" value="Uprovince">
                    <input type="text" name="value" placeholder="New province">
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <tr>
            <td>Account creation:</td>
            <td><?php echo $row['Ucreation']; ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Last updated:</td>
            <td><?php echo $row['last_updated']; ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td>*******</td>
            <td>
                <form id="update-form" method="post">
                    <input type="hidden" name="field" value="Upassword">
                    <input type="password" name="password" placeholder="New password">
                    <input type="password" name="confirm_password" placeholder="Confirm new password">
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
    </table>

    <a href="home.php">Back to Home</a>
</body>
<script>
    function refreshPage() {
        setTimeout(function() {
            location.reload();
        }, 1000); // Adjust the delay (in milliseconds) as needed
    }
</script>
</html>
<?php
} else {
    echo "User not found.";
}
?>
