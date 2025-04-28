<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty' || $_SESSION['faculty_id'] != 1074) {
    header("Location: ../home.html");
    exit;
}

require_once '../db.php';
$name = $_SESSION['username'];

$success = "";
$error = "";

// Handle Approve or Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_id']) && isset($_POST['action'])) {
    $leave_id = (int)$_POST['leave_id'];
    $action = $_POST['action']; // "Approved" or "Rejected"

    if (in_array($action, ['Approved', 'Rejected'])) {
        $stmt = $conn->prepare("UPDATE faculty_leaves SET status = ? WHERE leave_id = ?");
        $stmt->bind_param("si", $action, $leave_id);
        if ($stmt->execute()) {
            if ($action === 'Approved') {
                // 🔥 If Approved, assign substitutes
                require_once 'auto_generate_substitutions.php';
                assign_substitutes_for_leave($leave_id);
            }
            $success = "✅ Leave has been " . strtolower($action) . ".";
        } else {
            $error = "❌ Failed to update leave status.";
        }
        $stmt->close();
    }
    
}

// Fetch all pending leaves
$sql = "SELECT fl.leave_id, f.name AS faculty_name, fl.start_date, fl.end_date, fl.reason, fl.status 
        FROM faculty_leaves fl
        JOIN faculty f ON fl.faculty_id = f.faculty_id
        WHERE fl.status = 'Pending'
        ORDER BY fl.applied_on DESC";

$result = $conn->query($sql);

$leave_requests = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $leave_requests[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HOD - Manage Leaves</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        .form-section { margin-top: 20px; }
        .success { color: green; }
        .error { color: red; }
        button { padding: 6px 12px; margin: 2px; }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="left-section">
        <img src="../assets/image.png" alt="Logo" style="height:40px;">
        <div class="title">AutoSched</div>
    </div>
    <div class="welcome">Welcome, HOD <?= htmlspecialchars($name); ?> 👋</div>
    <form method="POST" action="../logout.php">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

<div class="sidebar">
    <a href="faculty-dashboard.php">Dashboard</a>
    <a href="hod-view-leave-requests.php" class="active">Manage Leaves</a>
</div>

<div class="main-content">
    <h2>Manage Faculty Leave Requests</h2>

    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <?php if (count($leave_requests) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?= htmlspecialchars($leave['faculty_name']) ?></td>
                        <td><?= htmlspecialchars($leave['start_date']) ?></td>
                        <td><?= htmlspecialchars($leave['end_date']) ?></td>
                        <td><?= htmlspecialchars($leave['reason']) ?></td>
                        <td><?= htmlspecialchars($leave['status']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="leave_id" value="<?= $leave['leave_id'] ?>">
                                <button type="submit" name="action" value="Approved" style="background:green; color:white;">Approve</button>
                                <button type="submit" name="action" value="Rejected" style="background:red; color:white;">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending leave requests.</p>
    <?php endif; ?>
</div>

</body>
</html>
