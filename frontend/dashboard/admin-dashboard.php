<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../home.html");
    exit;
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
        <a href="#">Manage Subjects</a>
        <a href="#">Generate Timetable</a>
        <a href="#">Manage Faculty</a>
    </div>

    <div class="main-content">
        <h2>Dashboard</h2>
        <p>Select an option from the sidebar to get started.</p>
    </div>

</body>
</html>
