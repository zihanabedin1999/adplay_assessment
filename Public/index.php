<?php
// index.php
require 'config.php';

// Fetch campaigns
$sql = "SELECT campaigncode, campaign_name, fixed_price, campaignshow FROM campaigns";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Active Campaigns</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Blue Button Style */
        .blue-button {
            background-color: #007bff; /* Blue background */
            color: white; /* White text */
            padding: 10px 20px; /* Padding */
            text-decoration: none; /* Remove underline */
            border-radius: 5px; /* Rounded corners */
            font-weight: bold; /* Bold text */
            text-align: center; /* Center text */
            display: inline-block; /* Make it behave like a button */
            margin-top: 10px; /* Space above */
        }

        .blue-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .add-btn-container a {
            margin: 5px;
        }
    </style>
</head>
<body>
    <!-- Logo Section -->
    <div class="logo-container">
        <img src="logo.jfif" alt="Company Logo" class="logo">
    </div>

    <h1>Active Campaigns</h1>

    <!-- Action Buttons -->
    <div class="add-btn-container">
        <a href="add_campaign.php" class="add-button">➕ Add New Campaign</a>
        <a href="delete_campaign.php" class="delete-button">🗑️ Delete Campaign</a>
        <a href="report_dashboard.php" class="add-button">📈 View Campaign Reports</a>
        <a href="campaign_reconciliation.php" class="blue-button">🔄 Campaign Reconciliation</a> <!-- Blue Button -->
    </div>

    <table id="campaignTable">
        <thead>
            <tr>
                <th>Campaign Code</th>
                <th>Name</th>
                <th>Fixed Price</th>
                <th>Shows</th>
                <th>Total Spend</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["campaigncode"]) ?></td>
                    <td><?= htmlspecialchars($row["campaign_name"]) ?></td>
                    <td><?= number_format($row["fixed_price"], 2) ?></td>
                    <td><?= (int)$row["campaignshow"] ?></td>
                    <td class="spend"><?= number_format($row["fixed_price"] * $row["campaignshow"], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No campaigns found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
