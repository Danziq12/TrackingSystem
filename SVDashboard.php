<?php include('sv.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Dashboard</title>
    <link rel="stylesheet" href="sv_dashboard.css">
</head>
<body>
    <h1 class="title">Supervisor Dashboard</h1>
    <div class="topbar">
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Latest Phase</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientData as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['name']) ?></td>
                        <td><?= htmlspecialchars($client['phase_name']) ?></td>
                        <td><?= htmlspecialchars($client['status']) ?></td>
                        <td>
                            <?php if (!empty($client['image'])): ?>
                                <img src="data:<?= $client['image_type'] ?>;base64,<?= base64_encode($client['image']) ?>" width="100">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="action-btn update" href="update_phase.php?id=<?= $client['client_id'] ?>">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
