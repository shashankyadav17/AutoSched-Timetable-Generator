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

    <?php
    require '../db.php';

    $result = $conn->query("SELECT * FROM admin_notifications ORDER BY created_at DESC");

    if ($result->num_rows > 0) {
        echo "<h3>Notifications</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($row['message']) . "</strong> 
                    <em>(" . date("d M Y, h:i A", strtotime($row['created_at'])) . ")</em>
                    - <a href='timetable-settings.php?year=" . $row['year'] . "&sem=" . $row['semester'] . "'>Generate Timetable</a>
                 </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No new notifications.</p>";
    }

    $conn->close();
    ?>
</div>


</body>
</html>
