<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php
include("../config/db_connect.php");
session_start();

if (isset($_GET['id'])) {
    $leave_id = $_GET['id'];

    // Update leave status
    $sql = "UPDATE leave_applications SET status='approved' WHERE leave_id='$leave_id'";
    if ($conn->query($sql) === TRUE) {

        // Find which user applied for this leave
        $userQuery = "SELECT user_id FROM leave_applications WHERE leave_id='$leave_id'";
        $userResult = $conn->query($userQuery);
        $row = $userResult->fetch_assoc();
        $user_id = $row['user_id'];

        // Insert notification
        $msg = "✅ Your leave application #$leave_id has been approved.";
        $notify = "INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$msg')";
        $conn->query($notify);

        header("Location: dashboard.php");
        exit();
    }
}
?>

</body>
</html>