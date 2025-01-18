<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Records</title>
</head>
<body>
    <h2>Donation Records</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Donor ID</th>
                <th>Number of Liters</th>
                <th>Blood Donation Type</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donations as $donation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($donation['id']); ?></td>
                    <td><?php echo htmlspecialchars($donation['donor_id']); ?></td>
                    <td><?php echo htmlspecialchars($donation['number_of_liters']); ?></td>
                    <td><?php echo htmlspecialchars($donation['blooddonationtype']); ?></td>
                    <td><?php echo htmlspecialchars($donation['date']); ?></td>
                    <td>
                        <!-- Edit Form -->
                        <form action="index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="editDonation">
                            <input type="hidden" name="id" value="<?php echo $donation['id']; ?>">
                            <button type="submit">Edit</button>
                        </form>

                        <!-- Delete Form -->
                        <form action="index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="deleteDonation">
                            <input type="hidden" name="id" value="<?php echo $donation['id']; ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
