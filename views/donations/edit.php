<?php
// Check if the donation data exists
if (!isset($donation) || empty($donation)) {
    echo "<p style='color:red;'>Error: Donation record not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donation</title>
</head>
<body>
    <h2>Edit Donation Record</h2>
    
    <form action="/views/donations/donationAdminV.php?action=editDonation&id=<?= htmlspecialchars($donation['id']) ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= isset($donation['name']) ? htmlspecialchars($donation['name']) : '' ?>" required>
        <br>

        <label for="donation_type">Donation Type:</label>
        <input type="text" name="donation_type" id="donation_type" value="<?= isset($donation['donation_type']) ? htmlspecialchars($donation['donation_type']) : '' ?>" required>
        <br>

        <label for="status">Status:</label>
        <input type="text" name="status" id="status" value="<?= isset($donation['status']) ? htmlspecialchars($donation['status']) : '' ?>" required>
        <br>

        <button type="submit">Update Donation</button>
    </form>
</body>
</html>
