<?php
// Start the session
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/BloodDonationController.php';

$controller = new BloodDonationController();
$donations = $controller->getDonations();
if (!isset($_SESSION['user'])) {
    header('Location: /views/user/login');

    exit();
}

$user = new Donor($_SESSION['user']['person_id']);
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
                <th>Amount</th>
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
    <?php
    // Skip if the donation's donor_id doesn't match the logged-in user's person_id
    if ($donation['donor_id'] !== $user->person_id) {
        continue;
    }
    ?>

    <tr>
        <td><?php echo htmlspecialchars($user->getAsJson()['name']); ?></td>
        <td><?php echo htmlspecialchars($donation['blooddonationtype']); ?></td>
        <td><?php echo htmlspecialchars($user->blood_type->getAsValue()); ?></td>
        <td>
            <?php 
            if ($donation['blooddonationtype'] === 'Blood') {
                echo number_format($donation['number_of_liters'], 2) . ' L';
            } elseif ($donation['blooddonationtype'] === 'Plasma') {
                echo number_format($donation['number_of_liters'], 2) . ' mL';
            } else {
                echo number_format($donation['number_of_liters'], 2);
            }
            ?>
        </td>
        <td><?php echo htmlspecialchars($donation['date']); ?></td>
    </tr>
<?php endforeach; ?>

            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
