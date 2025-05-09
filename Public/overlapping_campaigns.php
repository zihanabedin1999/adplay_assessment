<?php
require 'config.php';

// Assume the end date is provided via GET, e.g. ?end_date=23/06/15
$end_date_input = isset($_GET['end_date']) ? $_GET['end_date'] : null;
$end_date = null;

// Regular expression to validate the yy/mm/dd format
if ($end_date_input && preg_match('/^\d{2}\/\d{2}\/\d{2}$/', $end_date_input)) {
    // Validate and convert the input date to a proper format
    $end_date = DateTime::createFromFormat('y/m/d', $end_date_input);
    if ($end_date && $end_date->format('y/m/d') === $end_date_input) {
        // Convert to standard SQL format (YYYY-MM-DD)
        $end_date = $end_date->format('Y-m-d');
    } else {
        die("❌ Invalid date. Please check the values (e.g. 23/06/15).");
    }
} else {
    die("❌ Invalid date format. Please use the format yy/mm/dd.");
}

// SQL query to find overlapping campaigns with the user-provided end date
$sql = "
    SELECT 
        A.id AS campaign1_id,
        A.campaigncode AS campaign1_code,
        B.id AS campaign2_id,
        B.campaigncode AS campaign2_code,
        A.user_id,
        A.created_at AS start1,
        DATE_ADD(A.created_at, INTERVAL 30 DAY) AS end1,
        B.created_at AS start2,
        DATE_ADD(B.created_at, INTERVAL 30 DAY) AS end2
    FROM campaigns A
    JOIN campaigns B
      ON A.user_id = B.user_id
     AND A.id < B.id
     AND A.created_at <= DATE_ADD(B.created_at, INTERVAL 30 DAY)
     AND B.created_at <= DATE_ADD(A.created_at, INTERVAL 30 DAY)
     AND DATE_ADD(A.created_at, INTERVAL 30 DAY) <= ?
     AND DATE_ADD(B.created_at, INTERVAL 30 DAY) <= ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $end_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overlapping Campaigns</title>
    <link rel="stylesheet" href="report_style3.css">
    <script>
        // Optional: Format the date input for the user
        function formatDateInput() {
            const input = document.getElementById('end_date');
            let value = input.value.replace(/[^0-9\/]/g, ''); // Allow only numbers and slashes
            if (value.length > 2 && value[2] !== '/') value = value.slice(0, 2) + '/' + value.slice(2);
            if (value.length > 5 && value[5] !== '/') value = value.slice(0, 5) + '/' + value.slice(5);
            input.value = value;
        }
    </script>
</head>
<body>
    <h1>⚔️ Valid Campaigns (Non-Overlapping within End Date)</h1>

    <div style="display: flex; justify-content: center; margin: 40px 0;">
    <form method="get" action="overlapping_campaigns.php" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <label for="end_date">Enter End Date (yy/mm/dd):</label>
        <input type="text" name="end_date" id="end_date" required
               value="<?= htmlspecialchars($end_date_input ?? '') ?>"
               placeholder="yy/mm/dd" maxlength="8" oninput="formatDateInput()">
        <button type="submit" class="report-button">Filter Campaigns</button>
    </form>
</div>


    <?php if ($end_date): ?>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Campaign #1</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Campaign #2</th>
                        <th>Start</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= htmlspecialchars($row['campaign1_code']) ?> (ID: <?= $row['campaign1_id'] ?>)</td>
                        <td><?= $row['start1'] ?></td>
                        <td><?= $row['end1'] ?></td>
                        <td><?= htmlspecialchars($row['campaign2_code']) ?> (ID: <?= $row['campaign2_id'] ?>)</td>
                        <td><?= $row['start2'] ?></td>
                        <td><?= $row['end2'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data"> No valid campaigns found that do not overlap within the end date.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
