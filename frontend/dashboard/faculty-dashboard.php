<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../home.html");
    exit;
}
$username = $_SESSION['username'];
$faculty_id = $_SESSION['faculty_id']; // Assuming faculty_id is stored in the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
</head>
<body>

<div class="top-bar">
    <div class="left-section">
        <img src="../assets/image.png" alt="Logo">
        <div class="title">AutoSched</div>
    </div>
    <div class="welcome">Welcome, <?php echo htmlspecialchars($username); ?> 👋</div>
    <form method="POST" action="../logout.php">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

<div class="sidebar">
    <a href="faculty-view-timetable.php?username=<?php echo urlencode($username); ?>">View Timetable</a>
    <a href="faculty-apply-leave.php">Apply Leave</a>
    <?php if ((int)$faculty_id === 1074): ?>
        <a href="hod-view-leave-requests.php">Allot Leaves</a>
        <a href="allot-faculty.php">Allot Faculty</a>
    <?php endif; ?>
</div>

<div class="main-content">
    <h2>Dashboard</h2>
    <p>Select an option from the sidebar to get started.</p>
</div>

</body>
</html>