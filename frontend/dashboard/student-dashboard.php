<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../home.html");
    exit;
}
$username = $_SESSION['username'];
$roll = $_SESSION['username'];
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

        <div class="notifications">
            <?php
           $roll = $_SESSION['username'];

           // Fetch student's semester_id and section
           $student_info = $conn->query("SELECT semester_id, section FROM students WHERE roll_number = '$roll'")->fetch_assoc();
           $semester_id = $student_info['semester_id'];
           $section = $conn->real_escape_string($student_info['section']);

           $sql = "SELECT DISTINCT message, created_at FROM notifications WHERE user_type = 'student' AND semester_id = $semester_id AND section = '$section' ORDER BY created_at DESC LIMIT 5";
           $res = $conn->query($sql);
           echo "<h3>📢 Notifications</h3>";
           function extractDateFromMessage($message) {
               // Extract date in format YYYY-MM-DD from message string
               if (preg_match('/\d{4}-\d{2}-\d{2}/', $message, $matches)) {
                   return $matches[0];
               }
               return null;
           }
           while ($row = $res->fetch_assoc()) {
               $classDate = extractDateFromMessage($row['message']);
               $displayDate = $classDate ? $classDate : $row['created_at'];
               echo "<p>🕒 " . $displayDate . "<br>" . htmlspecialchars($row['message']) . "</p><hr>";
           }   

            ?>
        </div>
    </div>

</body>
</html>
