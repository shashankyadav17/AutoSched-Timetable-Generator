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
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .top-bar {
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 999;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .top-bar .left-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-bar .left-section img {
            height: 32px;
            width: 32px;
        }

        .top-bar .title {
            font-size: 22px;
            font-weight: bold;
        }

        .top-bar .welcome {
            font-size: 18px;
        }

        .logout-btn {
            padding: 8px 16px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .sidebar {
            width: 200px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar h2 {
            margin-top: 10;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px 0;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .main-content {
            margin-left: 200px;
            margin-top: 60px;
            padding: 20px;
        }
    </style>
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
        <h2>Faculty Panel</h2>
        <a href="#">View Timetable</a>
        <a href="#">Apply Leave</a>
        <?php if ((int)$faculty_id === 1074): ?>
        <a href="#">Allot Leaves</a>
        <a href="#">Allot Faculty</a>
        <?php endif; ?>
    </div>

    <div class="main-content">
        <h2>Dashboard</h2>
        <p>Select an option from the sidebar to get started.</p>
    </div>

</body>
</html>