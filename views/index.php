<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodStock.php';

// Error handling setup
function handleError($message) {
    echo "<div style='color: red; font-weight: bold; text-align: center; margin: 10px;'>Error: $message</div>";
    exit();
}

// Helper function to count records in a table with error handling
function getCount($table) {
    try {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT COUNT(*) AS total FROM `$table`");
        
        if (!$query) {
            throw new Exception("Failed to prepare query for table '$table': " . $db->error);
        }

        if (!$query->execute()) {
            throw new Exception("Failed to execute query for table '$table': " . $query->error);
        }

        $result = $query->get_result();
        if (!$result) {
            throw new Exception("Failed to fetch result for table '$table'.");
        }

        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;

    } catch (Exception $e) {
        handleError($e->getMessage());
    }
}

// Fetch summary data
try {
    $summary = [
        'totalPersons' => getCount("Persons"),
        'totalAddresses' => getCount("Addresses"),
        'totalDonors' => getCount("Donors"),
        'totalDonations' => getCount("bloodDonation"),
    ];
} catch (Exception $e) {
    handleError($e->getMessage());
}

// Fetch blood and plasma stock information
try {
    $bloodStock = BloodStock::getInstance()->getAllBloodStocks();
    $plasmaStock = BloodStock::getInstance()->getAllPlasmaStocks();
} catch (Exception $e) {
    handleError($e->getMessage());
}

$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
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
    <?php if ($isLoggedIn): ?>
        <p>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</p>
    <?php endif; ?>
</header>

<nav>
    <a href="index.php">Home</a>
    <?php if (!$isLoggedIn): ?>
        <a href="user/login">Login</a>
        <a href="user/register">Register</a>
    <?php else: ?>
        <a href="user/logout">Logout</a>
    <?php endif; ?>
    <a href="money/donations.php">Money Donations</a>
    <a href="blood_donations.php">Blood Donations</a>
    <a href="beneficiary.php">Beneficiary</a>
    <a href="events/">Events</a>
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
                <th>Total Amount</th>
                <th>Blood/Plasma</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bloodStock as $bloodType => $totalAmount): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bloodType); ?></td>
                    <td><?php echo number_format($totalAmount, 2); ?> L</td>
                    <td>Blood</td>
                </tr>
            <?php endforeach; ?>
            <?php foreach ($plasmaStock as $bloodType => $totalAmount): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bloodType); ?></td>
                    <td><?php echo number_format($totalAmount, 2); ?> mL</td>
                    <td>Plasma</td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($bloodStock) && empty($plasmaStock)): ?>
                <tr>
                    <td colspan="3">No stock data available.</td>
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
