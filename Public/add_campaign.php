<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['campaigncode'];
    $name = $_POST['campaign_name'];
    $price = floatval($_POST['fixed_price']);
    $shows = intval($_POST['campaignshow']);

    $stmt = $conn->prepare("INSERT INTO campaigns (campaigncode, campaign_name, fixed_price, campaignshow, created_at, updated_at, user_id) VALUES (?, ?, ?, ?, NOW(), NOW(), 1)");
    $stmt->bind_param("ssdi", $code, $name, $price, $shows);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Campaign added successfully.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Campaign</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="logo-container">
    <img src="logo1.png" alt="Company Logo" class="logo">
</div>

<h1>Add New Campaign</h1>

<form method="POST" class="campaign-form">
    <input name="campaigncode" placeholder="Campaign Code" required>
    <input name="campaign_name" placeholder="Name" required>
    <input name="fixed_price" placeholder="Fixed Price" type="number" step="0.01" required>
    <input name="campaignshow" placeholder="Shows" type="number" required>
    <button type="submit">Add Campaign</button>
</form>

<div class="quotes">
    <blockquote>"Fashion is the armor to survive the reality of everyday life." – Bill Cunningham</blockquote>
    <blockquote>"Style is a way to say who you are without having to speak." – Rachel Zoe</blockquote>
    <blockquote>"You can have anything you want in life if you dress for it." – Edith Head</blockquote>
</div>

</body>
</html>
