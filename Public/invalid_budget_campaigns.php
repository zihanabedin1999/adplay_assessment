<?php
require 'config.php';

$totalBudget = isset($_GET['total_budget']) ? (float)$_GET['total_budget'] : null;
$result = null;

if ($totalBudget !== null && $totalBudget > 0) {
    $sql = "
        SELECT id, campaigncode, campaign_name, fixed_price, campaignshow,
               (fixed_price * campaignshow) AS total_spend,
               (1000 + fixed_price * campaignshow) AS daily_budget
        FROM campaigns
        WHERE (1000 + fixed_price * campaignshow) > ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $totalBudget);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invalid Budget Campaigns</title>
    <link rel="stylesheet" href="report_style2.css">
</head>
<body>
    <h1>🚫 Campaigns With Invalid Daily Budgets</h1>

    <form method="get" action="invalid_budget_campaigns.php" class="budget-form">
        <label for="total_budget">Enter Total Budget:</label>
        <input type="number" step="0.01" name="total_budget" id="total_budget" required value="<?= htmlspecialchars($totalBudget ?? '') ?>">
        <button type="submit" class="report-button">Check</button>
    </form>

    <?php if ($totalBudget !== null): ?>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Fixed Price</th>
                        <th>Shows</th>
                        <th>Total Spend</th>
                        <th>Daily Budget</th>
                        <th>Limit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['campaigncode']) ?></td>
                        <td><?= htmlspecialchars($row['campaign_name']) ?></td>
                        <td><?= number_format($row['fixed_price'], 2) ?></td>
                        <td><?= $row['campaignshow'] ?></td>
                        <td><?= number_format($row['total_spend'], 2) ?></td>
                        <td><?= number_format($row['daily_budget'], 2) ?></td>
                        <td><?= number_format($totalBudget, 2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No invalid budget campaigns found for budget <strong><?= number_format($totalBudget, 2) ?></strong>.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
