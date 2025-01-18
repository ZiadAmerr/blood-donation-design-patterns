<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donation</title>
</head>
<body>
    <h2>Edit Donation Record</h2>
    <form action="index.php?action=editDonation&id=<?php echo $donation['id']; ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($donation['name']); ?>" required>
        <br>

        <label for="donation_type">Donation Type:</label>
        <input type="text" name="donation_type" id="donation_type" value="<?php echo htmlspecialchars($donation['donation_type']); ?>" required>
        <br>

        <label for="status">Status:</label>
        <input type="text" name="status" id="status" value="<?php echo htmlspecialchars($donation['status']); ?>" required>
        <br>

        <button type="submit">Update Donation</button>
    </form>
</body>
</html>
