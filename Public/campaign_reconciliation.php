<?php
// DB config
$host = 'localhost';
$dbname = 'marketing_db';
$username = 'root'; // change if needed
$password = '';     // change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch campaigns grouped by user_id with potential overlaps
    $stmt = $pdo->query("
        SELECT 
            user_id,
            GROUP_CONCAT(campaign_name ORDER BY created_at) AS campaigns,
            COUNT(*) AS total_campaigns
        FROM campaigns
        GROUP BY user_id
        HAVING total_campaigns >= 1
    ");
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Campaign Reconciliation</title>
    <link rel="stylesheet" type="text/css" href="style3.css">
</head>
<body>
    <h2>Campaign Reconciliation Report</h2>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Campaigns</th>
                <th>Total Campaigns</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($campaigns as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= htmlspecialchars($row['campaigns']) ?></td>
                    <td><?= htmlspecialchars($row['total_campaigns']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
