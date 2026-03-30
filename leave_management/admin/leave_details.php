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

$user_id = $_GET['user_id'];

// Fetch user info
$userSql = "SELECT name, email FROM users WHERE user_id=$user_id";
$userResult = $conn->query($userSql);
$user = $userResult->fetch_assoc();

// Fetch leave applications
$leaveSql = "SELECT lt.leave_name, la.start_date, la.end_date, la.reason, la.status
             FROM leave_applications la
             JOIN leave_types lt ON la.leave_type_id=lt.leave_type_id
             WHERE la.user_id=$user_id ORDER BY la.application_date DESC";
$leaveResult = $conn->query($leaveSql);

// Fetch leave count
$countSql = "SELECT 
                SUM(CASE WHEN status='approved' THEN 1 ELSE 0 END) AS approved,
                SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) AS rejected,
                SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) AS pending
             FROM leave_applications WHERE user_id=$user_id";
$countResult = $conn->query($countSql);
$countRow = $countResult->fetch_assoc();
?>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Leave Details</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header><h1>Leave Details - <?php echo $user['name']; ?></h1></header>
<div class="container">
    <a href="user_info.php" class="btn btn-secondary">Back</a>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <div class="card">
        <h2>Leave Summary</h2>
        <p>Approved: <strong><?php echo $countRow['approved']; ?></strong></p>
        <p>Rejected: <strong><?php echo $countRow['rejected']; ?></strong></p>
        <p>Pending: <strong><?php echo $countRow['pending']; ?></strong></p>
    </div>

    <div class="card">
        <h2>All Leave Applications</h2>
        <table>
            <tr>
                <th>Type</th>
                <th>Start</th>
                <th>End</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
            <?php while($row=$leaveResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['leave_name']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td><?php echo $row['reason']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
