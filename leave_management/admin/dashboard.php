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

$sql = "SELECT la.leave_id, u.name, lt.leave_name, la.start_date, la.end_date, la.reason, la.status 
        FROM leave_applications la 
        JOIN users u ON la.user_id=u.user_id
        JOIN leave_types lt ON la.leave_type_id=lt.leave_type_id
        ORDER BY la.application_date DESC";
$result = $conn->query($sql);
?>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header><h1>Admin Dashboard</h1></header>
<div class="container">
    <a href="../logout.php" class="btn btn-danger">Logout</a>
    <a href="user_info.php" class="btn btn-primary">User Info</a>

    <div class="card">
        <h2>All Leave Applications</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Type</th>
                <th>Start</th>
                <th>End</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while($row=$result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['leave_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['leave_name']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td><?php echo $row['reason']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <?php if ($row['status']=='pending'): ?>
                        <a href="approve.php?id=<?php echo $row['leave_id']; ?>" class="btn btn-primary">Approve</a>
                        <a href="reject.php?id=<?php echo $row['leave_id']; ?>" class="btn btn-danger">Reject</a>
                    <?php else: ?>
                        <?php echo ucfirst($row['status']); ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
