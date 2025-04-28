<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../home.html");
    exit;
}

require_once '../db.php';
$faculty_id = $_SESSION['faculty_id'];
$name = $_SESSION['username'];

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = trim($_POST['reason']);

    if (empty($start_date) || empty($end_date)) {
        $error = "Start and End dates are required.";
    } elseif (strtotime($start_date) > strtotime($end_date)) {
        $error = "Start date cannot be after End date.";
    } elseif (strtotime($start_date) < strtotime(date('Y-m-d'))) {
        $error = "Cannot apply leave for past dates.";
    } else {
        $stmt = $conn->prepare("INSERT INTO faculty_leaves (faculty_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $faculty_id, $start_date, $end_date, $reason);
        if ($stmt->execute()) {
            $success = "✅ Leave request submitted successfully!";
        } else {
            $error = "❌ Failed to submit leave.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
    <style>
        .form-container {
            background: #f8f8f8;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            width: 400px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .form-container button {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="left-section">
        <img src="../assets/image.png" alt="Logo" style="height:40px;">
        <div class="title">AutoSched</div>
    </div>
    <div class="welcome">Welcome, <?= htmlspecialchars($name); ?> 👋</div>
    <form method="POST" action="../logout.php">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

<div class="sidebar">
    <a href="faculty-dashboard.php">Dashboard</a>
    <a href="faculty-view-timetable.php">View Timetable</a>
    <a href="faculty-apply-leave.php" class="active">Apply for Leave</a>
</div>

<div class="main-content">
    <h2>Apply for Leave</h2>

    <div class="form-container">
        <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

        <form method="POST">
            <label>Start Date:</label>
            <input type="date" name="start_date" required>

            <label>End Date:</label>
            <input type="date" name="end_date" required>

            <label>Reason (Optional):</label>
            <textarea name="reason" rows="4"></textarea>

            <button type="submit">Submit Leave Request</button>
        </form>
    </div>
</div>

</body>
</html>
