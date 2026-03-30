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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $leave_type = $_POST['leave_type'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO leave_applications (user_id, leave_type_id, start_date, end_date, reason) 
            VALUES ('$user_id','$leave_type','$start','$end','$reason')";
    $conn->query($sql);

    header("Location: dashboard.php");
    exit();
}

$types = $conn->query("SELECT * FROM leave_types");
?>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Apply Leave</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header><h1>Apply for Leave</h1></header>
<div class="container">
    <div class="card">
        <form method="POST">
            <label>Leave Type:</label><br>
            <select name="leave_type" required>
                <?php while($row=$types->fetch_assoc()): ?>
                    <option value="<?php echo $row['leave_type_id']; ?>"><?php echo $row['leave_name']; ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Start Date:</label><br>
            <input type="date" name="start_date" required><br><br>

            <label>End Date:</label><br>
            <input type="date" name="end_date" required><br><br>

            <label>Reason:</label><br>
            <textarea name="reason" required></textarea><br><br>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
</body>
</html>
