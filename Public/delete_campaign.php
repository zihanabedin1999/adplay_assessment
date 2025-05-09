<?php
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['campaigncode'];
    $stmt = $conn->prepare("DELETE FROM campaigns WHERE campaigncode = ?");
    $stmt->bind_param("s", $code);

    if ($stmt->execute()) {
        $message = "<p style='color:green;'>✅ Campaign deleted successfully.</p>";
    } else {
        $message = "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delete Campaign</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<h1>Delete a Campaign</h1>

<?= $message ?>

<form method="POST" class="campaign-form">
    <input name="campaigncode" placeholder="Enter Campaign Code" required>
    <button type="submit" class="delete-button">Delete Campaign</button>
</form>

<div class="quotes">
    <blockquote>"Sometimes the smallest step in the right direction ends up being the biggest step of your life."</blockquote>
    <blockquote>"You must be bold, brave, and courageous and find a way... to get in the way." – John Lewis</blockquote>
    <blockquote>"Out of clutter, find simplicity. From discord, find harmony. In the middle of difficulty lies opportunity." – Albert Einstein</blockquote>
</div>

</body>
</html>
