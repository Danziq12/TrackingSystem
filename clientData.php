<?php
session_start();
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $client_name = $_POST['client_name'];
    $phone_number = $_POST['phone_number'];

    // ✅ Get staff ID from session
    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized. Please login first.");
    }

    $staff_id = $_SESSION['user_id'];

    // ✅ Insert with staff ID
    $stmt = $conn->prepare("INSERT INTO clients (name, phone_number, staff_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $client_name, $phone_number, $staff_id);

    if ($stmt->execute()) {
        // ✅ Show success message + redirect after 2 seconds
        echo "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='refresh' content='2;url=kerani.php'> <!-- Redirect in 2s -->
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    background: #f4f6fc;
                    padding-top: 50px;
                }
                .success {
                    font-size: 20px;
                    font-weight: bold;
                    color: green;
                    margin-bottom: 10px;
                }
                p {
                    font-size: 16px;
                    color: #333;
                }
            </style>
        </head>
        <body>
            <p class='success'>✅ Client added successfully!</p>
            <p>Redirecting you back to dashboard...</p>
        </body>
        </html>";
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error adding client: " . $stmt->error . "</p>";
    }
}
?>
