<?php
session_start();
include('dbconnect.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // ✅ Store session data
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id']; // ✅ Save staff ID
            $_SESSION['role'] = $user['role']; // ✅ Store role in session

            // ✅ Redirect based on role
            if ($user['role'] === "marketing") {
                header("Location: kerani.php");
            } elseif ($user['role'] === "site_supervisor") {
                header("Location: SVDashboard.php");
            } else {
                header("Location: login.php"); // fallback if role not recognized
            }
            exit;
        } else {
            $error = "❌ Wrong password!";
        }
    } else {
        $error = "❌ No user found!";
    }
}
?>
