
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
// Fetch summary data with error handling
try {
    $summary = [
        // 'totalPersons' => getCount("Person"),
        // 'totalAddresses' => getCount("Address"),
        // 'totalDonors' => getCount("Donor"),
        // 'totalDonations' => getCount("Donation"),
        'totalPersons' => 0,
        'totalAddresses' => 0,
        'totalDonors' => 0,
        'totalDonations' => 0,
    ];
} catch (Exception $e) {
    handleError($e->getMessage());
}

// Fetch blood stock information with error handling
try {
    $db = Database::getInstance();
    $query = $db->prepare("SELECT blood_type, SUM(amount) as total_amount FROM BloodStock GROUP BY blood_type");

    if (!$query) {
        throw new Exception("Failed to prepare blood stock query: " . $db->error);
    }

    if (!$query->execute()) {
        throw new Exception("Failed to execute blood stock query: " . $query->error);
    }

    $result = $query->get_result();
    if (!$result) {
        throw new Exception("Failed to fetch blood stock data.");
    }

    $bloodStock = [];
    while ($row = $result->fetch_assoc()) {
        $bloodStock[] = $row;
    }
} catch (Exception $e) {
    handleError($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background-color: #d9534f;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        nav {
            background-color: #d9534f;
            overflow: hidden;
        }
        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #c9302c;
        }
        section {
            padding: 20px;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>Blood Donation System</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="money/donations.php">Money Donations</a>
    <a href="blood_donations.php">Blood Donations</a>
    <a href="beneficiary.php">Beneficiary</a>
    <a href="events.php">Events</a>
    <a href="volunteers.php">Volunteers</a>
    <a href="other.php">Other</a>
</nav>

<section>
    <h2>System Overview</h2>
    <p>This is an overview of the current data in the system:</p>
    <ul>
        <li><strong>Total Persons:</strong> <?php echo $summary['totalPersons']; ?></li>
        <li><strong>Total Addresses:</strong> <?php echo $summary['totalAddresses']; ?></li>
        <li><strong>Total Donors:</strong> <?php echo $summary['totalDonors']; ?></li>
        <li><strong>Total Donations:</strong> <?php echo $summary['totalDonations']; ?></li>
    </ul>

    <h2>Blood Stock Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Blood Type</th>
                <th>Total Amount (Liters)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bloodStock as $stock): ?>
                <tr>
                    <td><?php echo htmlspecialchars($stock['blood_type']); ?></td>
                    <td><?php echo number_format($stock['total_amount'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($bloodStock)): ?>
                <tr>
                    <td colspan="2">No blood stock data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<footer>
    &copy; <?php echo date("Y"); ?> Blood Donation Management System. All rights reserved.
</footer>

</body>
</html>