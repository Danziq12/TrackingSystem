<!DOCTYPE html>
<html>
<head>
    <title>kerani Client Form</title>
    <link rel="stylesheet" href="kerani.css">
</head>
<body>
    <h1 class="main-title">Client Form</h1>

    <!-- Top Bar -->
    <div class="topbar">
        <form action="logout.php" method="get" style="display:inline;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <!-- ✅ New Client Info Button -->
        <form action="clientInfo.php" method="get" style="display:inline;">
            <button type="submit" class="client-info-btn">Client Info</button>
        </form>
    </div>

    <div class="container">
        <h2>Insert Client Information</h2>

        <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

        <form name="form1" method="POST" action="clientData.php">
            <label>Client Name:</label>
            <input type="text" name="client_name" required>

            <label>Phone Number:</label>
            <input type="text" name="phone_number" required>
            <input type="submit" value="Add Client">
        </form>
    </div>
</body>

<footer>
    <p>&copy; 2025 Binasifu. All rights reserved.</p>
</footer>
</html>
