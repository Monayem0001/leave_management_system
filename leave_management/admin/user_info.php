<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
session_start();
include("../config/db_connect.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all users
$sql = "SELECT user_id, name, email FROM users WHERE role='user'";
$result = $conn->query($sql);
?>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>User Info</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header><h1>User Info</h1></header>
<div class="container">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while($row=$result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="leave_details.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-primary">Leave Details</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
