<?php
session_start();
include('dbconnect.php');

// ✅ Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

$staff_id = $_SESSION['user_id']; // ✅ Logged-in staff's ID

// ✅ Handle Delete (POST request)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $deleteStmt = $conn->prepare("DELETE FROM clients WHERE id = ? AND staff_id = ?");
    $deleteStmt->bind_param("ii", $delete_id, $staff_id);
    $deleteStmt->execute();
    $deleteStmt->close();

    // ✅ Redirect to refresh the table after delete
    header("Location: clientInfo.php");
    exit;
}

// ✅ Select only clients created by this staff
$stmt = $conn->prepare("SELECT * FROM clients WHERE staff_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fc;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1.main-title {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            color: #5a3eff;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #5a3eff;
            color: white;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a.client-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        a.client-link:hover {
            text-decoration: underline;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 15px;
            background-color: #6c757d;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .delete-form {
            display: inline;
        }
    </style>
</head>
<body>
    <h1 class="main-title">Client Info</h1>
    <div class="container">
        <!-- Back Button -->
        <a href="kerani.php" class="back-btn">⬅ Back</a>

        <table>
            <tr>
                <th>Client Name</th>
                <th>Phone</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <a href="clientDetails.php?id=<?= $row['id']; ?>" class="client-link">
                        <?= htmlspecialchars($row['name']); ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($row['phone_number']); ?></td>
                <td>
                    <?= !empty($row['updated_at']) 
                        ? date("d M Y H:i", strtotime($row['updated_at'])) 
                        : 'Not updated yet'; ?>
                </td>
                <td>
                    <!-- Delete Button -->
                    <form method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this client?');">
                        <input type="hidden" name="delete_id" value="<?= $row['id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
