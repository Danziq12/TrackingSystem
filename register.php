<?php
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $office = $_POST['office'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, office, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $office, $role);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Registration successful! <a href='Login.php'>Login here</a></p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        input, select {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #5a3eff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4a34d1;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Office:</label>
            <select name="office" required>
                <option value="Johor">Johor</option>
                <option value="Cyberjaya">Cyberjaya</option>
                <option value="Seremban">Seremban</option>
                <option value="Ipoh">Ipoh</option>
                <option value="Pengkalan Hulu">Pengkalan Hulu</option>
            </select>

            <label>Role:</label>
            <select name="role" required>
                <option value="marketing">Marketing</option>
                <option value="site_supervisor">Site Supervisor</option>
            </select>


            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
