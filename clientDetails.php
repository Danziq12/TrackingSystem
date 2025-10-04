<?php
session_start();
include('dbconnect.php');

if (!isset($_GET['id'])) {
    die("Client ID missing");
}

$client_id = intval($_GET['id']);

// ✅ Get logged-in user details
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized - Please log in");
}

$user_id = $_SESSION['user_id'];
$userStmt = $conn->prepare("SELECT username, office, role FROM users WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

$logged_in_office = $user['office'] ?? '';
$logged_in_role   = $user['role'] ?? '';

// ✅ Handle Description Update + Save Date & History
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_description'])) {
    $new_description = $_POST['description'];
    $now = date("Y-m-d H:i:s");

    // Get the current description before updating
    $stmt = $conn->prepare("SELECT description FROM clients WHERE id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $stmt->bind_result($old_description);
    $stmt->fetch();
    $stmt->close();

    // Save old description to history (if exists)
    if (!empty($old_description)) {
        $historyStmt = $conn->prepare("INSERT INTO client_description_history (client_id, old_description, changed_at) VALUES (?, ?, ?)");
        $historyStmt->bind_param("iss", $client_id, $old_description, $now);
        $historyStmt->execute();
        $historyStmt->close();
    }

    // Update the description
    $stmt = $conn->prepare("UPDATE clients SET description = ?, updated_at = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_description, $now, $client_id);
    $stmt->execute();
    $stmt->close();
}

// ✅ Handle Approve/Reject (Only for marketing role)
if ($logged_in_role === 'marketing') {
    if (isset($_GET['approve'])) {
        $stmt = $conn->prepare("UPDATE clients SET approved = 1, office = ? WHERE id = ?");
        $stmt->bind_param("si", $logged_in_office, $client_id);
        $stmt->execute();
        $stmt->close();
    }
    if (isset($_GET['reject'])) {
        $conn->query("DELETE FROM clients WHERE id = $client_id");
        header("Location: clientInfo.php");
        exit;
    }
}

// ✅ Fetch updated client info
$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ✅ Fetch previous descriptions
$historyQuery = $conn->prepare("SELECT old_description, changed_at FROM client_description_history WHERE client_id = ? ORDER BY changed_at DESC");
$historyQuery->bind_param("i", $client_id);
$historyQuery->execute();
$historyResult = $historyQuery->get_result();
$historyQuery->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Details</title>
    <link rel="stylesheet" href="clientDetails.css">
</head>
<body>
    <h1 class="main-title">Client Details</h1>
    <div class="container">
        <p><strong>Name:</strong> <?= htmlspecialchars($client['name']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($client['phone_number']); ?></p>
        <p><strong>Office:</strong> <?= htmlspecialchars($client['office'] ?? 'Not Assigned'); ?></p>
        <p><strong>Status:</strong> <?= $client['approved'] ? '<span style="color:green;">Approved</span>' : '<span style="color:red;">Pending</span>'; ?></p>

        <!-- Editable Description -->
        <form method="POST" style="margin-top:20px;">
            <label><strong>Stage:</strong></label>
            <textarea name="description" rows="3" style="width:97%;"><?= htmlspecialchars($client['description'] ?? ''); ?></textarea>
            <button type="submit" name="update_description" class="save-btn" style="margin-top:8px;">Save Stage</button>
        </form>

        <!-- Show Old Descriptions -->
        <?php if ($historyResult->num_rows > 0): ?>
            <div style="margin-top:15px;">
                <h3 style="font-size: 16px; margin-bottom:10px;">Previous Stage</h3>
                <ul style="list-style:none; padding-left:0; margin:0;">
                    <?php while ($row = $historyResult->fetch_assoc()): ?>
                        <li style="margin-bottom:8px; padding:10px; background:#f8f8f8; border-radius:6px;">
                            <p style="margin:0;"><?= nl2br(htmlspecialchars($row['old_description'])); ?></p>
                            <small style="color:#555;">Saved on: <?= date("d M Y H:i", strtotime($row['changed_at'])); ?></small>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Approve / Reject Buttons (Only for Marketing Staff) -->
        <?php if ($logged_in_role === 'marketing'): ?>
            <div style="margin-top:20px;">
                <a href="?id=<?= $client_id; ?>&approve=1" class="approve-btn">Approve</a>
                <a href="?id=<?= $client_id; ?>&reject=1" class="reject-btn" onclick="return confirm('Are you sure you want to reject this client?');">Reject</a>
            </div>
        <?php endif; ?>

        <div style="margin-top:20px;">
            <a href="clientInfo.php" style="text-decoration:none; color:#007bff;">⬅ Back to Client List</a>
        </div>
    </div>
</body>
</html>
