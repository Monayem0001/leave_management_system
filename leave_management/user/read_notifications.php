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

$user_id = $_SESSION['user_id'];
$sql = "UPDATE notifications SET is_read=1 WHERE user_id='$user_id'";
$conn->query($sql);

header("Location: dashboard.php");
exit();
?>
</body>
</html>