<?php
// Include the necessary database connection
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/models.php";

// Error handling setup
function handleError($message) {
    echo "<div style='color: red; font-weight: bold; text-align: center; margin: 10px;'>Error: $message</div>";
    exit();
}

// Helper function to count records in a table with error handling
function getCount($table) {
    try {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT COUNT(*) AS total FROM $table");
        
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

// Fetch summary data with error handling
try {
    $totalPersons = getCount("Person");
    $totalAddresses = getCount("Address");
    $totalDonors = getCount("Donor");
    $totalDonations = getCount("Donation");
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
        h1, h2 {
            margin: 0;
        }
        nav {
            margin: 10px 0;
            text-align: center;
        }
        nav a {
            text-decoration: none;
            color: #d9534f;
            margin: 0 15px;
            font-size: 16px;
        }
        nav a:hover {
            text-decoration: underline;
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
    <h1>Blood Donation Management System</h1>
    <p>Welcome to the system dashboard</p>
</header>

<nav>
    <a href="login.php">Login</a>
    <a href="views/register/">Register</a>
    <a href="about.php">About</a>
</nav>

<section>
    <h2>System Overview</h2>
    <p>This is an overview of the current data in the system:</p>
    <ul>
        <li><strong>Total Persons:</strong> <?php echo $totalPersons; ?></li>
        <li><strong>Total Addresses:</strong> <?php echo $totalAddresses; ?></li>
        <li><strong>Total Donors:</strong> <?php echo $totalDonors; ?></li>
        <li><strong>Total Donations:</strong> <?php echo $totalDonations; ?></li>
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
