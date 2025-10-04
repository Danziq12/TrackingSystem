<?php
include('dbconnect.php');

$id = $_GET['id'];
$success = $error = '';

// Get client name
$client_name = '';
$clientStmt = $conn->prepare("SELECT name FROM clients WHERE id = ?");
$clientStmt->bind_param("i", $id);
$clientStmt->execute();
$clientResult = $clientStmt->get_result();
if ($row = $clientResult->fetch_assoc()) {
    $client_name = $row['name'];
}
$clientStmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phase_name = $_POST['phase_name'];
    $status = $_POST['status'];
    $client_id = $_POST['client_id'];

    // Insert project phase
    $stmt = $conn->prepare("INSERT INTO project_phases (client_id, phase_name, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $client_id, $phase_name, $status);

    if ($stmt->execute()) {
        $phase_id = $stmt->insert_id;

        // ✅ Handle multiple images (optional)
        if (!empty($_FILES['images']['name'][0])) { // Only process if at least one file is uploaded
            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] === 0 && is_uploaded_file($tmpName)) {
                    $imageData = file_get_contents($tmpName);
                    $imageType = $_FILES['images']['type'][$i];

                    $imagePlaceholder = null;
                    $imgStmt = $conn->prepare("INSERT INTO phase_images (phase_id, image, image_type) VALUES (?, ?, ?)");
                    $imgStmt->bind_param("ibs", $phase_id, $imagePlaceholder, $imageType);
                    $imgStmt->send_long_data(1, $imageData);
                    $imgStmt->execute();
                    $imgStmt->close();
                }
            }
        }

        header("Location: SVDashboard.php");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Project Phase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fc;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #5a3eff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type=text], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type=file] {
            margin-top: 10px;
        }

        input[type=submit] {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #5a3eff;
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #4834d4;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Phase for <?= htmlspecialchars($client_name) ?></h2>

        <?php if ($success): ?>
            <p class="message success"><?= $success ?></p>
        <?php elseif ($error): ?>
            <p class="message error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="client_id" value="<?= htmlspecialchars($id) ?>">

            <label for="phase_name">Phase Name:</label>
            <input type="text" name="phase_name" required>

            <label for="status">Status:</label>
            <select name="status" required>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>

            <label for="images">Upload Progress Images:</label>
            <input type="file" name="images[]" accept="image/*" multiple> <!-- ✅ Removed required -->

            <input type="submit" value="Submit Update">
        </form>
    </div>
</body>
</html>
