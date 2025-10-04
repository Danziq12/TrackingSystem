<?php
session_start();
include('dbconnect.php');

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please log in first.");
}

// ✅ Get logged-in user details
$user_id = $_SESSION['user_id'];
$userStmt = $conn->prepare("SELECT office, role FROM users WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

$logged_in_office = $user['office'] ?? '';
$logged_in_role   = $user['role'] ?? '';

// ✅ Only site supervisors should access this page
if ($logged_in_role !== 'site_supervisor') {
    die("Access denied. Only site supervisors can view this page.");
}

// ✅ Get only approved clients from the same office
$clientsQuery = "SELECT * FROM clients WHERE approved = 1 AND office = ?";
$clientsStmt = $conn->prepare($clientsQuery);
$clientsStmt->bind_param("s", $logged_in_office);
$clientsStmt->execute();
$clientsResult = $clientsStmt->get_result();

$clientData = [];

while ($client = $clientsResult->fetch_assoc()) {
    $client_id = $client['id'];

    // ✅ Get latest phase
    $phaseQuery = "SELECT * FROM project_phases WHERE client_id = ? ORDER BY id DESC LIMIT 1";
    $phaseStmt = $conn->prepare($phaseQuery);
    $phaseStmt->bind_param("i", $client_id);
    $phaseStmt->execute();
    $phaseResult = $phaseStmt->get_result();
    $phase = $phaseResult->fetch_assoc();
    $phaseStmt->close();

    $imageData = null;
    $imageType = null;

    if ($phase) {
        $phase_id = $phase['id'];

        // ✅ Get first image for the latest phase
        $imgQuery = "SELECT image, image_type FROM phase_images WHERE phase_id = ? LIMIT 1";
        $imgStmt = $conn->prepare($imgQuery);
        $imgStmt->bind_param("i", $phase_id);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();
        if ($imgRow = $imgResult->fetch_assoc()) {
            $imageData = $imgRow['image'];
            $imageType = $imgRow['image_type'];
        }
        $imgStmt->close();
    }

    $clientData[] = [
        'client_id' => $client_id,
        'name' => $client['name'],
        'phase_name' => $phase['phase_name'] ?? '-',
        'status' => $phase['status'] ?? '-',
        'image' => $imageData,
        'image_type' => $imageType
    ];
}

$clientsStmt->close();
?>
