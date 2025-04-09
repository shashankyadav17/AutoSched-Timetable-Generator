<?php
session_start();
include 'db.php'; // ✅ includes the connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check in appropriate table based on role
    if ($role === 'student') {
        $query = "SELECT * FROM students WHERE roll_number = '$username' AND password = '$password'";
    } elseif ($role === 'faculty') {
        $query = "SELECT * FROM faculty WHERE email = '$username' AND password = '$password'";
    } elseif ($role === 'admin') {
        $query = "SELECT * FROM admins WHERE email = '$username' AND password = '$password'";
    } else {
        echo "Invalid role.";
        exit;
    }

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect
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
}
?>
