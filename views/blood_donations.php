<?php
// Start the session
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/BloodDonationController.php';

$controller = new BloodDonationController();
$donations = $controller->getDonations();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Donations</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Blood Donations</h2>
    <button onclick="window.location.href='donate_blood.php'">Donate Blood</button>
    <button onclick="window.location.href='donate_plasma.php'">Donate Plasma</button>

    <h3>Past Donations</h3>
    <table>
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Blood Donation Type</th>
                <th>Blood Type</th>
                <th>Amount (Liters)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($donations)): ?>
                <tr>
                    <td colspan="4">No donations found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <?php
                        $name = $controller->getDonorName($donation['donor_id'])
                        ?>
                        <td><?php echo htmlspecialchars($name); ?></td>
                        <td><?php echo htmlspecialchars($donation['blooddonationtype']); ?></td>
                        <td><?php echo htmlspecialchars($donation['blood_type']); ?></td>
                        <td><?php echo number_format($donation['number_of_liters'], 2); ?></td>
                        <td><?php echo htmlspecialchars($donation['date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
