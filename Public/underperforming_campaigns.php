<?php
// underperforming_campaigns.php
require 'config.php';

$budgetThreshold = isset($_GET['budget']) ? (float)$_GET['budget'] : null;
$result = null;

if ($budgetThreshold !== null && $budgetThreshold > 0) {
    $sql = "
        SELECT id, campaign_name, fixed_price, campaignshow,
               (fixed_price * campaignshow) AS total_spend
        FROM campaigns
        WHERE (fixed_price * campaignshow) < (0.5 * ?)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $budgetThreshold);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Underperforming Campaigns</title>
    <link rel="stylesheet" href="report_styles.css">
</head>
<body>
    <h1>⚠️ Underperforming Campaigns</h1>

    <form method="get" action="underperforming_campaigns.php" style="margin: 20px;">
        <label for="budget">Enter Total Budget Threshold:</label>
        <input type="number" step="0.01" name="budget" id="budget" required value="<?= htmlspecialchars($budgetThreshold ?? '') ?>">
        <button type="submit" class="report-button" style="margin-top: 10px;">Filter Campaigns</button>
    </form>

    <?php if ($budgetThreshold !== null): ?>
        <?php if ($result && $result->num_rows > 0): ?>
            <table border="1" style="margin: auto; width: 90%; margin-top: 30px;">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Fixed Price</th>
                    <th>Shows</th>
                    <th>Total Spend</th>
                    <th>Budget Input</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['campaign_name']) ?></td>
                    <td><?= number_format($row['fixed_price'], 2) ?></td>
                    <td><?= $row['campaignshow'] ?></td>
                    <td><?= number_format($row['total_spend'], 2) ?></td>
                    <td><?= number_format($budgetThreshold, 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #888; margin-top: 20px;">
                No underperforming campaigns found for budget <strong><?= number_format($budgetThreshold, 2) ?></strong>.
            </p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
