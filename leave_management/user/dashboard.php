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

if ($_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user leave applications
$sql = "SELECT la.leave_id, lt.leave_name, la.start_date, la.end_date, la.reason, la.status 
        FROM leave_applications la 
        JOIN leave_types lt ON la.leave_type_id=lt.leave_type_id 
        WHERE la.user_id=$user_id ORDER BY la.application_date DESC";
$result = $conn->query($sql);

// Fetch notifications
$notifySql = "SELECT * FROM notifications WHERE user_id='$user_id' ORDER BY created_at DESC";
$notifyResult = $conn->query($notifySql);

// Fetch approved leave count
$countSql = "SELECT COUNT(*) AS approved_count 
             FROM leave_applications 
             WHERE user_id=$user_id AND status='approved'";
$countResult = $conn->query($countSql);
$countRow = $countResult->fetch_assoc();
$approvedCount = $countRow['approved_count'];
?>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header><h1>User Dashboard</h1></header>
<div class="container">
    <a href="apply_leave.php" class="btn btn-primary">Apply for Leave</a>
    <a href="../logout.php" class="btn btn-danger">Logout</a>

    <!-- Leave Applications Section -->
    <div class="card">
        <h2>Your Leave Applications</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Start</th>
                <th>End</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
            <?php while($row=$result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['leave_id']; ?></td>
                <td><?php echo $row['leave_name']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td><?php echo $row['reason']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Notifications Section -->
    <div class="card">
        <h2>Notifications</h2>
        <ul>
            <?php while($row = $notifyResult->fetch_assoc()) { ?>
                <li style="<?php echo $row['is_read'] ? 'color:gray;' : 'color:blue;'; ?>">
                    <?php echo $row['message']; ?> 
                    <small>(<?php echo $row['created_at']; ?>)</small>
                </li>
            <?php } ?>
        </ul>
        <a href="read_notifications.php" class="btn btn-secondary">Mark all as read</a>
    </div>

    <!-- Leave Count Section -->
    <div class="card">
        <h2>Leave Count</h2>
        <p>You have <strong><?php echo $approvedCount; ?></strong> approved leave applications.</p>
    </div>
</div>
</body>
</html>
