<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($role === 'student') {
        // Convert dd-mm-yyyy to yyyy-mm-dd
        $dob = DateTime::createFromFormat('d-m-Y', $password);
        if (!$dob) {
            echo "<script>alert('Invalid date format. Use dd-mm-yyyy'); window.location.href = 'home.html';</script>";
            exit;
        }
        $dob_sql_format = $dob->format('Y-m-d');

        // Prepare and execute
        $stmt = $conn->prepare("SELECT * FROM students WHERE roll_number = ? AND date_of_birth = ?");
        $stmt->bind_param("ss", $username, $dob_sql_format);
        $stmt->execute();
        $result = $stmt->get_result();

    } elseif ($role === 'faculty') {
        $query = "SELECT * FROM faculty WHERE email = '$username' AND employee_id = '$password'";
        $result = $conn->query($query);

    } elseif ($role === 'admin') {
        $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($query);

    } else {
        echo "Invalid role.";
        exit;
    }

    if ($result && $result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if ($role === 'student') {
            header("Location: dashboard/student-dashboard.php");
        } elseif ($role === 'faculty') {
            header("Location: dashboard/faculty-dashboard.php");
        } else {
            header("Location: dashboard/admin-dashboard.php");
        }
        exit;
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href = 'home.html';</script>";
    }

    if (isset($stmt)) $stmt->close(); // Close only if used
    $conn->close();
}
?>
