<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../home.html");
    exit;
}
$username = $_SESSION['username'];
echo "<p>Debug: Session username in student-dashboard.php = " . htmlspecialchars($username) . "</p>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
</head>
<body>

    <div class="top-bar">
        <div class="left-section">
            <div class="title">AutoSched</div>
        </div>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($username); ?> 👋</div>
        <form method="POST" action="../logout.php">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </div>

    <div class="sidebar">
        <a href="student-dashboard.php">Dashboard</a>
        <a href="student-view-timetable.php?username=<?php echo urlencode($username); ?>">View Timetable</a>
    </div>

    <div class="main-content">
        <h2>Dashboard</h2>
        <p>Select an option from the sidebar to get started.</p>
    </div>

</body>
</html>
