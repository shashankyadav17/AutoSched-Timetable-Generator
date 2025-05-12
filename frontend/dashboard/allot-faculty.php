<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty' || $_SESSION['faculty_id'] !== '1074') {
    header("Location: ../home.html");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Allot Faculty - AutoSched</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css"> <!-- update this path if needed -->
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="left-section">
            <div class="title">AutoSched</div>
        </div>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($username); ?> 👋</div>
        <form method="POST" action="../logout.php">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Sidebar -->
        <div class="sidebar">
            <a href="faculty-dashboard.php">Dashboard</a>
            <a href="view-timetable.php">View Timetable</a>
            <a href="faculty-apply-leave.php">Apply Leave</a>
            <?php if ($_SESSION['faculty_id'] === '1074'): ?>
                <a href="hod-view-leave-requests.php">Allot Leaves</a>
                <a href="allot-faculty.php">Allot Faculty</a>
            <?php endif; ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Allot Faculty to Subjects</h2>

            <form id="allotForm" method="POST" action="submit-allotment.php">
                <div class="form-section">
                    <label for="year">Select Year:</label>
                    <select id="year" name="year" required>
                        <option value="">--Select--</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                    </select>

                    <label for="sem">Select Semester:</label>
                    <select id="sem" name="sem" required>
                        <option value="">--Select--</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                    </select>
                   <div style="text-align: center; margin-top: 15px;">
                    <button class="submit-btn" type="button" style="width: 200px;" onclick="loadSubjects()">Load Subjects</button>
                   </div>
                </div>

                <div class="subjects-container" id="subjectsContainer">
                    <!-- Dynamic subject allocation grid will appear here -->
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="submit-btn"  style="width: 200px; margin-left:385px;">Submit Allotments</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function loadSubjects() {
            const year = document.getElementById('year').value;
            const sem = document.getElementById('sem').value;

            if (!year || !sem) {
                alert("Please select both year and semester.");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/AutoSched-Timetable-Generator/frontend/dashboard/fetch-sujects.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                document.getElementById('subjectsContainer').innerHTML = this.responseText;
            };
            xhr.send(`year=${year}&sem=${sem}`);
        }
    </script>

</body>
</html>
