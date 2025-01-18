<?php
// File: /views/donations/list.php

if (isset($_GET['message'])) {
    if ($_GET['message'] === 'update_success') {
        echo "<p style='color:green;'>Donation updated successfully.</p>";
    } elseif ($_GET['message'] === 'delete_success') {
        echo "<p style='color:red;'>Donation deleted successfully.</p>";
    }
}

?>

<h2>Donation Records</h2>
<table border="1">
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
                <td><?= htmlspecialchars($donation['id']) ?></td>
                <td><?= htmlspecialchars($donation['donor_id']) ?></td>
                <td><?= htmlspecialchars($donation['number_of_liters']) ?></td>
                <td><?= htmlspecialchars($donation['blooddonationtype']) ?></td>
                <td><?= htmlspecialchars($donation['date']) ?></td>
                <td>
                    <!-- Edit Link -->
                    <a href="/views/donations/donationAdminV.php?action=editDonation&id=<?= $donation['id'] ?>">
                        <button>Edit</button>
                    </a>

                    <!-- Delete Form -->
                    <form action="/views/donations/donationAdminV.php?action=deleteDonation" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $donation['id'] ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
